<?php

namespace App\Controller;

use App\Entity\Roles;
use App\Entity\User;
use App\Form\DelegueType;
use App\Form\MedecinType;
use App\Form\PatientType;
use App\Form\PharamacienType;
use App\Form\ReclamationType;
use App\Form\RegistrationFormType;
use App\Form\RoleType;
use App\Repository\UserRepository;
use App\Security\AppUserAuthenticator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;

class RegistrationController extends AbstractController
{


    /**
     * @Route("/register", name="app_register")
     */
    public function register(UserRepository $repo, Request $request, UserPasswordEncoderInterface $passwordEncoder, GuardAuthenticatorHandler $guardHandler, AppUserAuthenticator $authenticator): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
            // do anything else you need here, like send an email
            if ($user->getType() == "patient") {
                return $this->redirectToRoute("app_login");
            } elseif ($user->getType() == "medecin") {
                return $this->redirectToRoute("app_login");
                /*$form=$this->createForm(MedecinType::class,$user);
                $form->add('confirmer',SubmitType::class);
                $form->handleRequest($request);
                if($form->isSubmitted() && $form->isValid()) {
                    $entityManager = $this->getDoctrine()->getManager();
                    $entityManager->flush();
                    return $this->redirectToRoute('app_login');
                }
                return $this->render("registration/medecin.html.twig",['form'=>$form->createView()]);*/
            } elseif ($user->getType() == "pharmacien") {
                return $this->redirectToRoute("app_login");
                /*$form=$this->createForm(PharamacienType::class,$user);
                $form->add('confirmer',SubmitType::class);
                $form->handleRequest($request);
                if($form->isSubmitted() && $form->isValid()) {
                    $entityManager = $this->getDoctrine()->getManager();
                    $entityManager->flush();
                    return $this->redirectToRoute('app_login');
                }
                return $this->render("registration/pharmacien.html.twig",['form'=>$form->createView()]);*/
            } else {
                return $this->redirectToRoute("app_login");
                /*$form=$this->createForm(DelegueType::class,$user);
                $form->add('confirmer',SubmitType::class);
                $form->handleRequest($request);
                if($form->isSubmitted() && $form->isValid()) {
                    $entityManager = $this->getDoctrine()->getManager();
                    $entityManager->flush();
                    return $this->redirectToRoute('app_login');
                }
                return $this->render("registration/delegue.html.twig",['form'=>$form->createView()]);*/
            }


            return $guardHandler->authenticateUserAndHandleSuccess(
                $user,
                $request,
                $authenticator,
                'main' // firewall name in security.yaml
            );
        }


        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    /**
     * @param UserRepository $repo
     * @return Response
     * @Route("userinterface",name="userinterface")
     */
    public function userinetrface(UserRepository $repo)
    {
        $user = $repo->findAll();

        return $this->render("registration/UserInterface.html.twig", ['user' => $user]);
    }

    /**
     * @param UserRepository $repo
     * @param $id
     * @param Request $req
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     *@Route("/update/{id}",name="Modifier")
     */
    function UpdateUser(UserRepository $repo,$id,Request $req){
        $user=$repo->find($id);
        if ($user->getType() == "medecin") {
        $form=$this->createForm(MedecinType::class,$user);
        $form->add('modifier',SubmitType::class);
        $form->handleRequest($req);
        if($form->isSubmitted() && $form->isValid()){
            $file = $user->getImage();
            $filename = md5(uniqid()).'.'.$file->guessExtension();
            $em=$this->getDoctrine()->getManager();
            $user->setImage($filename);
            $em->flush();
            return $this->redirectToRoute('userinterface');
        }
        return $this->render("registration/medecin.html.twig", ['form' => $form->createView()]);}
        elseif ($user->getType() == "patient") {
        $form = $this->createForm(PatientType::class, $user);
        /*$form->add('confirmer', SubmitType::class);*/
        $form->handleRequest($req);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();

            return $this->redirectToRoute('userinterface');

        }
        return $this->render("registration/Patient.html.twig", ['form' => $form->createView()]);
    }
        elseif ($user->getType() == "pharmacien") {

            $form = $this->createForm(PharamacienType::class, $user);
         /*   $form->add('confirmer', SubmitType::class);*/
            $form->handleRequest($req);
            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->flush();
                return $this->redirectToRoute('userinterface');
            }
            return $this->render("registration/pharmacien.html.twig", ['form' => $form->createView()]);
        }
        elseif ($user->getType() == "delegue"){
            $form=$this->createForm(DelegueType::class,$user);
          /*  $form->add('confirmer',SubmitType::class);*/
            $form->handleRequest($req);
            if($form->isSubmitted() && $form->isValid()) {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->flush();
                return $this->redirectToRoute('userinterface');
            }
            return $this->render("registration/delegue.html.twig",['form'=>$form->createView()]);
        }

    }


   /* function Update(UserRepository $repo, $id, Request $req)
    {
        $user = $repo->findBy($id);
        if ($user->getType() == "patient") {
            $form = $this->createForm(PatientType::class, $user);
            $form->add('confirmer', SubmitType::class);
            $form->handleRequest($req);
            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->flush();

                return $this->redirectToRoute('userinterface');

            }
            return $this->render("registration/Patient.html.twig", ['form' => $form->createView()]);
        }
        elseif ($user->getType() == "medecin") {
            $form = $this->createForm(MedecinType::class, $user);
            $form->add('confirmer', SubmitType::class);
            $form->handleRequest($req);
            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->flush();

                return $this->redirectToRoute('userinterface');

            }
            return $this->render("registration/medecin.html.twig", ['form' => $form->createView()]);
        }elseif ($user->getType() == "pharmacien") {

            $form = $this->createForm(PharamacienType::class, $user);
            $form->add('confirmer', SubmitType::class);
            $form->handleRequest($req);
            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->flush();
                return $this->redirectToRoute('userinterface');
            }
            return $this->render("registration/pharmacien.html.twig", ['form' => $form->createView()]);
        }
        elseif ($user->getType() == "delegue"){
            $form=$this->createForm(DelegueType::class,$user);
            $form->add('confirmer',SubmitType::class);
            $form->handleRequest($req);
            if($form->isSubmitted() && $form->isValid()) {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->flush();
                return $this->redirectToRoute('userinterface');
            }
            return $this->render("registration/delegue.html.twig",['form'=>$form->createView()]);
        }
    }*/
}

