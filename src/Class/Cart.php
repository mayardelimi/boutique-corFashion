<?php
namespace App\Class;

use App\Repository\ProductVariantRepository;
use Symfony\Component\HttpFoundation\RequestStack;

class Cart
{
    public function __construct(
        private RequestStack $requestStack,
        private ProductVariantRepository $variantRepository
    ) {}

// src/Class/Cart.php

    public function add($variant, int $qty = 1): void
    {
        $cart = $this->requestStack->getSession()->get('cart', []);
        $id = $variant->getId();

        if ($qty < 1) {
            $qty = 1;
        }

        if (isset($cart[$id])) {
            $cart[$id]['qty'] += $qty;
        } else {
            $cart[$id] = ['qty' => $qty];
        }

        $this->requestStack->getSession()->set('cart', $cart);
    }

    public function decrese($variant): void
    {
        $cart = $this->requestStack->getSession()->get('cart', []);
        $id = $variant->getId();

        if (!isset($cart[$id])) {
            return;
        }

        $cart[$id]['qty'] -= 1;

        if ($cart[$id]['qty'] <= 0) {
            unset($cart[$id]);
        }
        $this->requestStack->getSession()->set('cart', $cart);
    }

    public function remove($variant): void
    {
        $cart = $this->requestStack->getSession()->get('cart', []);
        unset($cart[$variant->getId()]);
        $this->requestStack->getSession()->set('cart', $cart);
    }


    public function getCart(): array
    {
        $sessionCart = $this->requestStack->getSession()->get('cart', []);
        $result = [];

        foreach ($sessionCart as $id => $data) {
            $variant = $this->variantRepository->find($id);
            if (!$variant) {
                continue;
            }
            $result[$id] = [
                'object' => $variant,
                'qty' => $data['qty']
            ];
        }

        return $result;
    }

    public function getCartQte(): int
    {
        $cart = $this->requestStack->getSession()->get('cart', []);
        $s = 0;
        foreach ($cart as $item) {
            $s += $item['qty'];
        }
        return $s;
    }
    public function clear(): void
    {
        $this->requestStack->getSession()->remove('cart');
    }
    public function getTotal(): float
    {
        $cart = $this->getCart();
        $s = 0;

        foreach ($cart as $item) {
            $s += $item['qty'] * $item['object']->getProduct()->getPriceWt();
        }
        return $s;
    }
}
