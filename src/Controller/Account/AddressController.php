<?php

namespace App\Controller\Account;
use App\Class\Cart;
use App\Entity\Address;
use App\Form\AddressUserType;
use App\Repository\AddressRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use function App\Controller\addFlash;

class AddressController extends AbstractController{


    public function __construct(private EntityManagerInterface $entityManager){
        $this->entityManager = $entityManager;
    }
    #[Route('/compte/address', name: 'app_account_addresses')]
    public function index(): Response
    {
        return $this->render('account/address/addresses.html.twig'
        );
    }
    #[Route('/compte/address/ajouter/{id}', name: 'app_account_address_form' , defaults: ['id'=> null])]
    public function addressForm(Request $request , $id  , AddressRepository $addressRepository , Cart $cart): Response
    {
        if ($id){
            $address = $addressRepository->findOneById($id);
            if(!$address  OR $address->getUser() !== $this->getUser()){
                return $this->redirectToRoute('app_account_addresses');
            }
        }else{
            $address = new Address();
            $address->setUser($this->getUser());

        }


        $form= $this->createForm(AddressUserType::class, $address );

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($address);
            $this->entityManager->flush();
            $this->addFlash(
                'success', 'address added successfully'
            );

            if($cart->fullQuantity() > 0){
                return $this->redirectToRoute('app_account_addresses');

            }
            return $this->redirectToRoute('app_account_addresses');
        }
        return $this->render('account/address/addressForm.html.twig', [
                "addressForm" => $form
            ]
        );
    }


    #[Route('/compte/address/delete/{id}', name: 'app_account_address_delete')]
    public function delete($id , AddressRepository $addressRepository): Response
    {
        $address = $addressRepository->findOneById($id);
        if(!$address  OR $address->getUser() != $this->getUser()){
            return $this->redirectToRoute('app_account_addresses');
        } else{
            $this->entityManager->remove($address);
            $this->entityManager->flush();
            $this->addFlash(
                'success', 'address supprimer'
            );

        }
        return $this->render('account/address/addresses.html.twig');
    }
}

?>
