<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function search($request, $export = false) {

        $builder = $this->createQueryBuilder('u');

        if($request->query->get('user_admin') !== null) {

            $user = $request->query->get('user_admin');
            $builder->where('true = true');

            if(isset($user['firstname']) and $user['firstname'] !== null and $user['firstname'] !== '') {
                $builder
                    ->andWhere('u.firstname LIKE :firstname')
                    ->setParameter('firstname', '%' . $user['firstname'] . '%')
                ;
            }

            if(isset($user['lastname']) and $user['lastname'] !== null and $user['lastname'] !== '') {
                $builder
                    ->andWhere('u.lastname LIKE :lastname')
                    ->setParameter('lastname', '%' . $user['lastname'] . '%')
                ;
            }

            if(isset($user['email']) and $user['email'] !== null and $user['email'] !== '') {
                $builder
                    ->andWhere('u.email LIKE :email')
                    ->setParameter('email', '%' . $user['email'] . '%')
                ;
            }

            if(isset($user['phone']) and $user['phone'] !== null and $user['phone'] !== '') {
                $builder
                    ->andWhere('u.phone LIKE :phone')
                    ->setParameter('phone', '%' . $user['phone'] . '%')
                ;
            }
        }

        $total = count($builder->getQuery()->getResult());

        $array = [
            'current' => 0,
            'total' => $total,
            'results' => $builder->getQuery()->getResult(),
            'max' => 0
        ];

        if(!$export) {

            $max = 10;
            $page = 0;

            if($request->query->get('page') !== null and $request->query->get('page') !== '') {
                $page = $request->query->get('page');
            }

            $builder
                ->setFirstResult($page * $max)
                ->setMaxResults($max)
            ;
    
            $page++;

            $current = $page * $max;

            if($current > $total) {
                $current = $total;
            }

            $array = [
                'current' => $current,
                'total' => $total,
                'results' => $builder->getQuery()->getResult(),
                'max' => $max
            ];

        }

        return $array;
    }
}
