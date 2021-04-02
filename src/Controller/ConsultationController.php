<?php

namespace App\Controller;

use App\Entity\Consultation;
use App\Form\ConsultationType;
use App\Repository\ConsultationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ConsultationController extends AbstractController
{
    /**
     * @Route("/Liste-des-consultations", name="consultation")
     * @param ConsultationRepository $repo
     * @return Response
     */
    public function Affiche(ConsultationRepository $repo)
    {
        $consultation = $repo->findAll();
        return $this->render('consultation/AfficherConsultation.html.twig', ['consultation' => $consultation]);

    }

    /**
     * @Route ("/suppConsultation/{id}",name="suppConsultation")
     * @param ConsultationRepository $repo
     * @param $id
     * @return RedirectResponse
     */
    public function supprimer($id, ConsultationRepository $repo)
    {
        $consultationRepository = $repo->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($consultationRepository);
        $em->flush();
        return $this->redirectToRoute('consultation');
    }

    /**
     * @param Request $request
     * @return Response
     * @Route ("/ajouter-une-Consultation",name="AddConsultation")
     */

    public function Ajouter(Request $request)
    {
        $consultation = new Consultation();
        $form = $this->createForm(ConsultationType::class, $consultation);
        $form->add('Ajouter', SubmitType::class);
        $form->handleRequest($request); //parcourir la requete et extraire les champs du form et l'entité
        if ($form->isSubmitted() && $form->isValid()) {
            $consultation->setIsAccepted(null);
            $em = $this->getDoctrine()->getManager();
            $em->persist($consultation);
            $em->flush();
            return $this->redirectToRoute('consultation');
        }
        //la vue qui va gérer la vue de l'ajout
        // du formulaire pas dans la condition si vous avez remarqué
        return $this->render('consultation/AjouterConsultation.html.twig', ['form' => $form->createView()]);

    }

    /**
     * @param ConsultationRepository $repo
     * @param $id
     * @param Request $request
     * @return RedirectResponse|Response
     * @Route ("/modifier-une-consultation/{id}",name="UpdateConsultation")
     */
    public function modifier(ConsultationRepository $repo, $id, Request $request)
    {
        $consultation = $repo->find($id);
        $form = $this->createForm(ConsultationType::class, $consultation);
        $form->add('Update', SubmitType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute('consultation');
        }
        return $this->render('consultation/UpdateQuestion.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @param ConsultationRepository $repo
     * @return Response
     * @Route ("/Liste-des-consultations-medecin", name="consultationMedecin")
     */
    public function afficherConsultationMedecin(ConsultationRepository $repo)
    {
        $consultation = $this->getDoctrine()
            ->getRepository(Consultation::class)
            ->findForDoctor(1);

        return $this->render('consultation/AfficherConsultationMedecin.html.twig', ['consultation' => $consultation]);


    }
    /**
     * @param ConsultationRepository $repo
     * @return Response
     * @Route ("/Liste-des-consultations-patient", name="consultationPatient")
     */
    public function afficherConsultationPatient(ConsultationRepository $repo)
    {
        $consultation = $this->getDoctrine()
            ->getRepository(Consultation::class)
            ->findForPatient(1);

        return $this->render('consultation/AfficherConsultationPatient.html.twig', ['consultation' => $consultation]);

    }

    /**
     * @param ConsultationRepository $repo
     * @param $id
     * @param Request $request
     * @return RedirectResponse|Response
     * @Route ("/modifier-une-consultation/accepter-Consultation/{id}",name="AccepterConsultation")
     */
    public function AccepterConsultation(ConsultationRepository $repo, $id, Request $request)
    {
        $consultation = $repo->find($id);
        $consultation->setIsAccepted(true);
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute('consultationMedecin');

    }

}

