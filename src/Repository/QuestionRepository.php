<?php

namespace App\Repository;

use App\Entity\Question;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Question>
 *
 * @method Question|null find($id, $lockMode = null, $lockVersion = null)
 * @method Question|null findOneBy(array $criteria, array $orderBy = null)
 * @method Question[]    findAll()
 * @method Question[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class QuestionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Question::class);
    }

    public function search($mots)
    {
        $query = $this->createQueryBuilder('r');
        $query->where('r.id != 0');
        if ($mots != null) {
            $query->andWhere('MATCH_AGAINST(r.FAQ, r.Solution, r.Question, r.Reponse) AGAINST (:mots boolean)>0')
                ->setParameter('mots', $mots);
        }

        return $query->getQuery()->getResult();
    }

    //Groupe des question Ajouter par Jours
    public function countByDate(){
        $query = $this->createQueryBuilder('a')
        ->select('SUBSTRING(a.DateQuest, 1,10) as date_PJ, COUNT(a) as count')->groupBy('date_PJ');
        return $query->getQuery()->getResult();
    }

    //Groupe des question Terminé
    public function countByFinished(){
        $query = $this->createQueryBuilder('f')
        ->select('SUBSTRING(f.updateAt, 1,10) as date_UJ, COUNT(f) as count')->where('f.Finished != 0')->groupBy('date_UJ');
        return $query->getQuery()->getResult();
    }

    public function add(Question $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Question $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    //    /**
    //     * @return Question[] Returns an array of Question objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('q')
    //            ->andWhere('q.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('q.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Question
    //    {
    //        return $this->createQueryBuilder('q')
    //            ->andWhere('q.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
