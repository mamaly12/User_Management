<?php

namespace App\Service;

use App\Entity\GroupsUsers;
use \App\Repository\UserRepository;
use \App\Entity\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Doctrine\ORM\EntityManagerInterface;

final class UserService
{
    /**
     * @var UserRepositoryInterface
     */
    private $userRepository;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;


    public function __construct(EntityManagerInterface  $entityManager, UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
        $this->entityManager = $entityManager;
    }


    public function getUserByName(string $name): ?User
    {
        return $this->userRepository->findOneBy(array('name'=>$name));
    }

    public function getAllUsers(): ?array
    {
        return $this->userRepository->findAll();
    }

    public function getAdmin()
    {
        return$this->userRepository->getAdmin();
    }

    public function addUser(string $name, string $email, string $password,UserPasswordEncoderInterface $passwordEncoder): ?array
    {

        $user = new User();
        $user->setName($name);
        $user->setEmail($email);
        $user->setToken(uniqid());
        $users = $this->userRepository->findAll();
        if(sizeof($users)>0)
        {
            $user->setRoles(array('ROLE_USER'));
        }else{
            $user->setRoles(array('ROLE_ADMIN','ROLE_USER'));
        }
        $user->setPassword(
            $passwordEncoder->encodePassword(
                $user,
                $password
            )
        );
        $this->entityManager->persist($user);
        $this->entityManager->flush();
        return array('user'=>$user,'error'=>false, 'message'=>'User registered successfully');
    }

    public function deleteUserById($id): ?array
    {
        $user = $this->userRepository->find($id);
        if ($user) {
            $groupUserRepository=$this->entityManager->getRepository(GroupsUsers::class);
            $groupUserRepository->DeleteByUserId($id);
            $this->entityManager->remove($user);
            $this->entityManager->flush();
            return array('result'=>true,'error'=>false, 'message'=>'User deleted successfully');
        }
        return array('result'=>false,'error'=>true, 'message'=>'User cannot be deleted');
    }

}