<?php

namespace App\Controller\Account;

use App\Entity\Product;
use App\Repository\OrderRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use function App\Controller\addFlash;

final class HomeController extends AbstractController
{

    #[Route('/compte', name: 'app_account')]
    public function index(OrderRepository $orderRepository): Response
    {
        $orders = $orderRepository->findBy([
            'user' => $this->getUser()
        ]);


        return $this->render('account/index.html.twig' , [
            'orders' => $orders,
            ]
        );
    }
    #[Route('/compte/wishlist', name: 'app_wishlist')]
    public function get(): Response
    {
        /** @var \App\Entity\User $user */
        $user = $this->getUser();

        $saved = $user->getSaved();

        return $this->render('account/wishlist.html.twig', [
            'saved' => $saved,
        ]);
    }
    #[Route('/compte/wishlist/add/{id}', name: 'app_wishlist_add')]
    public function toggleWishlist(Product $product, EntityManagerInterface $em, Request $request): Response
    {
        /** @var \App\Entity\User $user */
        $user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        if ($user->getSaved()->contains($product)) {
            $user->removeSaved($product);
        } else {
            $user->addSaved($product);
        }

        $em->flush();

        return $this->redirect($request->headers->get('referer'));
    }
}
