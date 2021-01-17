<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
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
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;


/**
 * Description of UserManagementController
 *
 * @author abdelkarim
 */
class UserManagementController extends AbstractFOSRestController
{
       
    /**
     * @Rest\Post("/get_user", name="get_user")
     *
     * @SWG\Response(
     *     response=200,
     *     description="Return User connected"
     * ),
     *
     * @SWG\Response(
     *     response=403,
     *     description="Forbidden",
     *     examples={
     *          "invalid username/password":{
     *              "message": "Invalid credentials."
     *          }
     *     }
     * ),
     * @SWG\Response(
     *     response=500,
     *     description="Technical error",
     * ),
     * @SWG\Response(
     *     response=204,
     *     description="Request success but no content on response",
     * ),
     * @SWG\Parameter(
     *     name="body",
     *     description="....",
     *     in="body",
     *     @SWG\Schema(
     *         @SWG\Property(
     *             property="token",
     *             type="string",
     *             example="AxdttfKdldmk.sdddssdleiji"
     *         )
     *      )
     * )
     * @param Request $request
     * @param UserRepository
     * @SWG\Tag(name="Authentication")
     */
    public function getUserByToken(Request $request, JWTEncoderInterface $jwtEncoder, UserRepository $repo)
    {
         $responseCode = Response::HTTP_OK;
        $context = new Context();
        $credentials = json_decode($request->getContent(), true);
        $user = $jwtEncoder->decode($credentials['token']);
        if ($user === false) {
            throw new CustomUserMessageAuthenticationException('Invalid Token');
        }
       // dump($user['username']);die;
        $username = $user['username'];
        
        $data = $repo
                    ->findOneBy(['email' => $username]);
        
          $response = [
            "items" => $data
        ];

        $view = $this->view($data, $responseCode);
        $view->setContext($context);
        return $view;
    }
    
    
    /**
     * @Rest\Get("/users", name="api_users_list")
     *
     * @SWG\Response(
     *     response=200,
     *     description="Return list of users",
     *     @SWG\Items(ref=@Model(type=User::class, groups={"user_profil"}))
     * ),
     * @SWG\Response(
     *     response=403,
     *     description="Forbidden",
     *     examples={
     *          "invalid username/password":{
     *              "message": "Invalid credentials."
     *          },
     *          "Invalid customer ref/scope":{
     *              "message": "Access Denied"
     *          },
     *     }
     * ),
     * @SWG\Response(
     *     response=404,
     *     description="Not Found error",
     * ),
     * @SWG\Response(
     *     response=500,
     *     description="Technical error",
     * ),
     * @Rest\QueryParam(name="criteria", strict=false,   nullable=false)
     * @Rest\QueryParam(name="limit", strict=false,  nullable=true)
     * @Rest\QueryParam(name="offset", strict=false, nullable=true)
     * @SWG\Tag(name="User")
     * @param ParamFetcher $paramFetcher
     * @param UserRepository $repo
     * @return \FOS\RestBundle\View\View
     * @throws \Exception
     */
    public function listUsers(paramFetcher $paramFetcher, UserRepository $repo)
    {
        $responseCode = Response::HTTP_OK;
        $context = new Context();
        $groups = ['user_profil'];
        $context->setGroups($groups);
        $limit = $paramFetcher->get('limit') ?? $this->getParameter('defaultLimit');
        $offset = $paramFetcher->get('offset') ?? $this->getParameter('defaultOffset');
        $users = $repo->findBySomeField($paramFetcher->get('criteria'), $limit, $offset);
             
        $response = [
            "totalItems" => count($users),
            "items" => $users
        ];

        $view = $this->view($response, $responseCode);
        $view->setContext($context);
        return $view;
    }
    
