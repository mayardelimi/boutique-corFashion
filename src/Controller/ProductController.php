<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ProductController extends AbstractController
{

    #[Route('/product/{slug}', name: 'app_product')]
    public function index(string $slug, ProductRepository $productRepository): Response
    {
        $product = $productRepository->findOneBySlug($slug);
        $wishlistIds = [];

        if (!$product) {
            throw $this->createNotFoundException('Ce produit n\'existe pas.');
        }

        if ($this->getUser()) {
            // ✏️ Changed variable from $product to $savedProduct
            foreach ($this->getUser()->getSaved() as $savedProduct) {
                $wishlistIds[] = $savedProduct->getId();
            }
        }

        return $this->render('product/index.html.twig', [
            'product' => $product, // Now this will remain the correct original entity!
            'wishlistIds' => $wishlistIds,
        ]);
    }
}
