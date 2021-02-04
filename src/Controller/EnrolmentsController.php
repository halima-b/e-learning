<?php

namespace App\Controller;
use App\Entity\Enrolement;
use Symfony\Component\HttpFoundation\Request;
use App\Form\EnrolmentType;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class EnrolmentsController extends AbstractController
{
    /**
     * @Route("/admin/enrolments", name="enrolments")
     */
    public function index()
    {
        $repo = $this->getDoctrine()->getRepository(Enrolement::class);
        $enrolments = $repo->findAll();
        return $this->render('enrolments/index.html.twig', [
            'controller_name' => 'EnrolmentsController','enrolments' =>  $enrolments
        ]);
    }

    /**
     * @Route("/admin/enrol", name="new_enrolment")
     */
    public function create(Request $request)
    {
        

        $enrolment = new Enrolement();
        
        $form = $this->createForm(EnrolmentType::class, $enrolment);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
       $enrolment->setCreatedAt(new \DateTime());
            $enrolment = $form->getData();


            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($enrolment);
            $entityManager->flush();

            return $this->redirectToRoute('enrolments');
        }

      
        return $this->render('enrolments/new.html.twig', [
            'controller_name' => 'EnrolmentsController','EnrolmentForm' => $form->createView(),
        ]);
    }

       /**
     * @Route("/admin/deleteenrolment/{id}",name="delete_enrl")
     */
    public function delete($id){
        $repo = $this->getDoctrine()->getRepository(Enrolement::class);
        $enrolment = $repo->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($enrolment);
        $em->flush();
        return $this->redirectToRoute('enrolments');

    }
}
