<?php

namespace App\Controller;

use App\Entity\Users;
use App\Form\EditUserType;
use App\Repository\ReclamationRepository;
use App\Repository\UserRepository;
use phpDocumentor\Reflection\Type;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @Route("/admin", name="user")
     */
    public function index(): Response
    {
        return $this->render('baseBack.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }
    /**
     * @Route("/user", name="userr")
     */
    public function index1(): Response
    {
        return $this->render('baseUser.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }

    /**
     * @param UserRepository $repo
     * @return Response
     * @Route("/admin/AffichMedecin",name="AfficheMedecin")
     */
    function affichemed(UserRepository $repo){

        $user=$repo->findAll();

        return $this->render("user/AfficheMedecin.html.twig",
        ['user'=>$user]);
    }

    /**
     * @param UserRepository $repo
     * @return Response
     * @Route("/admin/affmed",name="affmed")
     */
    public function Affdoc(UserRepository $repo){
        $user=$repo->findAll();
        return $this->render("user/affmed.html.twig",
            ['user'=>$user]);


    }
    /**
     * @param UserRepository $repo
     * @return Response
     * @Route("/admin/AffichDelegue",name="AfficheDelegue")
     */
    function affichedel(UserRepository $repo){

        $user=$repo->findAll();

        return $this->render("user/AfficheDelegue.html.twig",
            ['user'=>$user]);
    }
    /**
     * @param UserRepository $repo
     * @return Response
     * @Route("/admin/AffichPatient",name="AffichePatient")
     */
    function affichepat(UserRepository $repo){

        $user=$repo->findAll();

        return $this->render("user/AffichePatient.html.twig",
            ['user'=>$user]);
    }

    /**
     * @param UserRepository $repo
     * @param $id
     * @return Response
     * @Route("/admin/AffichUser/{id}",name="Detailp")
     */
    function affichepatdet(UserRepository $repo,$id){

        $user=$repo->find($id);

        return $this->render("user/AffichePatdel.html.twig",
            ['user'=>$user]);
    }
    /**
     * @param UserRepository $repo
     * @param $id
     * @return Response
     * @Route("/admin/AfficheUser/{id}",name="Detailm")
     */
    function affichemd(UserRepository $repo,$id){

        $user=$repo->find($id);

        return $this->render("user/AfficheMedPha.html.twig",
            ['user'=>$user]);
    }

    /**
     * @param UserRepository $repo
     * @return Response
     * @Route("/admin/AffichPharmacien",name="AffichePharmacien")
     */
    function affichepha(UserRepository $repo){

        $user=$repo->findAll();

        return $this->render("user/AffichePharamacien.html.twig",
            ['user'=>$user]);
    }





    /**
     * @param $id
     * @param UserRepository $repo
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     *@Route("admin/Deleteu/{id}",name="Delete")
     */
    function Delete($id, UserRepository $repo){
        $user=$repo->find($id);
        $em=$this->getDoctrine()->getManager();
        $em->remove($user);
        $em->flush();
        return $this->redirectToRoute("user");
    }

    /**
     * @param ReclamationRepository $repRec
     * @param UserRepository $repUser
     * @param $id
     * @return Response
     * @Route("/user/listrec/{id}", name="usereclamation")
     */

    function ListReclamationBuUser(ReclamationRepository $repRec, UserRepository $repUser,$id){
        $user=$repUser->find($id);
        $reclamation=$repRec->listReclamationByUser($user->getId());
        return $this->render("user/show.html.twig",['rec'=>$reclamation]);


    }

    /**
     * @param UserRepository $rep
     * @param $id
     * @param UserRepository $rep1
     * @return Response
     *@Route("/user/affiche/{id}", name="userrrrr")
     */
    function AfficheUserid(UserRepository $rep,$id,UserRepository $rep1){
        $user=$rep->find($id);
        $userr=$rep1->AfficheUserqb($user->getId());
        return $this->render("registration/UserInterface.html.twig", ['user' => $user]);
    }

    /**
     * Modifier un utulisateur
     *
     * @Route("/user/modifier/{id}",name="modifierutilisateur")
     * @param Request $request
     * @param UserRepository $repo
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function editUser( Request $request, UserRepository $repo,$id){
        $user=$repo->find($id);
        $form = $this->createForm(EditUserType::class,$user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();

            $this->addFlash('message', 'Utilisateur modifié avec succès');
            return $this->redirectToRoute('user');
        }

        return $this->render('user/edituser.html.twig', [
            'userForm' => $form->createView(),
        ]);
    }







}
