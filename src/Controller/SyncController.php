<?php
/**
 * @file
 * SyncController.php
 *
 * @author: Lenon Tolfo <lenon.tolfo@de-media.de>
 */

namespace App\Controller;

use App\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Finder\Finder;

class SyncController extends AbstractController {

  public function syncData(){
    $createCount = 0;
    $updateCount = 0;
    $data = $this->getData();

    if (isset($data['error']) && $data['error']){
      // if there is no file ends process
      return $data;
    }

    foreach ($data as $item){

      $product = $this->getDoctrine()->getRepository(Product::class)->findOneByAsinCode($item->productASIN);
      $entityManager = $this->getDoctrine()->getManager();

      if ($product) {
        //          $product->setName($item->productName);
        //          $product->setDescription($item->productDescription);
        //          $product->setAsin($item->productASIN);
        //          $product->setCategory(implode(",",$item->productCategories));
        //          $product->setPrice($item->productPrice);

        $updateCount++;
      } else {
        $product = New Product();

        $product->setName($item->productName);
        $product->setDescription($item->productDescription);
        $product->setAsin($item->productASIN);
        $product->setCategory(implode(",",$item->productCategories));
        $product->setPrice($item->productPrice);

        $entityManager->persist($product);
        $entityManager->flush();

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

  /**
   * @Route("/product", name="create_product")
   */
  public function createProduct(ValidatorInterface $validator): Response
  {
    $product = new Product();
    // This will trigger an error: the column isn't nullable in the database
    $product->setName(null);
    // This will trigger a type mismatch error: an integer is expected
    $product->setPrice('1999');

    // ...

    $errors = $validator->validate($product);
    if (count($errors) > 0) {
      return new Response((string) $errors, 400);
    }

    // ...
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
}
