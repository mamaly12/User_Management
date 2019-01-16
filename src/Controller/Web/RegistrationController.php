<?php

namespace App\Controller\Web;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Security\LoginFormAuthenticator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use App\Service\UserService;
use Symfony\Component\HttpFoundation\RedirectResponse;

class RegistrationController extends AbstractController
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
     * @Route("/register", name="app_register")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder, GuardAuthenticatorHandler $guardHandler, LoginFormAuthenticator $authenticator): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $users=$this->userService->getAllUsers();
            $result = $this->userService->addUser( $form->get('name')->getData(),$form->get('email')->getData(),$form->get('plainPassword')->getData(),$passwordEncoder);
            // do anything else you need here, like send an email
            if(isset($result['user']) && sizeof($users)==0) {
                $user = $result['user'];
                return $guardHandler->authenticateUserAndHandleSuccess(
                    $user,
                    $request,
                    $authenticator,
                    'main' // firewall name in security.yaml
                );
            }else{
                return new RedirectResponse('/user/list');
            }
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}
