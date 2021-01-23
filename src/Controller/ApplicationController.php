<?php

namespace App\Controller;

use App\Entity\Application;
use App\Entity\Instructor;
use App\Form\ApplicationType;
use App\Form\php;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ApplicationController extends AbstractController
{
    /**
     * @Route("/student/application", name="application")
     */
    public function create(Request $request)
    {
        $application = new Application();
        $form = $this->createForm(ApplicationType::class, $application);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $file=$application->getCv();
            $fileName=md5(uniqid()).'.'.$file->guessExtension();
            $file->move($this->getParameter('cv_directory'), $fileName);
            $application->setCv($fileName);
            $application = $form->getData();
      //      $application->setCreatedAt(new \DateTime());


            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($application);
            $entityManager->flush();

            return $this->redirectToRoute('home');
        }
        return $this->render('application/index.html.twig', [
            'controller_name' => 'ApplicationController','ApplicationForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/applications", name="applications")
     */
    public function index()
    {
        $repo = $this->getDoctrine()->getRepository(Application::class);
        $applications = $repo->findAll();
        return $this->render('application/list.html.twig', [
            'controller_name' => 'ApplicationController',
            'applications' => $applications
        ]);
    }
    /**
     * @Route("/admin/application/{id}", name="showapplication")
     */
    public function show($id)
    {
        $repo = $this->getDoctrine()->getRepository(Application::class);
        $application = $repo->find($id);
        return $this->render('application/show.html.twig', [
            'controller_name' => 'ApplicationController',
            'application' => $application
        ]);
    }

    /**
     * @Route("/admin/delete/{id}",name="delete")
     */
    public function delete($id){
        $repo = $this->getDoctrine()->getRepository(Application::class);
        $application = $repo->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($application);
        $em->flush();
        return $this->redirectToRoute('applications');

    }





}