    /**
     * @Rest\Post("/users", name="api_user_create")
     * @SWG\Response(
     *     response=200,
     *     description="Create a user"
     * ),
     * @SWG\Response(
     *     response=403,
     *     description="Forbidden",
     *     examples={
     *          "invalid username/password":{
     *              "message": "Invalid credentials."
     *          },
     *          "Invalid customer ref/scope":{
     *              "message": "Access Denied"
     *          },
     *     }
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
     *             property="email",
     *             type="string",
     *             example="abdel12@gmail.com"
     *         ),
     *         @SWG\Property(
     *             property="password",
     *             type="string",
     *             example="chatFinder"
     *         ),
     *         @SWG\Property(
     *             property="profil",
     *             type="object",
     *             example={
     *                 "firstName": "abdel",
     *                 "lastName": "myfarma",
     *                 "ddn": "1990-31-10 15:15:15",
     *                 "phoneNumber": "0625669",  
     *                 "height": "170",
     *                 "weight": "50",
     *                      "address"= {
     *                          "streetNumber": "46",
     *                          "streetName":"nogent",
     *                          "streetComplementary":"dsfsf sfdsf sd ",
     *                          "zipCode":"94130",
     *                          "longitude":"12.4",
     *                          "latitude":"33,5555",
     *                          "createdAt":"2019-07-29 21:30:34",
     *                          "updatedAt":"2019-07-29 21:30:34",
     *                          "city":"Ariana",
     *                          "country": 10
     *                        }
     *             },
     *             @SWG\Items(
     *                 type="object",
     *                 @SWG\Property(property="key", type="string"),
     *                 @SWG\Property(property="value", type="string")
     *             )
     *          ),
     *      )
     * )
     * 
     * @SWG\Tag(name="User")
     * @param ErrorService $errorService
     * @return \FOS\RestBundle\View\View
     * @throws \Exception
     */
    public function create(Request $request, ErrorService $errorService, UserPasswordEncoderInterface $encoder)
    {
        $responseCode = Response::HTTP_OK;
        $user = new User();
        $context = new Context();
        $groups = ["addUser"];
        $context->setGroups($groups);
        try {
            $form = $this->createForm(UserType::class, $user);
            $data = json_decode($request->getContent(),true);
            $form->submit($data);
            $entityManager = $this->getDoctrine()->getManager();
            if ($form->isSubmitted() && $form->isValid()) {
                $password = $encoder->encodePassword($user, $user->getPassword());
                $user->setPassword($password);
                $entityManager->persist($user);
                $entityManager->flush();
                
            } else {
                
                $errors = $errorService->getErrorsFromForm($form);

                return new JsonResponse($errors, Response::HTTP_NOT_ACCEPTABLE);
            }
        } catch(HttpException $ex) {
            $responseCode = Response::HTTP_BAD_REQUEST;
        }

        $view = $this->view($user, $responseCode);
        $view->setContext($context);

        return $view;
    }
    
