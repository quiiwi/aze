<?php

namespace App\Repository;

use App\Entity\Content;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Content|null find($id, $lockMode = null, $lockVersion = null)
 * @method Content|null findOneBy(array $criteria, array $orderBy = null)
 * @method Content[]    findAll()
 * @method Content[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ContentRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Content::class);
    }

    public function search($request) {

        $builder = $this->createQueryBuilder('c');

        if($request->query->get('content') !== null) {

            $content = $request->query->get('content');
            $builder->where('true = true');

            if(isset($content['name']) and $content['name'] !== null and $content['name'] !== '') {
                $builder
                    ->andWhere('c.name LIKE :name')
                    ->setParameter('name', '%' . $content['name'] . '%')
                ;
            }
        }

        return $builder->getQuery()->getResult();
    }
}
