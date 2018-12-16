<?php

namespace App\Repository;

use App\Entity\Gir;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Gir|null find($id, $lockMode = null, $lockVersion = null)
 * @method Gir|null findOneBy(array $criteria, array $orderBy = null)
 * @method Gir[]    findAll()
 * @method Gir[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GirRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Gir::class);
    }

    public function search($request, $export = false) {

        $builder = $this->createQueryBuilder('g');

        if($request->query->get('gir') !== null) {

            $gir = $request->query->get('gir');
            $builder->where('true = true');

            if(isset($gir['one']) and $gir['one'] !== null and $gir['one'] !== '') {
                $builder
                    ->andWhere('g.one = :one')
                    ->setParameter('one', $gir['one'])
                ;
            }

            if(isset($gir['two']) and $gir['two'] !== null and $gir['two'] !== '') {
                $builder
                    ->andWhere('g.two = :two')
                    ->setParameter('two', $gir['two'])
                ;
            }

            if(isset($gir['three']) and $gir['three'] !== null and $gir['three'] !== '') {
                $builder
                    ->andWhere('g.three = :three')
                    ->setParameter('three', $gir['three'])
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
