<?php

namespace App\Repository;

use App\Entity\Fonc;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Fonc>
 *
 * @method Fonc|null find($id, $lockMode = null, $lockVersion = null)
 * @method Fonc|null findOneBy(array $criteria, array $orderBy = null)
 * @method Fonc[]    findAll()
 * @method Fonc[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FoncRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Fonc::class);
    }

    public function rech($mots){
        $query = $this->createQueryBuilder('f');
        $query->where('f.id != 0');
        if($mots != null){
            $query->andWhere('MATCH_AGAINST(f.NomFonc, f.Autre) AGAINST (:mots boolean)>0')
                ->setParameter('mots', $mots);
        }
        
        return $query->getQuery()->getResult();
    }

    public function add(Fonc $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Fonc $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return Fonc[] Returns an array of Fonc objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('f.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Fonc
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
