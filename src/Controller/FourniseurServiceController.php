<?php

namespace App\Controller;

use App\Entity\FourniseurService;
use App\Form\FournisseurType;
use App\Repository\FourniseurServiceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FourniseurServiceController extends AbstractController
{
    /**
     * @Route("/fourniseur/service", name="fourniseur_service")
     */
    public function index(): Response
    {
        return $this->render('fourniseur_service/index.html.twig', [
            'controller_name' => 'FourniseurServiceController',
        ]);
    }
    /**
     * @Route("/fourniseur/service/affiche",name="afficherfourniseur")
     */
    public function affiche(){
        $repo=$this->getDoctrine()->getRepository(FourniseurService::class)->findAll();
        return $this->render('fourniseur_service/affiche.html.twig',['fourniseur'=>$repo]);
    }
    /**
     * @Route("/fourniseur/service/details/{id}",name="detailsfourniseur")
     */
    public function affichedetails($id){
        $repo=$this->getDoctrine()->getRepository(FourniseurServiceRepository::class)->find($id);
        return $this->render('fourniseur_service//details.html.twig',['fourniseur'=>$repo]);
    }
    /**
     * @Route("/fourniseur/service/delete/{id}",name="deletefourniseur")
     */
    public function delete($id,FourniseurServiceRepository $repo){
        $em=$this->getDoctrine()->getManager();
        $fourniseur=$repo->find($id);
        $em->remove($fourniseur);
        $em->flush();
        return $this->redirectToRoute('afficherfourniseur');
    }
    /**
     * @Route("/fourniseur/service/ajouter",name="Ajouterfourniseur")
     */
    function Ajout(Request $request){
        $fourniseur=new FourniseurService();
        $form=$this->createForm(FournisseurType::class,$fourniseur);
        $form->handleRequest($request);
        if($form->isSubmitted()&&$form->isValid()){
            $em=$this->getDoctrine()->getManager();
            $em->persist($fourniseur);//insert into
            $em->flush();//maj de la BD
            return $this->redirectToRoute("afficherfourniseur");
        }
        return $this->render('fourniseur_service/ajout.html.twig',['f'=>$form->createView()]);
    }

    /**
     * @Route("/fourniseur/service/update/{id}",name="updatefourniseur")
     */

    function update($id,FourniseurServiceRepository $repo,Request $request){
        $fourniseur=$repo->find($id) ;
        $form=$this->createForm(FournisseurType::class, $fourniseur);
        $form->handleRequest($request);
        if($form->isSubmitted()&&$form->isValid()){
            $em=$this->getDoctrine()->getManager();
            $em->flush();//maj de la BD
            return $this->redirectToRoute("afficherfourniseur");
        }

        return $this->render("fourniseur_service/update.html.twig",['f'=>$form->createView()]);

    }
}
