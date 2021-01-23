<?php

namespace App\Controller;

use App\Entity\Application;
use App\Entity\Instructor;
use App\Form\EditInstructorType;
use App\Form\InstructorType;
use App\Form\php;
use App\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class InstructorController extends AbstractController
{/**
 * @Route("/admin/instructors", name="instructors")
 */
    public function instructors()
    {
        $repo = $this->getDoctrine()->getRepository(Instructor::class);
        $instructors = $repo->findAll();
        return $this->render('instructor/list.html.twig', [
            'controller_name' => 'InstructorController',
            'instructors' => $instructors
        ]);
    }
    /**
     * @Route("/admin/{id}/newinstructor", name="addinstructor")
     */
    public function accept($id,Request $request,UserPasswordEncoderInterface $passwordEncoder)
    {
        $repo = $this->getDoctrine()->getRepository(Application::class);
        $application = $repo->find($id);
        $instructor = new Instructor();
        $instructor->setNom($application->getName());
        $instructor->setPrenom($application->getPrenom());
        $instructor->setEmail($application->getEmail());
        $form = $this->createForm(UserType::class,$instructor);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $instructor = $form->getData();
            $password = $passwordEncoder->encodePassword($instructor, $instructor->getPlainPassword());
            $instructor->setPassword($password);
            $instructor->setRoles(array('ROLE_INSTRUCTOR'));
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($instructor);
            $entityManager->flush();

             return $this->redirectToRoute('instructors');
        }
        return $this->render('instructor/new.html.twig', [
            'controller_name' => 'InstructorController','InstructorForm' => $form->createView()
        ]);
    }
    /**
     * @Route("/admin/suspend/{id}", name="suspend")
     */
    public function  suspend($id)
    {
        $repo = $this->getDoctrine()->getRepository(Instructor::class);
        $instructor = $repo->find($id);
        $instructor->setStatus(false);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist( $instructor);
        $entityManager->flush();
        return $this->redirectToRoute('instructors');

    }
    /**
     * @Route("/admin/activate/{id}", name="activate")
     */
    public function activate($id)
    {
        $repo = $this->getDoctrine()->getRepository(Instructor::class);
        $instructor = $repo->find($id);
        $instructor->setStatus(true);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist( $instructor);
        $entityManager->flush();

        return $this->redirectToRoute('instructors');

    }

 /**
  * @Route("/admin/{id}/editinstructor", name="edit_instructor")
  */
    public function edit($id,Request $request)
{
    $repo = $this->getDoctrine()->getRepository(Instructor::class);
    $instructor = $repo->find($id);
    $form = $this->createForm(InstructorType::class,$instructor);
    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {

        $instructor = $form->getData();
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($instructor);
        $entityManager->flush();

        return $this->redirectToRoute('instructors');
    }
    return $this->render('instructor/edit.html.twig', [
        'controller_name' => 'InstructorController','EditForm' => $form->createView()
    ]);
}

    /**
     * @Route("/admin/deleteinstructor/{id}",name="delete_instructor")
     */
    public function delete($id){
        $repo = $this->getDoctrine()->getRepository(Instructor::class);
        $instructor = $repo->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($instructor);
        $em->flush();
        return $this->redirectToRoute('instructors');

    }

    /**
     * @Route("/instructor", name="instructor")
     */
    public function index()
    {
        return $this->render('instructor/index.html.twig', [
            'controller_name' => 'InstructorController',
        ]);
    }

}
