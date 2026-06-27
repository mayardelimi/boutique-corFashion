<?php

namespace App\Controller;

use App\Repository\OrderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Dompdf\Dompdf;
use Dompdf\Options;
use Stripe\Checkout\Session;
use Stripe\Stripe;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class PaymentController extends AbstractController
{
    #[Route('/commande/payment/{id_order}', name: 'app_payment')]
    public function index($id_order , OrderRepository $orderRepository , EntityManagerInterface $entityManager): Response
    {
        $YOUR_DOMAIN = $_ENV['DOMAIN'];
        $order = $orderRepository->findOneById($id_order);
        $order = $orderRepository->findOneBy(['id'=>$id_order , 'user'=>$this->getUser()]);

        foreach ($order->getOrderDetails() as $product) {
            $productstripe[]= [

                    'price_data' =>[
                        'currency' => 'eur',
                        'unit_amount' => (int) (number_format($product->getProductPriceWt(), 2, '.', '') * 100),
                        'product_data' => [
                            'name' => $product->getProductName(),
                            'images' => [
                                $YOUR_DOMAIN.'/uploads/'.$product->getProductIllustration()
                            ]
                        ]
                    ],
                    'quantity' => $product->getProductQuantity(),

            ];

        }
        Stripe::setApiKey($_ENV['SECRET_KEY']);

        $checkout_session = Session::create([
            "customer_email" => $order->getUser()->getEmail(),
            'line_items' => $productstripe,
            'mode' => 'payment',
            'cancel_url' => $YOUR_DOMAIN . '/mon-panier/annulation',
            "success_url"=>$YOUR_DOMAIN . '/commande/merci/{CHECKOUT_SESSION_ID}',
        ]);

        header('Location: ' . $checkout_session->url);
        $order->setStripeSessionId($checkout_session->id);
        $entityManager->flush();


            return $this->redirect($checkout_session->url);
    }



    #[Route('/commande/merci/{stripe_session_id}', name: 'app_payment_merci')]
    public function success($stripe_session_id, OrderRepository $orderRepository, EntityManagerInterface $entityManager): Response
    {
        $order = $orderRepository->findOneBy([
            'stripe_session_id' => $stripe_session_id,
            'user' => $this->getUser()
        ]);

        if (!$order) {
            return $this->redirectToRoute('app_home');
        }

        if ($order->getState() == 1) {
            $order->setState(2);
            $entityManager->flush();
        }

        return $this->render('payment/success.html.twig', [
            'order' => $order,
        ]);
    }

    #[Route('/commande/facture/{stripe_session_id}/telecharger', name: 'app_invoice_download')]
    public function downloadInvoice($stripe_session_id, OrderRepository $orderRepository): Response
    {
        $order = $orderRepository->findOneBy([
            'stripe_session_id' => $stripe_session_id,
            'user' => $this->getUser()
        ]);

        // Secure the invoice route so users can't random-guess other people's invoices
        if (!$order) {
            throw $this->createNotFoundException('Facture introuvable.');
        }

        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');
        $pdfOptions->set('isRemoteEnabled', true);

        $dompdf = new Dompdf($pdfOptions);

        // Pass your real database $order object to your Twig layout template
        $html = $this->renderView('pdf/invoice.html.twig', [
            'order' => $order,
        ]);

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        return new Response(
            $dompdf->output(),
            Response::HTTP_OK,
            [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="facture_' . $order->getId() . '.pdf"'
            ]
        );
    }


}
