<?php

namespace App\Controller;

use App\Entity\Reclamation;
use App\Form\ReclamationType;
use App\Repository\ReclamationRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Routing\Annotation\Route;

class ReclamationController extends AbstractController
{
    /**
     * @Route("/reclamation", name="reclamation")
     */
    public function index(): Response
    {
        return $this->render('baseLR.html.twig', [
            'controller_name' => 'ReclamationController',
        ]);
    }

    /**
     * @param ReclamationRepository $repo
     * @return Response
     *@Route("reclamation/AfficheAdmin",name="AfficheAdmin")
     */

    function affichead(ReclamationRepository $repo){
        $reclamation=$repo->findAll();
        return $this->render("reclamation/AfficherAdmin.html.twig",
            ['reclamation'=>$reclamation]);
    }

    /**
     * @param ReclamationRepository $repo
     * @return Response
     * @Route("reclamation/AfficheAdminDQL")
     */
    function OrderByMotifDQL(ReclamationRepository $repo){
        $reclamation=$repo->OrderByMotif();
        return $this->render("reclamation/AfficherAdmin.html.twig",
            ['reclamation'=>$reclamation]);
    }

    /**
     * @param ReclamationRepository $repository
     * @return Response
     * @Route("reclamation/AfficheUser",name="AfficheU")
     */

    function affiche(ReclamationRepository $repository){
        $rec=$repository->findAll();
        return $this->render("reclamation/AfficherUser.html.twig",
            ['rec'=>$rec]);
    }

    /**
     * @param $id
     * @param ReclamationRepository $repo
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Route("/Supp/{id}",name="Supprimer")
     */
    function Delete($id,ReclamationRepository $repo){
        $reclamation=$repo->find($id);
        $em=$this->getDoctrine()->getManager();
        $em->remove($reclamation);//delete
        $em->flush();//mise à jour BD
        return $this->redirectToRoute('AfficheAdmin');
    }
    /**
     * @param $id
     * @param ReclamationRepository $repo
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Route("/SuppUser/{id}",name="Supprimeru")
     */
    function Delete1($id,ReclamationRepository $repo){
        $reclamation=$repo->find($id);
        $em=$this->getDoctrine()->getManager();
        $em->remove($reclamation);//delete
        $em->flush();//mise à jour BD
        return $this->redirectToRoute('AfficheU');
    }

    /**
     * @IsGranted("ROLE_USER")
     * @param Request $req
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @Route("/user/AjouterReclamation",name="AjouterU")
     */
    function Ajouter(Request $req){
        $rec=new Reclamation();
        $form=$this->createForm(ReclamationType::class,$rec);
       // $form->add("Ajouter Reclamation",SubmitType::class);
        $form->handleRequest($req);
        if($form->isSubmitted() && $form->isValid()){
            $em=$this->getDoctrine()->getManager();
            $em->persist($rec);
            $em->flush();
            $this->addFlash('message','Votre réclamation a bien été envoyé');
            return $this->redirectToRoute('AfficheU');
        }
        return $this->render("reclamation/Ajouter.html.twig",
        ['form'=>$form->createView()]);
    }




    /**
     * @param ReclamationRepository $repo
     * @param $id
     * @param Request $req
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     *@Route("reclamation/Modifier/{id}",name="ModifierRec")
     */
    function Update(ReclamationRepository $repo,$id,Request $req){
        $reclamation=$repo->find($id);
        $form=$this->createForm(ReclamationType::class,$reclamation);
        $form->add('Modifier',SubmitType::class);
        $form->handleRequest($req);
        if($form->isSubmitted() && $form->isValid()){
            $em=$this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute('AfficheU');
        }
        return $this->render("reclamation/Update.html.twig",
            ['form'=>$form->createView()]);


    }}
