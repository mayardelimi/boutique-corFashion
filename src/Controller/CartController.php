<?php

namespace App\Controller;

use App\Class\Cart;
use App\Entity\ProductVariant;
use App\Repository\ProductRepository;
use App\Repository\ProductVariantRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

final class CartController extends AbstractController
{


    #[Route('/mon-panier/add', name: 'app_cart_add', methods: ['POST'])]
    public function add(Cart $cart, ProductVariantRepository $variantRepository, Request $request): Response
    {
        $variantId = $request->request->get('variant_id');
        // Retrieve the quantity from the form and cast it safely to an integer
        $quantity = (int) $request->request->get('quantity', 1);

        if (!$variantId) {
            $this->addFlash('danger', 'Veuillez sélectionner une option valide.');
            return $this->redirectToRoute('app_cart');
        }

        $variant = $variantRepository->find($variantId);

        if (!$variant) {
            throw $this->createNotFoundException("Cette option de produit n'existe pas.");
        }

        // Pass both the variant object and the requested quantity here
        $cart->add($variant, $quantity);

        $this->addFlash(
            'success',
            'Produit ajouté à votre panier !'
        );

        $referer = $request->headers->get('referer');
        return $this->redirect($referer ?? $this->generateUrl('app_cart'));
    }
    #[Route('/mon-panier/{motif}', name: 'app_cart', defaults: ['motif' => null], methods: ['GET'])]
    public function index(Cart $cart, $motif): Response
    {
        return $this->render('cart/index.html.twig', [
            'cart' => $cart->getCart(),
            'total' => $cart->getTotal()
        ]);
    }
    #[Route('/mon-panier/edit/{id}', name: 'app_cart_edit')]
    public function edit($id, Cart $cart, ProductVariantRepository $variantRepository, Request $request): Response
    {
        $variant = $variantRepository->find($id);
        $cart->decrese($variant);
        $referer = $request->headers->get('referer');
        return $this->redirect($referer ?? $this->generateUrl('app_cart'));
    }
    #[Route('/mon-panier/increase/{id}', name: 'app_cart_increase')]
    public function increase($id, Cart $cart, ProductVariantRepository $variantRepository, Request $request): Response
    {
        $variant = $variantRepository->find($id);
        if (!$variant) {
            throw $this->createNotFoundException();
        }
        $cart->add($variant);
        $referer = $request->headers->get('referer');
        return $this->redirect($referer ?? $this->generateUrl('app_cart'));
    }
    #[Route('/mon-panier/remove/{id}', name: 'app_cart_remove')]
    public function remove($id, Cart $cart, ProductVariantRepository $variantRepository, Request $request): Response
    {
        $variant = $variantRepository->find($id);
        $cart->remove($variant);
        $referer = $request->headers->get('referer');
        return $this->redirect($referer ?? $this->generateUrl('app_cart'));
    }
}
