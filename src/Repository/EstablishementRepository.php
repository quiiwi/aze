<?php

namespace App\Repository;

use App\Entity\Establishement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Establishement|null find($id, $lockMode = null, $lockVersion = null)
 * @method Establishement|null findOneBy(array $criteria, array $orderBy = null)
 * @method Establishement[]    findAll()
 * @method Establishement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EstablishementRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Establishement::class);
    }

    public function search($request, $export = false) {

        $builder = $this->createQueryBuilder('e');

        if($request->query->get('establishement') !== null) {

            $establishement = $request->query->get('establishement');
            $builder->where('true = true');

            if(isset($establishement['name']) and $establishement['name'] !== null and $establishement['name'] !== '') {
                $builder
                    ->andWhere('e.name LIKE :name')
                    ->setParameter('name', '%' . $establishement['name'] . '%')
                ;
            }

            if(isset($establishement['city']) and $establishement['city'] !== null and $establishement['city'] !== '') {
                $builder
                    ->andWhere('e.city LIKE :city')
                    ->setParameter('city', '%' . $establishement['city'] . '%')
                ;
            }

            if(isset($establishement['type']) and $establishement['type'] !== null and $establishement['type'] !== '') {
                $builder
                    ->andWhere('e.type = :type')
                    ->setParameter('type', $establishement['type'])
                ;
            }

            if(isset($establishement['status']) and $establishement['status'] !== null and $establishement['status'] !== '') {
                $builder
                    ->andWhere('e.status = :status')
                    ->setParameter('status', $establishement['status'])
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
