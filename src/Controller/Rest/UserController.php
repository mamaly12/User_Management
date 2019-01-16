<?php


namespace App\Controller\Rest;

use App\Entity\User;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\RedirectResponse;
use App\Service\UserService;

class UserController extends AbstractFOSRestController
{

    /**
     * @var UserService
     */
    private $userService;
    /**
     * UserController constructor.
     * @param UserService $userService
     */
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     *
     * @Rest\Get("/deleteUser")
     * @param Request $request
     * @return View
     */
    public function deleteUser(Request $request): View
    {
        if(!$request->get('token'))
        {
            return View::create(['error'=>true,'message'=>'admin token is needed from user table as a get token parameter'], Response::HTTP_NON_AUTHORITATIVE_INFORMATION);
        }
        $user=$this->userService->getAdmin();

        if($user['token']==$request->get('token')) {
            $id = $request->get('userId');
            if (isset($id)) {
                $result = $this->userService->deleteUserById($id);
                return View::create($result, Response::HTTP_CREATED);
            }
            return View::create(['result'=>false,'error'=>true,'message'=>'invalid parameters'],Response::HTTP_CREATED);
        }else{
            return View::create(['result'=>false,'error'=>true,'message'=>'invalid token, admin token is needed from user table as a get token parameter'], Response::HTTP_NON_AUTHORITATIVE_INFORMATION);
        }
    }


    /**
    *
    * @Rest\Get("/viewUsers")
    * @param Request $request
    * @return View
    */
    public function viewUsers(Request $request): View
    {
        if(!$request->get('token'))
        {
            return View::create(['error'=>true,'message'=>'admin token is needed from user table as a get token parameter'], Response::HTTP_NON_AUTHORITATIVE_INFORMATION);
        }
        $user=$this->userService->getAdmin();

        if($user['token']==$request->get('token')) {

            $users = $this->getDoctrine()->getRepository(User::class)->findAll();
            return View::create(array('result'=>true,'users'=>$users), Response::HTTP_CREATED);
        }else{
            return View::create(['result'=>false,'error'=>true,'message'=>'invalid token, admin token is needed from user table as a get token parameter'], Response::HTTP_NON_AUTHORITATIVE_INFORMATION);
        }
    }

}