<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Finder\Finder;

/**
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository {

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

  /**
   * @throws \Doctrine\ORM\NonUniqueResultException
   */
  public function syncData(): array {
    $data = $this->getData();
    $createCount = 0;
    $updateCount = 0;

    if (isset($data['error']) && $data['error']){
      // if there is no file ends process
      return $data;
    }

    foreach ($data as $item){

      $product = $this->findOneByAsinCode($item->productASIN);

      if ($product) {
//          $product->setName($item->productName);
//          $product->setDescription($item->productDescription);
//          $product->setAsin($item->productASIN);
//          $product->setCategory(implode(",",$item->productCategories));
//          $product->setPrice($item->productPrice);

        $updateCount++;
      } else {
//          $product = New Product();
//
//          $product->setName($item->productName);
//          $product->setDescription($item->productDescription);
//          $product->setAsin($item->productASIN);
//          $product->setCategory(implode(",",$item->productCategories));
//          $product->setPrice($item->productPrice);
//
//          $this->flush();

        $createCount++;
      }

    }

    return [
      'error' => 0,
      'message' => [
        sprintf('Created "%d" new products.', $createCount),
        sprintf('Updated "%d" products', $updateCount),
      ],
    ];

  }

  public function getData(): array {
    $data = ['error' => 1, 'message' => 'File not found'];

    // use finder to get the specific sync file
    $finder = New Finder();
    $finder->files()->in('DataFiles')->name('syncData.json');

    if ($finder->hasResults()) {

      foreach ($finder as $file) {
        $data = json_decode($file->getContents());
      }

    }

    return $data;

  }

  // /**
  //  * @return Product[] Returns an array of Product objects
  //  */

  public function findByAsinCode($value)
  {
      return $this->createQueryBuilder('p')
          ->andWhere('p.asin = :val')
          ->setParameter('val', $value)
          ->orderBy('p.id', 'ASC')
          ->setMaxResults(10)
          ->getQuery()
          ->getResult()
      ;
  }

  /**
   * @throws \Doctrine\ORM\NonUniqueResultException
   */
  public function findOneByAsinCode($value): ?Product {
    return $this->createQueryBuilder('p')
        ->andWhere('p.asin = :val')
        ->setParameter('val', $value)
        ->getQuery()
        ->getOneOrNullResult()
    ;
  }

  private function create(Product $product) {
    $this->createQuery();
  }

}
