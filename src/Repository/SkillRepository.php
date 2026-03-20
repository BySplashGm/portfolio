<?php

namespace App\Repository;

use App\Entity\Skill;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Skill>
 */
class SkillRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Skill::class);
    }

    /**
     * @return Skill[] with type eagerly loaded
     */
    public function findAllWithType(): array
    {
        return $this->createQueryBuilder('s')
            ->leftJoin('s.type', 't')
            ->addSelect('t')
            ->orderBy('t.label', 'ASC')
            ->addOrderBy('s.name', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findWithProjectsAndExperiences(int $id): ?object
    {
        return $this->createQueryBuilder('s')
            ->leftJoin('s.projects', 'p')
            ->addSelect('p')
            ->leftJoin('s.experiences', 'e')
            ->addSelect('e')
            ->where('s.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
