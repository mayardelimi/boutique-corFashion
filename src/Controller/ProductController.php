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
        $product = $productRepository->findOneBy(['slug' => $slug]);

        if (!$product) {
            throw $this->createNotFoundException('Ce produit n\'existe pas.');
        }

        $availableVariants = [];
        foreach ($product->getVariants() as $variant) {
                $availableVariants[] = $variant;

        }

        $wishlistIds = [];
        $user = $this->getUser();
        if ($user && method_exists($user, 'getSaved')) {
            foreach ($user->getSaved() as $savedProduct) {
                $wishlistIds[] = $savedProduct->getId();
            }
        }

        return $this->render('product/index.html.twig', [
            'product' => $product,
            'variants' => $availableVariants,
            'images' => $product->getImages(),
            'wishlistIds' => $wishlistIds,
            'totalQTE' => $product->getTotalQTE(),
        ]);
    }
}
