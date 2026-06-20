<?php

namespace App\Twig ;
use App\Repository\CategoryRepository;
use Twig\Extension\AbstractExtension;
use Twig\Extension\GlobalsInterface;
use Twig\TwigFilter;
use App\Class\Cart;
class AppExtensions extends AbstractExtension implements GlobalsInterface{

    private $categoryRepository;


    public function __construct(CategoryRepository $categoryRepository , Cart $cart){
        $this->categoryRepository = $categoryRepository;
        $this->cart = $cart;
    }
    public function getFilters(){
        return [
            new TwigFilter('price', [$this, 'formatPrice']),
        ];
    }
    public function formatPrice($number): string
    {
        return number_format($number, 2, ',', ' ') . ' €';
    }

    public function getGlobals() : array {
        return [
            'allCategories' => $this->categoryRepository->findAll(),
            'fullcartqte' => $this->cart->getCartQte()
        ];
    }
}


