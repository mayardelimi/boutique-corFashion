<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use Mailjet\Resources;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use \Mailjet\Client;

final class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(CategoryRepository $categoryRepository): Response
    {
        $categories= $categoryRepository->findAll();

        return $this->render('home/index.html.twig' , [
            'categories' => $categories ,
        ]);
    }



}
