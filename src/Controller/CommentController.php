<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\User;
use App\Form\CommentType;
use App\Repository\CommentRepository;
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
 * Description of CommentController
 *
 * @author abdelkarim
 */
class CommentController extends AbstractFOSRestController 
{
    /**
     * @Rest\Post("/comment", name="api_add_comment")
     * 
     * @SWG\Response(
     *     response=200,
     *     description="Add comment to published user"
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
     *             property="userComment",
     *             type="integer",
     *             example=232
     *         ),
     *         @SWG\Property(
     *             property="commentBody",
     *             type="string",
     *             example="Voila mon premiere commentaire to this publication"
     *         ),
     *         @SWG\Property(
     *             property="userPublished",
     *             type="integer",
     *             example=1
     *         ),
     *         @SWG\Property(
     *              property="isArchived",
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
     * @SWG\Tag(name="Comment")
     * @param ErrorService $errorService
     * @param CommentRepository $repo Description
     * @return \FOS\RestBundle\View\View
     * @throws \Exception
     */
    public function addComment(Request $request, ErrorService $errorService, CommentRepository $repo)
    {
        $responseCode = Response::HTTP_OK;
        $context = new Context();
        $comment = new Comment();
        $groups = ["addComment"];
        $context->setGroups($groups);
       
        try{
            $data = json_decode($request->getContent(), true);
            
            $form = $this->createForm(CommentType::class, $comment);
            
            $form->submit($data);
          
            $entityManager = $this->getDoctrine()->getManager();
            
            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager->persist($comment);
                $entityManager->flush();
                
            } else {
                $errors = $errorService->getErrorsFromForm($form);
                return new JsonResponse($errors, Response::HTTP_NOT_ACCEPTABLE);
            }
        } catch (Exception $ex) {
            $responseCode = Response::HTTP_BAD_REQUEST;
        }
        
        $view = $this->view($comment, $responseCode);
        $view->setContext($context);

        return $view;
        
    }
   
    /**
     * @Rest\Delete("/comment/remove/{id}", requirements={"id"="\d+"}, name="api_remove_comment")
     * 
     * @SWG\Response(
     *     response=200,
     *     description="remove comment for publication"
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
     * @SWG\Tag(name="Comment")
     * @param Request $request
     * @ParamConverter("comment", class="App\Entity\Comment")
     * @param ErrorService $errorService
     */
    public function removeComment(Request $request, ErrorService $errorService, Comment $comment)
    {
        $responseCode = Response::HTTP_OK;
        $group = ["updtComment"];
        $context = new Context();
         if (!($comment instanceof Comment)) {
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
     * @Rest\Get("/comment/list/{userPublished_id}", name="api_list_comment", requirements={"userPublished_id" = "\d+"})
     * 
     * @SWG\Response(
     *      response="200",
     *      description="List of comment for this user"
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
     * @SWG\Tag(name="Comment")
     * @param Request $request
     * @param ErrorService $errorService
     * @param CommentRepository
     */
    public function listAllMessages(Request $request, CommentRepository $repo)
    {
        $responseCode = Response::HTTP_OK;
        $context = new Context();
        /*
        $received = $request->attributes->get('received');
        $list = $repo->findBy(['received' => intval($received), 'archived' => 0]);
        $response =[
            "totalItems" => count($list),
            "items" => $list
        ];

        $view = $this->view($response, $responseCode);
        $view->setContext($context);

        return $view;
         
         */
    }
    
}
