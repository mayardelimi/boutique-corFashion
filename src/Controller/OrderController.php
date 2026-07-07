<?php
namespace App\Controller;

use App\Class\Cart;
use App\Entity\Order;
use App\Entity\OrderDetail;
use App\Entity\ProductVariant;
use App\Form\OrderType;
use App\Repository\ProductVariantRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class OrderController extends AbstractController
{
    #[Route('/commande/livraison', name: 'app_order')]
    public function index(): Response
    {
        $addresses = $this->getUser()->getAddresses();
        if (count($addresses) === 0) {
            return $this->redirectToRoute('app_account_address_form');
        }

        $form = $this->createForm(
            OrderType::class,
            null,
            ['addresses' => $addresses, 'action' => $this->generateUrl('app_order_summary')]
        );

        return $this->render('order/index.html.twig', [
            'deliveryForm' => $form->createView(),
        ]);
    }

    #[Route('/commande/recap', name: 'app_order_summary')]
    public function add(Request $request, Cart $cart, EntityManagerInterface $entityManager): Response
    {
        $products = $cart->getCart();

        if (empty($products)) {
            $this->addFlash('warning', 'Votre panier est vide.');
            return $this->redirectToRoute('app_cart');
        }

        $form = $this->createForm(
            OrderType::class,
            null,
            ['addresses' => $this->getUser()->getAddresses()]
        );
        $form->handleRequest($request);

        if (!$form->isSubmitted() || !$form->isValid()) {
            return $this->redirectToRoute('app_order');
        }

        $addressObj = $form->get('addresses')->getData();
        $address = $addressObj->getFirstname() . ' ' . $addressObj->getLastname() . '<br/>';
        $address .= $addressObj->getAddress() . '<br/>';
        $address .= $addressObj->getPostal() . '<br/>';
        $address .= $addressObj->getCountry() . '<br/>';
        $address .= $addressObj->getPhone();

        $order = new Order();
        $order->setUser($this->getUser());
        $order->setCreatedAt(new \DateTime());
        $order->setState(1);
        $order->setCarrierName($form->get('carriers')->getData()->getName());
        $order->setCarrierPrice($form->get('carriers')->getData()->getPrix());
        $order->setDelivery($address);

        foreach ($products as $item) {
            $variant = $item['object'];
            $product = $variant->getProduct();
            $variant->updateAQ( $item['qty']);
            $orderDetail = new OrderDetail();
            $orderDetail->setProductName($product->getName());
            $orderDetail->setProductIllustration(
                $product->getImages()->first() ? $product->getImages()->first()->getImagePath() : null
            );
            $orderDetail->setPrice($product->getPrice());
            $orderDetail->setProductTva($product->getTva());
            $orderDetail->setProductQuantity($item['qty']);
            $orderDetail->setProductId($variant);
            $order->addOrderDetail($orderDetail);

            $entityManager->persist($orderDetail);
        }

        $entityManager->persist($order);
        $entityManager->flush();


        return $this->render('order/summary.html.twig', [
            'choices' => $form->getData(),
            'cart' => $products,
            'totalWt' => $cart->getTotal(),
            'order' => $order,
        ]);
    }
}
