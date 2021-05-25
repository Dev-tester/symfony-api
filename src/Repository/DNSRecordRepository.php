<?php

namespace App\Repository;

use App\Entity\DNSRecord;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method DNSRecord|null find($id, $lockMode = null, $lockVersion = null)
 * @method DNSRecord|null findOneBy(array $criteria, array $orderBy = null)
 * @method DNSRecord[]    findAll()
 * @method DNSRecord[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DNSRecordRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DNSRecord::class);
    }

    public function getConnection(){
    	return $this->getEntityManager()->getConnection();
    }

    // /**
    //  * @return DNSRecord[] Returns an array of DNSRecord objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?DNSRecord
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
