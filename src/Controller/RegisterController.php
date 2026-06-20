<?php

namespace App\Controller;

use App\Class\Mail;
use App\Entity\User;
use App\Form\RegisterUserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Routing\Attribute\Route;

final class RegisterController extends AbstractController
{
    #[Route('/inscription', name: 'app_register')]
    public function index(Request $request , EntityManagerInterface $entityManager): Response
    {

        $user = new User();

        $form = $this->createForm(RegisterUserType::class , $user);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($user);
            $this->addFlash(
                'success',
                'votre compte est cree'
            );
            $entityManager->flush();

            $mail = new Mail();
            $vars =[
                'firstname' => $user->getFirstname(),
            ];
            $mail->send($user->getEmail(), $user->getFirstname() ,'test de mailjet ' , $vars , 'welcome.html');

            return $this->redirectToRoute('app_login');
        }
        return $this->render('register/index.html.twig' ,[
            'registerForm'=> $form ]
        );
    }
}
