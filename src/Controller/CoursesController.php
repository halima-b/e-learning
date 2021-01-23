<?php

namespace App\Controller;

use App\Entity\Course;
use App\Entity\Enrolement;
use App\Entity\WishList;
use App\Form\CourseType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class CoursesController extends AbstractController
{

    /**
     * @Route("/instructor/mycourses", name="mycourses")
     */
    public function show(Security $security)
    {
        $user = $this->getUser();
        $courses = $user->getCourses();
        return $this->render('courses/index.html.twig', [
            'controller_name' => 'CoursesController',
            'courses' => $courses
        ]);
    }

    /**
     * @Route("/instructor/newcourse", name="newcourse")
     * @Route("/instructor/editcourse/{id}", name="editcourse")
     */
    public function form(Course $course = null,Request $request)
    {
        $user = $this->getUser();

        if(! $course){
            $course = new Course();
        }

        $form = $this->createForm(CourseType::class, $course);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $file=$course->getImage();
            $fileName=md5(uniqid()).'.'.$file->guessExtension();
            $file->move($this->getParameter('images_directory'), $fileName);
            $course->setImage($fileName);
           $course->setAuthor($user->getNom().' '.$user->getPrenom());
            $course->setInstructor($user);
            $course = $form->getData();
            if(!$course->getId()){$course->setCreatedAt(new \DateTime());}


            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($course);
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('mycourses',['id' => $course->getId()]);
        }

        return $this->render('courses/new.html.twig', [
            'controller_name' => 'CoursesController','CourseForm' => $form->createView(),
            'editMode' => $course->getId() !== null
        ]);
    }
    /**
     * @Route("/instructor/deletecourse/{id}",name="deletecourse")
     */
    public function delete($id){
        $repo = $this->getDoctrine()->getRepository(Course::class);
        $course = $repo->find($id);
        $em = $this->getDoctrine()->getManager();
        foreach ($course->getSections() as $section){
            foreach ($section->getLessons() as $lesson){
                $em->remove($lesson);
            }
            $em->remove($section);
        }
        $em->remove($course);
        $em->flush();
        return $this->redirectToRoute('mycourses');

 
   }

 /**
     * @Route("/courses", name="all_courses")
     */
    public function index(Security $security)
    {
        $courses = $this->getDoctrine()->getRepository(Course::class)->findAll();
        

        return $this->render('courses/allcourses.html.twig', [
            'controller_name' => 'CoursesController',
            'courses' => $courses,
        ]);
    }
 /**
     * @Route("/student/wishlist", name="wishlist_courses")
     */
    public function list()
    {
      
        $student = $this->getUser();
        $student_id = $student->getId();
        $courses = $this->getDoctrine()->getRepository(WishList::class)->findAllCourses($student_id);
        $wishlist_courses_n = $this->getDoctrine()->getRepository(WishList::class)->countAllCourses($student_id);

       
      
        return $this->render('courses/wishlistcourses.html.twig', [
            'courses' => $courses,'w_c_n' => $wishlist_courses_n
        ]);
    }

   
}
