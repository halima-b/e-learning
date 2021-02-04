<?php

namespace App\Controller;

use App\Entity\Course;
use App\Entity\Lesson;
use App\Entity\Section;
use App\Form\LessonType;
use App\Form\SectionType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class LessonController extends AbstractController
{
    /**
     * @Route("/lesson", name="lesson")
     */
    public function index()
    {
        return $this->render('lesson/index.html.twig', [
            'controller_name' => 'LessonController',
        ]);
    }
    /**
     * @Route("/instructor/newlesson/{id}", name="newlesson")
     */
    public function new(Request $request,$id)
    {
       
        $repo = $this->getDoctrine()->getRepository(Course::class);
        $course = $repo->find($id);
        $lesson = new Lesson();
        $form = $this->createForm(LessonType::class, $lesson);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $file=$lesson->getContent();
            $fileName=md5(uniqid()).'.'.$file->guessExtension();
            $file->move($this->getParameter('videos_directory'), $fileName);
            $lesson->setContent($fileName);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($lesson);
            $entityManager->flush();

            return $this->redirect($this->generateUrl('details',['id' => $course->getId()]));
        }
        return $this->render('lesson/new.html.twig', [
            'controller_name' => 'LessonController', 'course' => $course,'LessonForm' => $form->createView(),
            'editMode' => $lesson->getId() !== null

        ]);
    }

     /**
     * @Route("/instructor/editlesson/{id}", name="editlesson")
     */
    public function edit(Request $request,$id)
    {
        $lesson = $this->getDoctrine()->getRepository(Lesson::class)->find($id);
        $course = $lesson->getSection()->getCourse();
        $form = $this->createForm(LessonType::class, $lesson);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $file=$lesson->getContent();
            $fileName=md5(uniqid()).'.'.$file->guessExtension();
            $file->move($this->getParameter('videos_directory'), $fileName);
            $lesson->setContent($fileName);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($lesson);
            $entityManager->flush();

            return $this->redirect($this->generateUrl('details',['id' => $course->getId()]));
        }
        return $this->render('lesson/new.html.twig', [
            'controller_name' => 'LessonController', 'course' => $course,'LessonForm' => $form->createView(),
            'editMode' => $lesson->getId() !== null

        ]);
    }
    /**
     * @Route("/instructor/deletelesson/{id}",name="deletelesson")
     */
    public function delete($id, Request $request){

        $repo = $this->getDoctrine()->getRepository(Lesson::class);
        $lesson = $repo->find($id);
        $course=$lesson->getSection()->getCourse();
        $id_course = $course->getId();
        $em = $this->getDoctrine()->getManager();
        $em->remove($lesson);
        $em->flush();
        return $this->redirect($this->generateUrl('details',['id' => $id_course]));

    }
}
