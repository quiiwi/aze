<?php

namespace App\Repository;

use App\Entity\Groupe;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Groupe|null find($id, $lockMode = null, $lockVersion = null)
 * @method Groupe|null findOneBy(array $criteria, array $orderBy = null)
 * @method Groupe[]    findAll()
 * @method Groupe[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GroupeRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Groupe::class);
    }

    public function search($request, $export = false) {

        $builder = $this->createQueryBuilder('g');

        if($request->query->get('groupe') !== null) {

            $groupe = $request->query->get('groupe');
            $builder->where('true = true');

            if(isset($groupe['name']) and $groupe['name'] !== null and $groupe['name'] !== '') {
                $builder
                    ->andWhere('g.name LIKE :name')
                    ->setParameter('name', '%' . $groupe['name'] . '%')
                ;
            }

            if(isset($groupe['email']) and $groupe['email'] !== null and $groupe['email'] !== '') {
                $builder
                    ->andWhere('g.email LIKE :email')
                    ->setParameter('email', '%' . $groupe['email'] . '%')
                ;
            }

            if(isset($groupe['phone']) and $groupe['phone'] !== null and $groupe['phone'] !== '') {
                $builder
                    ->andWhere('g.phone LIKE :phone')
                    ->setParameter('phone', '%' . $groupe['phone'] . '%')
                ;
            }
            
            if(isset($groupe['city']) and $groupe['city'] !== null and $groupe['city'] !== '') {
                $builder
                    ->andWhere('g.city LIKE :city')
                    ->setParameter('city', '%' . $groupe['city'] . '%')
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
