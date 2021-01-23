<?php

namespace App\Controller;

use App\Entity\Course;
use App\Entity\Section;
use App\Form\SectionType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class SectionController extends AbstractController
{
    /**
     * @Route("/instructor/details/{id}", name="details")
     */
    public function index($id)
    {
        $repo = $this->getDoctrine()->getRepository(Course::class);
        $course = $repo->find($id);
        $sections = $course->getSections();



        return $this->render('section/index.html.twig', [
            'controller_name' => 'SectionController','course' => $course, 'sections' => $sections
        ]);
    }

    /**
     * @Route("/instructor/newsection/{id}", name="newsection")
     */
        public function new(Request $request,$id)
    {

        $repo = $this->getDoctrine()->getRepository(Course::class);
        $course = $repo->find($id);
        $section = new Section();
        $form = $this->createForm(SectionType::class, $section);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

          $section->setCourse($course);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($section);
            $entityManager->flush();

            return $this->redirect($this->generateUrl('details',['id' => $course->getId()]));
        }
        return $this->render('section/new.html.twig', [
            'controller_name' => 'SectionController','course' => $course,'SectionForm' => $form->createView(),
            'editMode' => $section->getId() !== null

        ]);

        }
        /**
    
     * @Route("/instructor/editsection/{id}", name="editsection")
     */
    public function edit(Request $request,$id)
    {

        $section = $this->getDoctrine()->getRepository(Section::class)->find($id);
        $course = $section->getCourse();

        $form = $this->createForm(SectionType::class, $section);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($section);
            $entityManager->flush();

            return $this->redirect($this->generateUrl('details',['id' => $course->getId()]));
        }
        return $this->render('section/new.html.twig', [
            'controller_name' => 'SectionController','course' => $course,'SectionForm' => $form->createView(),
            'editMode' => $section->getId() !== null

        ]);

        }

    /**
     * @Route("/instructor/deletesection/{id}",name="deletesection")
     */
    public function delete($id, Request $request){

        $repo = $this->getDoctrine()->getRepository(Section::class);
        $section = $repo->find($id);
        $course=$section->getCourse();
        $id_course = $course->getId();
        $em = $this->getDoctrine()->getManager();
        foreach ($section->getLessons() as $lesson){
            $em->remove($lesson);
        }
        $em->remove($section);
        $em->flush();
        return $this->redirect($this->generateUrl('details',['id' => $id_course]));

    }


}
