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


        if (!$product) {
            throw $this->createNotFoundException('Ce produit n\'existe pas.');
        }

        return $this->render('product/index.html.twig', [
            'product' => $product,
        ]);
    }
}
