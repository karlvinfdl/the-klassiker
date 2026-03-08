<?php

namespace App\Repository;

use App\Entity\GalleryPhoto;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<GalleryPhoto>
 *
 * @method GalleryPhoto|null find($id, $lockMode = null, $lockVersion = null)
 * @method GalleryPhoto|null findOneBy(array $criteria, array $orderBy = null)
 * @method GalleryPhoto[]    findAll()
 * @method GalleryPhoto[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GalleryPhotoRepository extends ServiceEntityRepository
{
  public function __construct(ManagerRegistry $registry)
  {
    parent::__construct($registry, GalleryPhoto::class);
  }

  public function save(GalleryPhoto $entity, bool $flush = false): void
  {
    $this->getEntityManager()->persist($entity);

    if ($flush) {
      $this->getEntityManager()->flush();
    }
  }

  public function remove(GalleryPhoto $entity, bool $flush = false): void
  {
    $this->getEntityManager()->remove($entity);

    if ($flush) {
      $this->getEntityManager()->flush();
    }
  }
}

