<?php


namespace App\Controller\Web;

use App\Entity\Groups;
use App\Service\GroupService;
use App\Service\GroupUserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
class GroupController extends AbstractController
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
     * GroupController constructor.
     * @param GroupService $groupService
     * @param GroupUserService $groupUserService
     */
    public function __construct(GroupService $groupService,GroupUserService $groupUserService)
    {
        $this->groupService = $groupService;
        $this->groupUserService = $groupUserService;
    }


    /**
     * @return Response
     * @Method({"GET"})
     * @Route("/group/list", name="group_list")
     */
    public function index(){
        $groups = $this->groupService->findAllGroups();
        return $this->render('groups/index.html.twig',array('groups'=>$groups));
    }

    /**
     * @Security("has_role('ROLE_ADMIN')")
     * @Route("/group/new" , name="group_new")
     * @Method({"GET" , "POST"})
     *
     * @param Request $request
     *
     * @return render
     */

    public function createGroup(Request $request)
    {
       $group = new Groups();

       $form = $this->createFormBuilder($group)
           ->add('title', TextType::class, array('attr'=>array('class'=>'form-control')))
           ->add('save', SubmitType::class,array('label'=>'Create','attr'=>array('class'=>'btn btn-primary mt-3')))
           ->getForm();

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $title = $form->get('title')->getData();

            $this->groupService->addGroup($title);
            return new RedirectResponse($this->generateUrl('group_list'));
        }
        return $this->render('groups/create.html.twig',array('form'=>$form->createView()));
    }

    /**
     * @Security("has_role('ROLE_ADMIN')")
     * @return Response
     * @Method({"DELETE"})
     * @param $request
     * @Route("/group/delete", name="delete_group")
     */
    public function deleteGroupAjax(Request $request){
        $id = $request->get('id');
        $result=$this->groupService->deleteGroupById($id);
        exit(json_encode($result));
    }


    /**
     * @return Response
     * @Method({"GET"})
     * @param $id
     * @Route("/group/{id}/users", name="members_group")
     */
    public function viewGroupUsers($id){

        $groupUsers = $this->groupUserService->findUsersByGroupId($id);
        return $this->render('groups/users.html.twig',array('groupUsers'=>$groupUsers,'groupId'=>$id));
    }

    /**
     * @Security("has_role('ROLE_ADMIN')")
     * @return Response
     * @Method({"DELETE"})
     * @param $request
     * @Route("/group/user/delete", name="delete_user_group")
     */
    public function deleteUserFromGroupAjax(Request $request){
        $userId = $request->get('userId');
        $groupId = $request->get('groupId');
        $result=$this->groupUserService->deleteUserFromGroup($userId,$groupId);
        exit(json_encode($result));
    }

    /**
     * @Security("has_role('ROLE_ADMIN')")
     * @return Response
     * @Method({"GET"})
     * @param $id
     * @Route("/group/{id}/user/add", name="members_add_group")
     */
    public function addUsersToGroup($id){

        $groupUsers = $this->groupUserService->findUsersToAddByGroupId($id);
        return $this->render('groups/users.html.twig',array('groupUsers'=>$groupUsers,'toAdd'=>true,'groupId'=>$id));
    }

    /**
     * @Security("has_role('ROLE_ADMIN')")
     * @return Response
     * @Method({"DELETE"})
     * @param $request
     * @Route("/group/user/add", name="add_user_group")
     */
    public function addUserToGroupAjax(Request $request){
        $userId = $request->get('userId');
        $groupId = $request->get('groupId');
        $result= $this->groupUserService->addUserToGroup($userId,$groupId);
        exit(json_encode($result));
    }
}