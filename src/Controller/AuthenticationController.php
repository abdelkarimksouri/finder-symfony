<?php

namespace App\Controller;

use FOS\RestBundle\Controller\Annotations as Rest;
use Swagger\Annotations as SWG;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\UserRepository;

class AuthenticationController extends AbstractFOSRestController
{
    
    /**
     * @Rest\Post("/login_check", name="api_login_check")
     *
     * @SWG\Response(
     *     response=200,
     *     description="Delete a pharmacy"
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
     *             property="username",
     *             type="string",
     *             example="abdel1@gmail.com"
     *         ),
     *        @SWG\Property(
     *           property="password",
     *           type="string",
     *           example="myfarma"
     *       )
     *   )
     * )
     * @SWG\Tag(name="Authentication")
     */
    public function authenticate(){}   

}