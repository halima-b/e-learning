<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\WishList;
use App\Entity\Course;
use App\Entity\Comment;
use App\Form\CommentType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Form\ProfileType;
use App\Form\EditPasswordType;
use App\Form\EditPhotoType;
use App\Entity\Photo;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class StudentController extends AbstractController
{
    /**
     * @Route("/student", name="student")
     */
    public function index(Request $request,SessionInterface $session)
    {   
        
    
        $student = $this->getUser();
        $student_id = $student->getId();
        $wishlist_courses_n = $this->getDoctrine()->getRepository(WishList::class)->countAllCourses($student_id);
        $panier = $session->get('panier',[]);
        $panierWithdata = [];
        foreach($panier as $id => $quantity){
            $panierWithdata[] = [
                'course' => $this->getDoctrine()->getRepository(Course::class)->find($id),
                'quantity' => $quantity
            ];
        }
       
        $form = $this->createForm(ProfileType::class,$student);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
    
            $user = $form->getData();
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($student);
       /*     if ($this->isGranted('ROLE_STUDENT')) {
                $courses = $user->getCourses();
            foreach($courses as $course){
                $course->setAuthor($user->getNom().' '.$user->getPrenom());
                $entityManager->persist($course);
              }
            }*/
            $entityManager->flush();
            return $this->redirectToRoute('profile');
        }
    
        return $this->render('student/profile.html.twig', [
            'EditProfileForm' => $form->createView(),'w_c_n' => $wishlist_courses_n,'items' => $panierWithdata]);
    } 

   /**
     * @Route("/student/pass-edit", name="student_password_edit")
     */
    public function edit_password(Request $request,UserPasswordEncoderInterface $passwordEncoder,SessionInterface $session)
    {
       
        $student = $this->getUser();
        $student_id = $student->getId();
        $wishlist_courses_n = $this->getDoctrine()->getRepository(WishList::class)->countAllCourses($student_id);
        $panier = $session->get('panier',[]);
        $panierWithdata = [];
        foreach($panier as $id => $quantity){
            $panierWithdata[] = [
                'course' => $this->getDoctrine()->getRepository(Course::class)->find($id),
                'quantity' => $quantity
            ];
        }
    $form = $this->createForm(EditPasswordType::class,$student);
    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {

        $user = $form->getData();
        $password = $passwordEncoder->encodePassword($student, $student->getPlainPassword());
        $user->setPassword($password);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($student);
        $entityManager->flush();
        return $this->redirectToRoute('profile');
    }
        return $this->render('student/password.html.twig', [
            'EditPasswordForm' => $form->createView(),'w_c_n' => $wishlist_courses_n,'items' => $panierWithdata
        ]);
    }
     /**
     * @Route("/student/photo-edit", name="student_photo_edit")
     */
    public function edit_photo(Request $request,SessionInterface $session)
    {
       
    $student = $this->getUser();
    $photo= $student->getPhoto();
        $student_id = $student->getId();
        $wishlist_courses_n = $this->getDoctrine()->getRepository(WishList::class)->countAllCourses($student_id);
        $panier = $session->get('panier',[]);
        $panierWithdata = [];
        foreach($panier as $id => $quantity){
            $panierWithdata[] = [
                'course' => $this->getDoctrine()->getRepository(Course::class)->find($id),
                'quantity' => $quantity
            ];
        }
    
    $form = $this->createForm(EditPhotoType::class,$photo);
    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
        $file=$photo->getImage();
        $fileName=md5(uniqid()).'.'.$file->guessExtension();
        $file->move($this->getParameter('images_directory'), $fileName);
        $photo->setImage($fileName);
        $photo = $form->getData();
        $student->setPhoto($photo);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($photo);
        $entityManager->persist($student);
        $entityManager->flush();

        return $this->redirectToRoute('profile');
    }
        return $this->render('student/editPhoto.html.twig', [
            'EditPhotoForm' => $form->createView(),'w_c_n' => $wishlist_courses_n,'items' => $panierWithdata
        ]);
    }


}