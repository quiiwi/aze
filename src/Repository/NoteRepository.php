<?php

namespace App\Repository;

use App\Entity\Note;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Note|null find($id, $lockMode = null, $lockVersion = null)
 * @method Note|null findOneBy(array $criteria, array $orderBy = null)
 * @method Note[]    findAll()
 * @method Note[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NoteRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Note::class);
    }

    public function search($request, $export = false) {

        $builder = $this->createQueryBuilder('n');

        if($request->query->get('note') !== null) {

            $note = $request->query->get('note');
            $builder->where('true = true');

            if(isset($note['name']) and $note['name'] !== null and $note['name'] !== '') {
                $builder
                    ->andWhere('n.name LIKE :name')
                    ->setParameter('name', '%' . $note['name'] . '%')
                ;
            }

            if(isset($note['place']) and $note['place'] !== null and $note['place'] !== '') {
                $builder
                    ->andWhere('n.place LIKE :place')
                    ->setParameter('place', '%' . $note['place'] . '%')
                ;
            }

            if(isset($note['notation']) and $note['notation'] !== null and $note['notation'] !== '') {
                $builder
                    ->andWhere('n.notation = :notation')
                    ->setParameter('notation', $note['notation'])
                ;
            }

            if(isset($note['commentary']) and $note['commentary'] !== null and $note['commentary'] !== '') {
                $builder
                    ->andWhere('n.commentary LIKE :commentary')
                    ->setParameter('commentary', '%' . $note['commentary'] . '%')
                ;
            }

            if(isset($note['isVisible']) and $note['isVisible'] !== null and $note['isVisible'] !== '') {
                $builder
                    ->andWhere('n.isVisible = :isVisible')
                    ->setParameter('isVisible', $note['isVisible'])
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
