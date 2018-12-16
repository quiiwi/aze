<?php

namespace App\Repository;

use App\Entity\Candidacy;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Candidacy|null find($id, $lockMode = null, $lockVersion = null)
 * @method Candidacy|null findOneBy(array $criteria, array $orderBy = null)
 * @method Candidacy[]    findAll()
 * @method Candidacy[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CandidacyRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Candidacy::class);
    }

    public function search($request, $export = false) {

        $builder = $this->createQueryBuilder('c');

        if($request->query->get('candidacy') !== null) {

            $candidacy = $request->query->get('candidacy');
            $builder->where('true = true')
                    ->leftJoin('c.user', 'u')
                    ->leftJoin('c.establishment', 'e');

            if(isset($candidacy['firstname']) and $candidacy['firstname'] !== null and $candidacy['firstname'] !== '') {
                $builder
                    ->andWhere('u.firstname LIKE :firstname')
                    ->setParameter('firstname', '%' . $candidacy['firstname'] . '%')
                ;
            }

            if(isset($candidacy['lastname']) and $candidacy['lastname'] !== null and $candidacy['lastname'] !== '') {
                $builder
                    ->andWhere('u.lastname LIKE :lastname')
                    ->setParameter('lastname', '%' . $candidacy['lastname'] . '%')
                ;
            }

            if(isset($candidacy['email']) and $candidacy['email'] !== null and $candidacy['email'] !== '') {
                $builder
                    ->andWhere('u.email LIKE :email')
                    ->setParameter('email', '%' . $candidacy['email'] . '%')
                ;
            }

            if(isset($candidacy['status']) and $candidacy['status'] !== null and $candidacy['status'] !== '') {
                $builder
                    ->andWhere('c.status = :status')
                    ->setParameter('status', $candidacy['status'])
                ;
            }

            if(isset($candidacy['establishment']) and $candidacy['establishment'] !== null and $candidacy['establishment'] !== '') {
                $builder
                    ->andWhere('e.name LIKE :name')
                    ->setParameter('name', '%' . $candidacy['establishment'] . '%')
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
