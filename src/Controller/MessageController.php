<?php

namespace App\Controller;

use App\Entity\Message;
use App\Entity\User;
use App\Form\MessageType;
use App\Repository\MessageRepository;
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
 * Description of MessageController
 *
 * @author abdelkarim
 */
class MessageController extends AbstractFOSRestController 
{
    /**
     * @Rest\Post("/messages", name="api_send_message")
     * 
     * @SWG\Response(
     *     response=200,
     *     description="Send Message to another user"
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
     *             property="body",
     *             type="string",
     *             example="Hello Abdel Comment va-tu"
     *         ),
     *         @SWG\Property(
     *              property="archived",
     *              type="integer",
     *              example="1"
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
     * @SWG\Tag(name="Message")
     * @param ErrorService $errorService
     * @param MessageRepository $repo Description
     * @return \FOS\RestBundle\View\View
     * @throws \Exception
     */
    public function sendMessage(Request $request, ErrorService $errorService, MessageRepository $repo)
    {
        $responseCode = Response::HTTP_OK;
        $context = new Context();
        $message = new Message();
        $groups = ["addMsg"];
        $context->setGroups($groups);
       
        try{
            $data = json_decode($request->getContent(), true);
            $form = $this->createForm(MessageType::class, $message);
            
            $form->submit($data);
           
            $sender = $form->getData()->getSender();
            $received = $form->getData()->getReceived();
            if ((!$sender instanceof User) || (!$received instanceof User)) {
                return new JsonResponse('Sorry but this User not exist', Response::HTTP_NOT_ACCEPTABLE);
            }
            
            $entityManager = $this->getDoctrine()->getManager();
            
            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager->persist($message);
                $entityManager->flush();
                
            } else {
                $errors = $errorService->getErrorsFromForm($form);
                dd($errors);
                return new JsonResponse($errors, Response::HTTP_NOT_ACCEPTABLE);
            }
        } catch (Exception $ex) {
            $responseCode = Response::HTTP_BAD_REQUEST;
        }
        
        $view = $this->view($message, $responseCode);
        $view->setContext($context);

        return $view;
    }
   
    /**
     * @Rest\Delete("/message/remove/{id}", requirements={"id"="\d+"}, name="api_remove_message")
     * 
     * @SWG\Response(
     *     response=200,
     *     description="remove message"
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
     * @SWG\Tag(name="Message")
     * @param Request $request
     * @ParamConverter("message", class="App\Entity\Message")
     * @param ErrorService $errorService
     */
    public function removeMessage(Request $request, ErrorService $errorService, Message $message)
    {
        $responseCode = Response::HTTP_OK;
        $group = ["updtMessage"];
        $context = new Context();
         if (!($message instanceof Message)) {
            throw new HttpException(Response::HTTP_NOT_FOUND, 'Resource not found');
        }
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
    }
    
    /**
     * Api For User to Show Her list Of message on StandBy
     * 
     * @Rest\Get("/message/list/{received}", name="api_list_message", requirements={"received" = "\d+"})
     * 
     * @SWG\Response(
     *      response="200",
     *      description="List of received message on waiting"
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
     * @SWG\Tag(name="Message")
     * @param Request $request
     * @param ErrorService $errorService
     * @param MessageRepository
     */
    public function listAllMessages(Request $request, MessageRepository $repo)
    {
        $responseCode = Response::HTTP_OK;
        $context = new Context();
        
        $received = $request->attributes->get('received');
        $list = $repo->findBy(['received' => intval($received), 'archived' => 0]);
        $response =[
            "totalItems" => count($list),
            "items" => $list
        ];

        $view = $this->view($response, $responseCode);
        $view->setContext($context);

        return $view;
    }
    
}
