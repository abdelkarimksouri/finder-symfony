<?php

namespace App\Controller;

use App\Entity\Invitation;
use App\Entity\User;
use App\Form\InvitationType;
use App\Repository\InvitationRepository;
use FOS\RestBundle\Context\Context;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Request\ParamFetcher;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use App\Service\ErrorService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use App\ValueObject\DefaultParameters;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Description of InvitationController
 *
 * @author abdelkarim
 */
class InvitationController extends \FOS\RestBundle\Controller\AbstractFOSRestController 
{
    /**
     * @Rest\Post("/invitation", name="invit")
     * 
     * @SWG\Response(
     *     response=200,
     *     description="Send invitation to another user"
     * ),
     * @SWG\Response(
     *     response=403,
     *     description="Forbidden"
     * ),
     * 
     * @SWG\Response(
     *     response=500,
     *     description="Technical error",
     * ),
     * @SWG\Response(
     *     response=406,
     *     description="Form validation error",
     * ),
     * 
     * @SWG\Parameter(
     *     name="body",
     *     description="....",
     *     in="body",
     *     @SWG\Schema(
     *         @SWG\Property(
     *             property="sender",
     *             type="object",
     *             example="232"
     *         ),
     *         @SWG\Property(
     *             property="received",
     *             type="object",
     *             example="233"
     *         ),
     *         @SWG\Property(
     *             property="status",
     *             type="integer",
     *             example=1
     *         ),
     *      )
     *  )
     * 
     * @SWG\Parameter(
     *     name="Authorization",
     *     in="header",
     *     required=true,
     *     type="string",
     *     default="Bearer TOKEN",
     *     description="Bearer token",
     * )
     * @SWG\Tag(name="Invitation")
     * @param ErrorService $errorService
     * @param InvitationRepository $repo Description
     * @return \FOS\RestBundle\View\View
     * @throws \Exception
     */
    public function sendInvitation(Request $request, ErrorService $errorService, InvitationRepository $repo)
    {
        $responseCode = Response::HTTP_OK;
        $context = new Context();
        $invitation = new Invitation();
        $groups = ["addInvi"];
        $context->setGroups($groups);
       
        try{
            $data = json_decode($request->getContent(), true);
            $form = $this->createForm(InvitationType::class, $invitation);
            $form->submit($data);
            $sender = $form->getData()->getSender();
            $received = $form->getData()->getReceived();
            if ((!$sender instanceof User) || (!$received instanceof User)) {
                return new JsonResponse('Sorry but this User not exist', Response::HTTP_NOT_ACCEPTABLE);
            }
            
            $entityManager = $this->getDoctrine()->getManager();
            $checkInvit = $repo->findOneBy(['sender' => $sender, 'received' => $received]);
            if ($checkInvit instanceof Invitation) {
                return new JsonResponse('Sorry but you have an existant invitation ', Response::HTTP_NOT_ACCEPTABLE);
            }
            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager->persist($invitation);
                $entityManager->flush();
                
            } else {
                $errors = $errorService->getErrorsFromForm($form);

                return new JsonResponse($errors, Response::HTTP_NOT_ACCEPTABLE);
            }
        } catch (Exception $ex) {
            $responseCode = Response::HTTP_BAD_REQUEST;
        }

        $view = $this->view($invitation, $responseCode);
        $view->setContext($context);

