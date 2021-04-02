<?php

namespace App\Controller;

use App\Entity\CategorieArticle;
use App\Form\CategorieArticleType;
use App\Repository\CategorieArticleRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategorieArticleController extends AbstractController
{
    /**
     * @Route("/categorie/article", name="categorie_article")
     */
    public function index(): Response
    {
        return $this->render('categorie_article/index.html.twig', [
            'controller_name' => 'CategorieArticleController',
        ]);
    }
    /**
     * @Route("/categorie/affiche",name="affichercatcategorie")
     */
    public function affiche(){
        $repo=$this->getDoctrine()->getRepository(CategorieArticle::class)->findAll();
        return $this->render('categorieArticle/affiche.html.twig',['categorie'=>$repo]);
    }
    /**
     * @Route("/categorie/details/{id}",name="detailscatcategorie")
     */
    public function affichedetails($id){
        $repo=$this->getDoctrine()->getRepository(CategorieArticle::class)->find($id);
        return $this->render('categorieArticle/details.html.twig',['categorie'=>$repo]);
    }
    /**
     * @Route("categorie/delete/{id}",name="deletecatcategorie")
     */

    public function delete($id,CategorieArticleRepository $repo){
        $em=$this->getDoctrine()->getManager();
        $categorie=$repo->find($id);
        $em->remove($categorie);
        $em->flush();
        return $this->redirectToRoute('affichercatcategorie');
    }


    /**
     * @Route("/categorie/ajouter",name="Ajoutercatcategorie")
     */
    function Ajout(Request $request){
        $categorie=new CategorieArticle();
        $form=$this->createForm(CategorieArticleType::class,$categorie);

        $form->handleRequest($request);

        if($form->isSubmitted()&&$form->isValid()){
            $em=$this->getDoctrine()->getManager();
            $em->persist($categorie);//insert into
            $em->flush();//maj de la BD
            return $this->redirectToRoute("affichercatcategorie");
        }
        return $this->render('categorieArticle/ajout.html.twig',['form'=>$form->createView()]);
    }

    /**
     * @Route("categorie/update/{id}",name="updatecatcategorie")
     * @param $id
     * @param CategorieArticleRepository $repo
     * @param Request $request
     * @param $categorie
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */

    function update($id, CategorieArticleRepository $repo, Request $request){
        $categorie=$repo->find($id) ;
        $form = $this->createForm(CategorieArticleType::class,$categorie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('affichercatcategorie');
        }

        return $this->render('categorieArticle/update.html.twig', [
            'categorie' => $categorie,
            'form' => $form->createView(),
        ]);
    }


}
