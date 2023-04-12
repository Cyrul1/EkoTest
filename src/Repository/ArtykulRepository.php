<?php

namespace App\Repository;

use App\Entity\Artykul;
use App\Entity\Magazyny;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Artykul>
 *
 * @method Artykul|null find($id, $lockMode = null, $lockVersion = null)
 * @method Artykul|null findOneBy(array $criteria, array $orderBy = null)
 * @method Artykul[]    findAll()
 * @method Artykul[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArtykulRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Artykul::class);
    }

    public function save(Artykul $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Artykul $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findArtykulyByMagazyn($magazynId)
    {
        return $this->createQueryBuilder('a')
            ->join('a.Magazyny', 'm')
            ->andWhere('m.id = :magazynId')
            ->setParameter('magazynId', $magazynId)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findByMagazyn(Magazyny $magazyn)
    {
        return $this->findBy(['Magazyn' => $magazyn]);
    }

//    /**
//     * @return Artykul[] Returns an array of Artykul objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Artykul
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
