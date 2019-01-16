<?php

namespace App\Service;

use \App\Repository\GroupsUsersRepository;
use \App\Entity\GroupsUsers;
use Doctrine\ORM\EntityManagerInterface;


final class GroupUserService
{
    /**
     * @var GroupsUsersRepository
     */
    private $groupUserRepository;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;


    public function __construct(EntityManagerInterface  $entityManager, GroupsUsersRepository $groupUserRepository)
    {
        $this->groupUserRepository = $groupUserRepository;
        $this->entityManager = $entityManager;
    }


    public function getGroupUserByUserIdAndGroupId($userId,$groupId): ?GroupsUsers
    {
        return $this->groupUserRepository->findBy(array('userId'=>$userId,'groupId'=>$groupId));
    }

    public function getGroupUsers($groupId): ?GroupsUsers
    {
        return $this->groupUserRepository->findBy(array('groupId'=>$groupId));
    }

    public function addUserToGroup($userId,$groupId): ?array
    {
        $groupUser = $this->groupUserRepository->findOneBy(array('userId'=>$userId,'groupId'=>$groupId));
        if (isset($groupUser)) {
            return array('result'=>false,'error'=>true, 'message'=>'User Already Added');
        }else {
            $groupUser = new GroupsUsers();
            $groupUser->setGroupId($groupId);
            $groupUser->setUserId($userId);
            $this->entityManager->persist($groupUser);
            $this->entityManager->flush();
            return array('groupUser'=>$groupUser,'error'=>false, 'message'=>'User has been added to the group successfully');
        }
    }


    public function deleteUserFromGroup($userId,$groupId): ?array
    {
        $groupUser = $this->groupUserRepository->findOneBy(array('userId'=>$userId,'groupId'=>$groupId));
        if (!isset($groupUser)) {
            return array('result'=>false,'error' => true, 'message' => 'No user has been found');
        }else{
            $this->entityManager->remove($groupUser);
            $this->entityManager->flush();
            return array('result'=>true,'error' => false, 'message' => 'user deleted from the group successfully');
        }
    }

    public function findUsersByGroupId($groupId): ?array
    {
        $users= $this->groupUserRepository->findUsersByGroupId($groupId);
        return array('users'=>$users,'error' => false, 'message' => '');
    }

    public function findUsersToAddByGroupId($groupId): ?array
    {
        return $this->groupUserRepository->findUsersToAddByGroupId($groupId);
    }

}