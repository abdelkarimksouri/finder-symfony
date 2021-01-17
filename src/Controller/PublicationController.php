<?php

namespace App\Controller;

use App\Entity\UserPublished;
use App\Entity\User;
use App\Form\UserPublishedType;
use App\Repository\UserPublishedRepository;
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
 * Description of PublicationController
 *
 * @author abdelkarim
 */
class PublicationController extends AbstractFOSRestController 
{
    /**
     * @Rest\Post("/publication", name="api_add_publication")
     * 
     * @SWG\Response(
     *     response=200,
     *     description="Add publication By user"
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
     *             property="userPublished",
     *             type="integer",
     *             example= 225
     *         ),
     *         @SWG\Property(
     *             property="publishedText",
     *             type="string",
     *             example="Papapapa"
     *         ),
     *         @SWG\Property(
     *             property="mediaId",
     *             type="integer",
     *             example=2
     *         ),
     *         @SWG\Property(
     *              property="isArchived",
     *              type="integer",
     *              example=0
     *          ),
     *         @SWG\Property(
     *              property="isUpdated",
     *              type="integer",
     *              example=0
     *          ),
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
     * @SWG\Tag(name="Publication")
     * @param ErrorService $errorService
     * @param UserPublishedRepository $repo Description
     * @return \FOS\RestBundle\View\View
     * @throws \Exception
     */
    public function addPublication(Request $request, ErrorService $errorService, UserPublishedRepository $repo)
    {
        $responseCode = Response::HTTP_OK;
        $context = new Context();
        $userPublished = new UserPublished();
        $groups = ["addPub"];
        $context->setGroups($groups);
        
        try{
            $data = json_decode($request->getContent(), true);
            $form = $this->createForm(UserPublishedType::class, $userPublished);
            
            $form->submit($data);
          
            $entityManager = $this->getDoctrine()->getManager();
            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager->persist($userPublished);
                $entityManager->flush();
                
            } else {
                $errors = $errorService->getErrorsFromForm($form);
                return new JsonResponse($errors, Response::HTTP_NOT_ACCEPTABLE);
            }
        } catch (Exception $ex) {
            $responseCode = Response::HTTP_BAD_REQUEST;
        }
        
        $view = $this->view($userPublished, $responseCode);
        $view->setContext($context);

        return $view;
        
    }
   
    /**
     * @Rest\Delete("/publication/remove/{id}", requirements={"id"="\d+"}, name="api_remove_publication")
     * 
     * @SWG\Response(
     *     response=200,
     *     description="remove publication"
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
     * @SWG\Tag(name="Publication")
     * @param Request $request
     * @ParamConverter("userPublished", class="App\Entity\UserPublished")
     * @param ErrorService $errorService
     */
    public function removePublication(Request $request, ErrorService $errorService, UserPublished $userPublished)
    {
        $responseCode = Response::HTTP_OK;
        $group = ["removePub"];
        $context = new Context();
         if (!($userPublished instanceof UserPublished)) {
            throw new HttpException(Response::HTTP_NOT_FOUND, 'Resource not found');
        }
        /*
        try{
            $form = $this->createForm(MessageType::class, $message, ['validation_groups' => 'updtMessage']);
            $data = json_decode($request->getContent(),true);
            $data['body'] = $form->getData()->getBody();
            $form->submit($data);
           if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($message);
            $entityManager->flush();
                
            } else {
                $errors = $errorService->getErrorsFromForm($form);
                return new JsonResponse($errors, Response::HTTP_NOT_ACCEPTABLE);
            }

        } catch (Exception $ex) {
            
            $responseCode = Response::HTTP_BAD_REQUEST;
            echo $ex->getMessage();
        }
        
        $response = new Response();
            $response->setContent(json_encode([
                'data' => 'OK',
                'status' => $responseCode
            ]));
        
       return $response;
         
         */
    }
    
    /**
     * Api For User to Show Her list Of message on StandBy
     * 
     * @Rest\Get("/publication/list/{id}", name="api_list_publication", requirements={"id" = "\d+"})
     * 
     * @SWG\Response(
     *      response="200",
     *      description="List of my publication"
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
     * 
     * @SWG\Tag(name="Publication")
     * @param Request $request
     * @param ErrorService $errorService
     * @param UserPublishedRepository
     */
    public function listPublications(Request $request, UserPublishedRepository $repo)
    {
        $responseCode = Response::HTTP_OK;
        $context = new Context();
        
        $id = $request->attributes->get('id');
        $list = $repo->findBy(['id' => intval($id), 'isArchived' => 0]);
        $response =[
            "totalItems" => count($list),
            "items" => $list
        ];

        $view = $this->view($response, $responseCode);
        $view->setContext($context);

        return $view;
    }
    
}
