<?php

namespace App\Repository\Authorization;

use App\Entity\Authorization\Users;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Query\Parameter;

/**
 * @method Users|null find($id, $lockMode = null, $lockVersion = null)
 * @method Users|null findOneBy(array $criteria, array $orderBy = null)
 * @method Users[]    findAll()
 * @method Users[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UsersRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Users::class);
    }

    public function listUsers(array $params)
    {
        try {
            $objQueryBuilder = $this->createQueryBuilder('user');
            $objArrayCollection = new ArrayCollection();
            
            if(array_key_exists('name', $params) && trim($params['name'])){
                $objLike = $objQueryBuilder->expr()->like('LOWER(user.name)', ':name');
                $objQueryBuilder->andWhere($objLike);
                $objArrayCollection->add(new Parameter('name', "%".mb_strtolower(trim($params['name']))."%"));
            }
            
            if(array_key_exists('isActive', $params) && ($params['isActive'] === true ||  $params['isActive'] === false)){
                $objEq = $objQueryBuilder->expr()->eq('user.isActive', ':isActive');
                $objQueryBuilder->andWhere($objEq);
                $objArrayCollection->add(new Parameter('isActive', $params['isActive']));
            }
            
            if(array_key_exists('username', $params) && trim($params['username'])){
                $objLike = $objQueryBuilder->expr()->like('user.username', ':username');
                $objQueryBuilder->andWhere($objLike);
                $objArrayCollection->add(new Parameter('username', "%".mb_strtolower(trim($params['username']))."%"));
            }
            
            if($objArrayCollection->count()){
                $objQueryBuilder->setParameters($objArrayCollection);
            }
            return $objQueryBuilder->getQuery()->execute();
            
        } catch (\Exception $e) {
            throw $e;
        }
//         return $this->createQueryBuilder('u')
//             ->andWhere('u.exampleField = :val')
//             ->setParameter('val', $value)
//             ->orderBy('u.id', 'ASC')
//             ->setMaxResults(10)
//             ->getQuery()
//             ->getResult()
//         ;
    }
    

    /*
    public function findOneBySomeField($value): ?Users
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
