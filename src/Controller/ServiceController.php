<?php

namespace App\Controller;

use App\Entity\Service;
use App\Form\ServiceType;
use App\Repository\CategorieServiceRepository;
use App\Repository\ServiceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class ServiceController extends AbstractController
{

    /**
     * @Route("service/affiche/{categorie}", name="afficheservicesparcategorie")
     */
    public function caffiche(ServiceRepository $repos,CategorieServiceRepository $repoc,$categorie){

        $servicecat=$repos->findallbycategorie($categorie);
        $cat=$repoc->find($categorie);
        return $this->render('service/Caffiche.html.twig',['services'=>$servicecat,'categorie'=>$cat]);
    }
    /**
     * @Route("admin/service/affiche", name="afficherservice")
     */
    public function affiche(ServiceRepository $repo){
        return $this->render('service/affiche.html.twig',['services'=>$repo->findAll()]);
    }
    /**
     * @Route("/service", name="services")
     */
    public function services(ServiceRepository $repo){
        return $this->render('service/services.html.twig',['services'=>$repo->findAll()]);
    }
    /**
     * @Route("admin/service/delete/{id}",name="deleteservice")
     */
    public function delete( $id ,ServiceRepository $repo){
        $em=$this->getDoctrine()->getManager();
        $service=$repo->find($id);
        $em->remove($service);
        $em->flush();
        return $this->redirectToRoute('afficherservice');
    }
    /**
     * @Route("admin/service/ajouter",name="Ajouterservice")
     */
    function Ajout(Request $request){
        $service=new Service();
        $form=$this->createForm(ServiceType::class,$service);
        $form->add("ajouter",SubmitType::class);
        $form->handleRequest($request);
        if($form->isSubmitted()){
            $em=$this->getDoctrine()->getManager();
            $em->persist($service);//insert into
            $em->flush();//maj de la BD
            return $this->redirectToRoute("afficherservice");
        }
        return $this->render('service/ajout.html.twig',['f'=>$form->createView()]);
    }

    /**
     * @Route("admin/service/update/{id}",name="updateservice")
     */
    function update($id,ServiceRepository $repo,Request $request){
        $service=$repo->find($id) ;
        $form=$this->createForm(ServiceType::class,$service);
        $form->add("update",SubmitType::class);
        $form->handleRequest($request);
        if($form->isSubmitted()&&$form->isValid()){
            $em=$this->getDoctrine()->getManager();
            $em->flush();//maj de la BD
            return $this->redirectToRoute("afficherservice");
        }

        return $this->render("service/update.html.twig",['f'=>$form->createView()]);

    }
    /**
     * @Route("admin/service/details/{id}",name="detailservice")
     */
    public function affichedetails($id){
        $repo=$this->getDoctrine()->getRepository(Service::class)->find($id);
        return $this->render('service/details.html.twig',['service'=>$repo]);
    }
    /**
     * @Route("service/details/{id}",name="service")
     */
    public function service($id){
        $repo=$this->getDoctrine()->getRepository(Service::class)->find($id);
        return $this->render('service/service.html.twig',['service'=>$repo]);
    }
}

