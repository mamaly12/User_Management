<?php

namespace App\Service;

use App\Entity\GroupsUsers;
use \App\Repository\GroupsRepository;
use \App\Entity\Groups;
use Doctrine\ORM\EntityManagerInterface;
use \App\Repository\GroupsUsersRepository;
use Doctrine\ORM\EntityManager;
use App\Service\GroupUserService;

final class GroupService
{
    /**
     * @var GroupRepository
     */
    private $groupRepository;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;


    public function __construct(EntityManagerInterface  $entityManager, GroupsRepository $groupRepository)
    {
        $this->groupRepository = $groupRepository;
        $this->entityManager = $entityManager;
    }

    public function getGroupByTitle(string $title): ?Groups
    {
        return $this->groupRepository->findOneBy(array('title'=>$title));
    }

    public function findAllGroups(): ?array
    {
        return $this->groupRepository->findAllGroups();
    }

    public function addGroup(string $title): ?array
    {
        $group = $this->getGroupByTitle($title);
        if(isset($group))
        {
            return array('result'=>false,'error'=>true, 'message'=>'Group with this title already exists');
        }
        else {
            $group = new Groups();
            $group->setTitle($title);
            $this->entityManager->persist($group);
            $this->entityManager->flush();
            return array('group'=>$group,'error'=>false, 'message'=>'Group created successfully');
        }
    }

    public function deleteGroupById($id): ?array
    {
        $group = $this->groupRepository->find($id);
        if ($group) {
            $groupUserRepository=$this->entityManager->getRepository(GroupsUsers::class);
            $groupUsers= $groupUserRepository->findUsersByGroupId($id);
            if(sizeof($groupUsers)>0)
            {
                return array('result' => false, 'error' => true, 'message' =>
                    'Group with members can not be deleted. This group has '.sizeof($groupUsers). ' members. First Delete Users from Group');
            }else {
                $this->entityManager->remove($group);
                $this->entityManager->flush();
                return array('result' => true, 'error' => false, 'message' => 'Group deleted successfully');
            }
        }
        return array('result'=>false,'error'=>true, 'message'=>'Group cannot be deleted');
    }
}