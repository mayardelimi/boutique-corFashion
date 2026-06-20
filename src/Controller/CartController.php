<?php

namespace App\Controller;

use App\Class\Cart;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

final class CartController extends AbstractController
{
    #[Route('/mon-panier/{motif}', name: 'app_cart' , defaults: ['motif'=> null])]
    public function index(Cart $cart , $motif ): Response
    {
        return $this->render('cart/index.html.twig' , [
            'cart' => $cart->getCart(),
            'total' => $cart->getTotal()
        ]);
    }

    #[Route('/mon-panier/add/{id}', name: 'app_cart_add')]
    public function add($id , Cart $cart , ProductRepository $productRepository , Request $request): Response
    {
          $product = $productRepository->findOneById($id);
          $cart->add($product);
          $this->addFlash(
              'success',
              "produit ajoute a votre panier"
          );
        $referer = $request->headers->get('referer');
        return $this->redirect($referer ?? $this->generateUrl('app_cart'));

    }

    #[Route('/mon-panier/edit/{id}', name: 'app_cart_edit')]
    public function edit($id , Cart $cart , ProductRepository $productRepository , Request $request): Response
    {
        $product = $productRepository->findOneById($id);
        $cart->decrese($product);

        $referer = $request->headers->get('referer');
        return $this->redirect($referer ?? $this->generateUrl('app_cart'));

    }
    #[Route('/mon-panier/remove/{id}', name: 'app_cart_remove')]
    public function remove($id , Cart $cart , ProductRepository $productRepository , Request $request): Response
    {
        $product = $productRepository->findOneById($id);
        $cart->remove($product);

        $referer = $request->headers->get('referer');
        return $this->redirect($referer ?? $this->generateUrl('app_cart'));

    }
}
