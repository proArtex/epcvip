<?php declare(strict_types=1);

namespace App\Repository;

use App\Entity\Product;
use DateTimeInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    /**
     * @return Product[]
     */
    public function countPendingOlderThan(DateTimeInterface $dateTime): int
    {
        return (int) $this
            ->createQueryBuilder('p')
            ->select('count(p.issn)')
            ->where('p.status = :status')
            ->andWhere('p.updatedAt <= :datetime')
            ->setParameter('status', 'pending')
            ->setParameter('datetime', $dateTime)
            ->getQuery()
            ->getSingleScalarResult();
    }
}
