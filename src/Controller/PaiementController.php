<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Entity\Paiement;
use App\Form\PaiementType;
use App\Repository\PaiementRepository;
use App\Repository\ProduitRepository;
use Dompdf\Dompdf;
use Dompdf\Options;
use http\Client\Curl\User;
use MercurySeries\FlashyBundle\FlashyNotifier;
use phpDocumentor\Reflection\Utils;
use Stripe\Stripe;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class PaiementController extends AbstractController
{
    /**
     * @Route("/paiement", name="paiement")
     */
    public function index()
    {
        return $this->render('paiement/index.html.twig', [
            'controller_name' => 'PaiementController',
        ]);
    }


    /**
     * @Route("/paiement/affiche1",name="afficher1")
     * @param Request $request
     * @return Response
     */
    public function affiche1(Request $request)
    {
        $repo=$this->getDoctrine()->getRepository(Paiement::class)->findAll();
        if($request->isMethod("POST")){
            $prenom = $request->get('prenom');
            $repo = $this->getDoctrine()->getRepository(Paiement::class)->findBy(array('prenom'=>$prenom));
        }
        return $this->render('paiement/affichePaiementB.html.twig',[
            'repo'=>$repo]);
    }

    /**
     * @Route("/paiement/affichefront/{userid}",name="afficherPaiement")
     * @return Response
     */
    public function affiche($userid)
    {
        $repo=$this->getDoctrine()->getRepository(Paiement::class)->findBy(array('userid' => $userid));
        return $this->render('paiement/affiche.html.twig',[
            'repo'=>$repo]);
    }

    /**
     * @Route("/paiement/ajouter",name="AjouterPaiement")
     * @param Request $request
     * @param \Swift_Mailer $mailer
     * @return RedirectResponse|Response
     */
    function Ajout(Request $request, \Swift_Mailer $mailer,SessionInterface $session)
    {

        $paiement=new Paiement();
        $form=$this->createForm(PaiementType::class,$paiement);
        $form->add("Payer",SubmitType::class);
        $form->handleRequest($request);
        if($form->isSubmitted()&&$form->isValid()){
            $paiement->setStatus('Not paid');
            $paiement->setPrix($session->get('prix'));
            $paiement->setUserid($session->get('id_user'));
            $em=$this->getDoctrine()->getManager();
            $em->persist($paiement);
            $em->flush();
            $message = (new \Swift_Message('Commande'))
                        ->setFrom('docdocpidev@gmail.com','DocDoc')
                        ->setTo($paiement->getEmail())
                        ->setBody('Votre Commande a été validé avec success.
Merci pour votre confiance.');
            $mailer->send($message);
            if($paiement->getType() == "En ligne"){
                $session->set('paiement', $paiement->getId());
                return $this->redirectToRoute("paiement");
            }
            else
                $session->set('paiement', $paiement->getId());
                return $this->redirectToRoute("success", array(
                    'id'=> $paiement->getId(),
                ));
        }
        return $this->render("paiement/ajout.html.twig",['f'=>$form->createView()]);
    }

    /**
     * @Route("/paiement/delete/{id}",name="remove")
     * @param $id
     * @param PaiementRepository $repo
     * @return RedirectResponse
     */
    public function delete($id,PaiementRepository $repo)
    {
        $em = $this->getDoctrine()->getManager();
        $paiement = $repo->find($id);
        $em->remove($paiement);
        $em->flush();
        return $this->redirectToRoute('afficherPaiement');
    }

    /**
     * @Route ("/success/{id}", name="success")
     * @param PaiementRepository $repo
     * @return Response
     */
    public function success($id, PaiementRepository $repo,SessionInterface $session ,FlashyNotifier $flashy ){
        $em = $this->getDoctrine()->getManager();
        $paiement = $repo->find($id);
        $paiement->setStatus("Paid");
        $em->flush();
        $flashy->success('Paiement avec success!');
        return $this->render("paiement/success.html.twig");
    }

    /**
     * @Route("/facture", name="facture")
     */
    public function facture(SessionInterface $session, ProduitRepository $produitRepository){

        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');
        $dompdf = new Dompdf($pdfOptions);
        $i = 0;
        $panier = $session->get('panier', []);
        $panierwithData = [];
        foreach ($panier as $id => $quantity){
            $panierwithData [] = [
                'produit' => $produitRepository->find($id),
                'quantity' => $quantity
            ];
            $i++;
        }
        $session->set('number', $i);
        $total = 0;
        foreach ($panierwithData as $item){
            $totalitem = $item['produit']->getPrix() * $item['quantity'];
            $total +=$totalitem;
        }
        $session->set('total', $total);
        $html= $this->render('paiement/facture.html.twig', [
            'title' => "Welcome to our PDF Test",
            'items' => $panierwithData,
            'total' => $total
        ]);

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream("DocDoc.pdf", [
            "Attachment" => true
        ]);
        $session->set('total', 0);
        $session->set('panier', []);
        $session->set('number', 0);
        $session->set('panier2', []);
    }

    /**
     * @Route ("/error", name="error")
     */
    public function error( FlashyNotifier $flashy){
        $flashy->error('Désolé, veuillez réessayer !');
        return $this->render("paiement/error.html.twig");

    }


    /**
     * @Route("checkout-session", name="checkout")
     */
    public function checkout(SessionInterface $session){
        $prix = $session->get('total');
        $paiement = $session->get('paiement');
        Stripe::setApiKey('sk_test_51ITZdrDWcbi4gy3SbvHGW6U9amD46RvaIrukmts6jHDyoO1ALaODTNC347m2KquVwUwBXbKAqfK2bwHV2gi439ra00l0AmxP70');
        $session = \Stripe\Checkout\Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => 'Prix:',
                    ],
                    'unit_amount' => $prix*100,
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => $this->generateUrl("success",array('id'=> $paiement,), UrlGeneratorInterface::ABSOLUTE_URL),
            'cancel_url' => $this->generateUrl("error", [], UrlGeneratorInterface::ABSOLUTE_URL),
        ]);
        return new JsonResponse(['id' => $session->id]);
    }
}
