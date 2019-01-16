<?php


namespace App\Controller\Web;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use App\Service\UserService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class UserController extends AbstractController
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
     * @return Response
     * @Method({"GET"})
     * @Route("/user/list", name="home_url")
     */
    public function index(){
        $users = $this->getDoctrine()->getRepository(User::class)->findAll();
        $adminUser=$this->userService->getAdmin();
        $adminData=array();
        if(isset($adminUser))
        {
            $adminData['id']=(int)$adminUser['userId'];
            $adminData['token']=(int)$adminUser['token'];

        }
        return $this->render('users/index.html.twig',array('users'=>$users,'adminData'=>$adminData));
    }

    /**
     *
     * @Security("has_role('ROLE_ADMIN')")
     * @return Response
     * @Method({"DELETE"})
     * @param $request
     * @Route("/user/delete", name="delete_user")
     */
    public function deleteUserAjax(Request $request){
        $id = $request->get('id');
        $result=$this->userService->deleteUserById($id);
        exit(json_encode($result));
    }

}