        return $view;
    }
    
    /**
     * @Rest\Patch("/invitation/accept/{id}", name="api_accept_invitation", requirements={"id"="\d+"})
     * 
     * @SWG\Response(
     *     response=200,
     *     description="Accept invitation"
     * ),
     * @SWG\Response(
     *     response=403,
     *     description="Forbidden",
     *     examples={
     *          "invalid id invitation":{
     *              "message": "Invalid credentials."
     *          },
     *          "Invalid customer ref/scope":{
     *              "message": "Access Denied"
     *          },
     *     }
     * ),
     *@SWG\Response(
     *     response=500,
     *     description="Technical error",
     * ),
     * @SWG\Response(
     *     response=406,
     *     description="Form validation error",
     * ),
     * 
     * @SWG\Parameter(
     *     name="body",
     *     description="....",
     *     in="body",
     *     @SWG\Schema(
     *         @SWG\Property(
     *             property="sender",
     *             type="integer",
     *             example= 233
     *         ),
     *         @SWG\Property(
     *             property="received",
     *             type="object",
     *             example="234"
     *         ),
     *         @SWG\Property(
     *             property="status",
     *             type="integer",
     *             example=1
     *         )
     *      )
     *  )
     * @SWG\Parameter(
     *     name="Authorization",
     *     in="header",
     *     required=true,
     *     type="string",
     *     default="Bearer TOKEN",
     *     description="Bearer token",
     * )
     * 
     * @SWG\Tag(name="Invitation")
     * @param Request $request
     * @ParamConverter("invitation", class="App\Entity\Invitation")
     * @param ErrorService $errorService
     * @param InvitationRepository $repo
     */
    public function acceptInvitation(Request $request, ErrorService $errorService, Invitation $invitation, InvitationRepository $repo)
    {
        $responseCode = Response::HTTP_OK;
        $context = new Context();
        $groups = ["acceptInvi"];
        $context->setGroups($groups);
        $data = json_decode($request->getContent(),true);
        if ($data['status'] != 1 ) {
            throw new HttpException(Response::HTTP_NOT_FOUND, 'Value not valid');
        }
        $received = $data['received'];
        if (!$invitation instanceof Invitation || $invitation->getReceived()->getId() != $received) {
            throw new HttpException(Response::HTTP_NOT_FOUND, 'Resource not found');
        }
        
        try{
            
            $invitation->setStatus($data['status']);
            
            $form = $this->createForm(InvitationType::class, $invitation);
            $form->submit($data);
              
            $entityManager = $this->getDoctrine()->getManager();
            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager->persist($invitation);
                $entityManager->flush();
                // TODO add column Accepted_at type Date
                
            } else {
                $errors = $errorService->getErrorsFromForm($form);
                return new JsonResponse($errors, Response::HTTP_NOT_ACCEPTABLE);
            }
            
        } catch (Exception $ex) {
            
            $responseCode = Response::HTTP_BAD_REQUEST;
            echo $ex->getMessage();
        }
        
        $view = $this->view($invitation, $responseCode);
        $view->setContext($context);

        return $view;
    }
    
    /**
     * @Rest\Delete("/invitation/remove/{id}", requirements={"id"="\d+"}, name="api_remove_invitation")
     * 
     * @SWG\Response(
     *     response=200,
     *     description="remove invitation"
     * ),
     * @SWG\Response(
     *     response=400,
     *     description="Ressource not found"
     * ),
     * 
     * @SWG\Response(
     *     response=500,
     *     description="Technical error",
     * ),
     * @SWG\Response(
     *     response=406,
     *     description="Form validation error",
     * ),
     * 
     * @SWG\Parameter(
     *     name="Authorization",
     *     in="header",
     *     required=true,
     *     type="string",
     *     default="Bearer TOKEN",
     *     description="Bearer token",
     * )
     * 
     * @SWG\Tag(name="Invitation")
     * @param Request $request
     * @ParamConverter("invitation", class="App\Entity\Invitation")
     * @param ErrorService $errorService
     * @param InvitationRepository
     */
    public function removeInvitation(Request $request, ErrorService $errorService, Invitation $invitation)
    {
        $responseCode = Response::HTTP_OK;
        $context = new Context();
        //$data = json_decode($request->getContent(),true);
         if (!($invitation instanceof Invitation)) {
            throw new HttpException(Response::HTTP_NOT_FOUND, 'Resource not found');
        }
        try{
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($invitation);
            $entityManager->flush();
        } catch (Exception $ex) {
            
            $responseCode = Response::HTTP_BAD_REQUEST;
            echo $ex->getMessage();
        }
        
        $view = $this->view($invitation, $responseCode);
        $view->setContext($context);

        return $view;
    }
    
    /**
     * Api For User to Show Her list Of invitation on StandBy
     * 
     * @Rest\Get("/invitation/list/{received}", name="api_list_invitation", requirements={"received" = "\d+"})
     * 
     * @SWG\Response(
     *      response="200",
     *      description="List of received Invitation on waiting"
     * ),
     * @SWG\Response(
     *     response=400,
     *     description="Ressource not found"
     * ),
     * 
     * @SWG\Response(
     *     response=500,
     *     description="Technical error",
     * ),
     * @SWG\Response(
     *     response=406,
     *     description="Form validation error",
     * ),
     * 
     * @SWG\Parameter(
     *     name="Authorization",
     *     in="header",
     *     required=true,
     *     type="string",
     *     default="Bearer TOKEN",
     *     description="Bearer token",
     * )
     * 
     * @SWG\Tag(name="Invitation")
     * @param Request $request
     * @param ErrorService $errorService
     * @param InvitationRepository
     */
    public function listWaitingInvitation(Request $request, InvitationRepository $repo)
    {
        $responseCode = Response::HTTP_OK;
        $context = new Context();
        
        $received = $request->attributes->get('received');
        $list = $repo->findBy(['received' => intval($received), 'status' => 0]);
        $response =[
            "totalItems" => count($list),
            "items" => $list
        ];

        $view = $this->view($response, $responseCode);
        $view->setContext($context);

        return $view;
    }
    
}