    /**
     * @Rest\Put("/user/{id}", name="api_user_update", requirements={"id"="\d+"})
     * @Rest\View(serializerGroups={"put_user"})
     * @SWG\Response(
     *     response=200,
     *     description="Create a user"
     * ),
     * @SWG\Response(
     *     response=403,
     *     description="Forbidden",
     *     examples={
     *          "invalid username/password":{
     *              "message": "Invalid credentials."
     *          },
     *          "Invalid customer ref/scope":{
     *              "message": "Access Denied"
     *          },
     *     }
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
     *             property="profil",
     *             type="object",
     *             example={
     *                 "firstName": "abdel",
     *                 "lastName": "myfarma",
     *                 "ddn": "1990-31-10 15:15:15",
     *                 "phoneNumber": "0625669",  
     *                 "height": "170",
     *                 "weight": "50",
     *                      "address"= {
     *                          "streetNumber": "46",
     *                          "streetName":"nogent",
     *                          "streetComplementary":"dsfsf sfdsf sd ",
     *                          "zipCode":"94130",
     *                          "longitude":"12.4",
     *                          "latitude":"33,5555",
     *                          "createdAt":"2019-07-29 21:30:34",
     *                          "updatedAt":"2019-07-29 21:30:34",
     *                          "city":"Ariana",
     *                          "country": 10
     *                        }
     *             },
     *             @SWG\Items(
     *                 type="object",
     *                 @SWG\Property(property="key", type="string"),
     *                 @SWG\Property(property="value", type="string")
     *             )
     *          ),
     *      )
     * )
     * 
     * @SWG\Parameter(
     *     name="Authorization",
     *     in="header",
     *     required=true,
     *     type="string",
     *     default="Bearer TOKEN",
     *     description="Bearer token",
     * )
     * @SWG\Tag(name="User")
     * @param Request $request
     * @ParamConverter("user", class="App\Entity\User")
     * @param ErrorService $errorService
     * @param UserPasswordEncoderInterface $encoder
     */
    public function updateUser(Request $request, User $user, ErrorService $errorService, UserPasswordEncoderInterface $encoder)
    {
        $responseCode = Response::HTTP_OK;
        $context = new Context();
        $groups = ["put_user"];
        $context->setGroups($groups);
        try {
            $form = $this->createForm(UserType::class, $user);
            $email = $form->getData()->getEmail();
            $pass  = $form->getData()->getPassword();
            $data = json_decode($request->getContent(), true);
            $data['email'] = $email;
            $data['password'] = $pass;
            $form->submit($data);
            $entityManager = $this->getDoctrine()->getManager();
            if ($form->isSubmitted() && $form->isValid()) {
                $password = $encoder->encodePassword($user, $user->getPassword());
                $user->setPassword($password);
                $entityManager->persist($user);
                $entityManager->flush();
                
            } else {
                $errors = $errorService->getErrorsFromForm($form);

                return new JsonResponse($errors, Response::HTTP_NOT_ACCEPTABLE);
            }
        } catch(HttpException $ex) {
            $responseCode = Response::HTTP_BAD_REQUEST;
        }

        $view = $this->view($user, $responseCode);
        $view->setContext($context);

        return $view;
    }
    
    
    /**
     * @Rest\Delete("/user/{id}", name="api_user_delete", requirements={"id"="\d+"})
     * @SWG\Response(
     *     response=200,
     *     description="Create a user"
     * ),
     * @SWG\Response(
     *     response=403,
     *     description="Forbidden",
     *     examples={
     *          "invalid username/password":{
     *              "message": "Invalid credentials."
     *          },
     *          "Invalid customer ref/scope":{
     *              "message": "Access Denied"
     *          },
     *     }
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
     * @SWG\Tag(name="User")
     * @param Request $request
     * @ParamConverter("user", class="App\Entity\User")
     * @param ErrorService $errorService
     * 
     */
    public function removeUser(User $user)
    {
        $responseCode = Response::HTTP_OK;
        $context = new Context();
        $groups = ["put_user"];
        $context->setGroups($groups);
         if (empty($user)) {
            throw new HttpException(Response::HTTP_NOT_FOUND, 'Resource not found');
        }
        try{
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($user);
            $entityManager->flush();
            $view = $this->view($user, Response::HTTP_NO_CONTENT);
            $view->setContext($context);
            
            return $view;
        } catch (Exception $ex) {
            
            $responseCode = Response::HTTP_BAD_REQUEST;
            echo $ex->getMessage();
        }
        $view = $this->view($user, $responseCode);
        $view->setContext($context);

        return $view;

    }
      
    /**
     * Api For User to Show List of Friend
     * 
     * @Rest\Get("/users/friendList/{received}", name="api_list_friend", requirements={"received" = "\d+"})
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
     * @SWG\Tag(name="User")
     * @param Request $request
     * @param ErrorService $errorService
     * @param InvitationRepository
     */
    public function listFriendUser(Request $request, InvitationRepository $repo)
    {
        $responseCode = Response::HTTP_OK;
        $context = new Context();
        
        $received = $request->attributes->get('received');
        $list = $repo->findBy(['received' => intval($received), 'status' => 1]);
        $response =[
            "totalItems" => count($list),
            "items" => $list
        ];

        $view = $this->view($response, $responseCode);
        $view->setContext($context);

        return $view;
    }
    
}
