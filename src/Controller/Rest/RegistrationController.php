<?php

namespace App\Controller\Rest;

use App\Entity\User;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use App\Service\UserService;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegistrationController extends AbstractFOSRestController
{
    /**
     * @var UserService
     */
    private $userService;
    /**
     * RegistrationController constructor.
     * @param UserService $userService
     */
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * @Rest\Get("/createUser")
     * @param Request $request
     * @param $passwordEncoder
     * @return View
     */
    public function createUser(Request $request,UserPasswordEncoderInterface $passwordEncoder): View
    {
        $users=$this->userService->getAllUsers();
        if(sizeof($users)==0) {
            $result = $this->userService->addUser($request->get('name'), $request->get('email'), $request->get('password'), $passwordEncoder);
        }
        else{
            if(!$request->get('token'))
            {
                return View::create(['error'=>true,'message'=>'admin token is needed from user table as a get token parameter'], Response::HTTP_NON_AUTHORITATIVE_INFORMATION);
            }
            $user=$this->userService->getAdmin();

            if($user['token']==$request->get('token'))
            {
                $result = $this->userService->addUser($request->get('name'), $request->get('email'), $request->get('password'), $passwordEncoder);
                return View::create($result, Response::HTTP_CREATED);
            }else{
                return View::create(['error'=>true,'message'=>'invalid token, admin token is needed from user table as a get token parameter'], Response::HTTP_NON_AUTHORITATIVE_INFORMATION);
            }
        }
    }
}
