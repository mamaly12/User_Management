<?php

namespace App\Repository;

use App\Entity\GroupsUsers;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method GroupsUsers|null find($id, $lockMode = null, $lockVersion = null)
 * @method GroupsUsers|null findOneBy(array $criteria, array $orderBy = null)
 * @method GroupsUsers[]    findAll()
 * @method GroupsUsers[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 *
 */
class GroupsUsersRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, GroupsUsers::class);
    }

     /**
      * @return GroupsUsers[] Returns an array of GroupsUsers objects
      * @param $groupId
      */

    public function findUsersByGroupId($groupId): ?array
    {
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery(
            'SELECT u.id as userId, u.name as name, gu.groupId as groupId, gu.id  as id 
        FROM App\Entity\User u, App\Entity\GroupsUsers gu where u.id = gu.userId
        AND gu.groupId= :groupId'
        )->setParameter('groupId', (int)$groupId);

        // returns an array of User objects
        return $query->execute();
    }

    public function findUsersToAddByGroupId($groupId): ?array
    {
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery(
            'SELECT u1.id as userId, u1.name as name FROM App\Entity\User u1 WHERE u1.id NOT IN 
        (SELECT u.id 
        FROM App\Entity\User u, App\Entity\GroupsUsers gu where u.id = gu.userId
        AND gu.groupId= :groupId)'
        )->setParameter('groupId', (int)$groupId);

        // returns an array of User objects
        return $query->execute();
    }


    public function DeleteByUserId($userId)
    {
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery(
            'DELETE FROM App\Entity\GroupsUsers gu where gu.userId = :userId'
        )->setParameter('userId', (int)$userId);

        return $query->execute();
    }

    /*
    public function findOneBySomeField($value): ?GroupsUsers
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
