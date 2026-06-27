<?php
namespace App\Class ;
use Symfony\Component\HttpFoundation\RequestStack;
class Cart{

    public function  __construct(private RequestStack $requestStack){


}
    public function add($product){

        $cart = $this->requestStack->getSession()->get('cart');
        if (isset($cart[$product->getId()])){
            $cart[$product->getId()] = [
                'object' => $product,
                'qty' => $cart[$product->getId()]['qty'] + 1
            ];
        }else{
            $cart[$product->getId()] = [
                'object' => $product,
                'qty' => 1
            ];
        }
        $this->requestStack->getSession()->set("cart",$cart);
    }

    public function decrese($product){

        $cart = $this->requestStack->getSession()->get('cart');

            $cart[$product->getId()] = [
                'object' => $product,
                'qty' => $cart[$product->getId()]['qty'] - 1
            ];
            if ($cart[$product->getId()]['qty']==0){
                unset($cart[$product->getId()]);
            }

        $this->requestStack->getSession()->set("cart",$cart);
    }
    public function remove($product){
        $session = $this->requestStack->getSession();
         $cart = $session->get("cart");
         unset($cart[$product->getId()]);
         $session->set("cart",$cart);
    }


    public function getCart(){
        return $this->requestStack->getSession()->get("cart");

    }

    public function getCartQte(){
         $cart =$this->requestStack->getSession()->get("cart");
         $s=0;
         if(!isset($cart)){
              return $s;
         }
         foreach ($cart as $product){
             $s+= $product['qty'];
         }
         return $s;

    }
    public function getTotal(){
        $cart =$this->requestStack->getSession()->get("cart");
        $s=0;
        if (!isset($cart)){
            return $s;
        }
        foreach ($cart as $product){
            $s+= $product['qty'] * $product['object']->getPriceWt();
        }
        return $s;
    }


}
