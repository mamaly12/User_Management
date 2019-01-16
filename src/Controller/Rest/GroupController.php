<?php


namespace App\Controller\Rest;

use App\Entity\Groups;
use App\Service\GroupService;
use App\Service\GroupUserService;
use App\Service\UserService;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\RedirectResponse;

class GroupController extends AbstractFOSRestController
{

    /**
     * @var GroupService
     */
    private $groupService;

    /**
     * @var GroupUserService
     */
    private $groupUserService;

    /**
     * @var UserService
     */
    private $userService;
    /**
     * GroupController constructor.
     * @param GroupService $groupService
     * @param GroupUserService $groupUserService
     * @param UserService $userService
     */
    public function __construct(GroupService $groupService,GroupUserService $groupUserService,UserService $userService)
    {
        $this->groupService = $groupService;
        $this->groupUserService = $groupUserService;
        $this->userService=$userService;
    }


    /**
     * @Rest\Get("/groupList")
     * @param Request $request
     * @return View
     */
    public function groupList(Request $request): View
    {
        if(!$request->get('token'))
        {
            return View::create(['error'=>true,'message'=>'admin token is needed from user table as a get token parameter'], Response::HTTP_NON_AUTHORITATIVE_INFORMATION);
        }
        $user=$this->userService->getAdmin();
        if($user['token']==$request->get('token')) {
            $groups = $this->getDoctrine()->getRepository(Groups::class)->findAll();
            return View::create($groups, Response::HTTP_CREATED);
        } else {
            return View::create(['error' => true, 'message' => 'invalid token, admin token is needed from user table as a get token parameter'], Response::HTTP_NON_AUTHORITATIVE_INFORMATION);
        }
    }

    /**
     * @Rest\Get("/createGroup")
     * @param Request $request
     * @return View
     */

    public function createGroup(Request $request): View
    {
        if(!$request->get('token'))
        {
            return View::create(['error'=>true,'message'=>'admin token is needed from user table as a get token parameter'], Response::HTTP_NON_AUTHORITATIVE_INFORMATION);
        }
        $user=$this->userService->getAdmin();
        if($user['token']==$request->get('token')) {
            $title = $request->get('title');
            $response = $this->groupService->addGroup($title);
            return View::create($response, Response::HTTP_CREATED);
        }else{
            return View::create(['error'=>true,'message'=>'invalid token, admin token is needed from user table as a get token parameter'], Response::HTTP_NON_AUTHORITATIVE_INFORMATION);
        }

    }

    /**
     *
     * @Rest\Get("/deleteGroup")
     * @param Request $request
     * @return View
     */
    public function deleteGroup(Request $request): View
    {
        if(!$request->get('token'))
        {
            return View::create(['error'=>true,'message'=>'admin token is needed from user table as a get token parameter'], Response::HTTP_NON_AUTHORITATIVE_INFORMATION);
        }
        $user=$this->userService->getAdmin();
        if($user['token']==$request->get('token'))
        {
            $id=$request->get('groupId');
            if(isset($id))
            {
                $result = $this->groupService->deleteGroupById($id);
                return View::create($result, Response::HTTP_CREATED);
            }
        }else{
            return View::create(['error'=>true,'message'=>'invalid token, admin token is needed from user table as a get token parameter'], Response::HTTP_NON_AUTHORITATIVE_INFORMATION);
        }
    }


    /**
     *
     * @Rest\Get("/usersGroup")
     * @param Request $request
     * @return View
     */
    public function viewGroupUsers(Request $request): View
    {
        $groupId=$request->get('groupId');
        $groupUsers = $this->groupUserService->findUsersByGroupId($groupId);
        return View::create($groupUsers, Response::HTTP_CREATED);
    }

    /**
     *
     * @Rest\Get("/deleteUserFromGroup")
     * @param Request $request
     * @return View
     */
    public function deleteUserFromGroup(Request $request): View
    {
        if(!$request->get('token'))
        {
            return View::create(['error'=>true,'message'=>'admin token is needed from user table as a get token parameter'], Response::HTTP_NON_AUTHORITATIVE_INFORMATION);
        }
        $user=$this->userService->getAdmin();
        if($user['token']==$request->get('token')) {
            $userId = $request->get('userId');
            $groupId = $request->get('groupId');
            $result = $this->groupUserService->deleteUserFromGroup($userId, $groupId);
            return View::create($result, Response::HTTP_CREATED);
        }else{
            return View::create(['error'=>true,'message'=>'invalid token, admin token is needed from user table as a get token parameter'], Response::HTTP_NON_AUTHORITATIVE_INFORMATION);
        }
    }

    /**
     *
     * @Rest\Get("/addUserToGroup")
     * @param Request $request
     * @return View
     */
    public function addUserToGroup(Request $request): View
    {
        if(!$request->get('token'))
        {
            return View::create(['error'=>true,'message'=>'admin token is needed from user table as a get token parameter'], Response::HTTP_NON_AUTHORITATIVE_INFORMATION);
        }
        $user=$this->userService->getAdmin();
        if($user['token']==$request->get('token')) {
            $userId = $request->get('userId');
            $groupId = $request->get('groupId');
            $result = $this->groupUserService->addUserToGroup($userId, $groupId);
            return View::create($result, Response::HTTP_CREATED);
        }else{
            return View::create(['error'=>true,'message'=>'invalid token, admin token is needed from user table as a get token parameter'], Response::HTTP_NON_AUTHORITATIVE_INFORMATION);
        }
    }
}