<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Form\ProfileType;
use App\Form\EditPasswordType;
use App\Form\EditPhotoType;
use App\Entity\Photo;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;



class ProfileController extends AbstractController
{
    /**
     * @Route("/profile-edit", name="profile_edit")
     */
    public function edit(Request $request)
    {
       
    $user = $this->getUser();
    $form = $this->createForm(ProfileType::class,$user);
    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {

        $user = $form->getData();
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($user);
        if ($this->isGranted('ROLE_INSTRUCTOR')) {
            $courses = $user->getCourses();
        foreach($courses as $course){
            $course->setAuthor($user->getNom().' '.$user->getPrenom());
            $entityManager->persist($course);
          }
        }
        $entityManager->flush();
        return $this->redirectToRoute('profile');
    }
        return $this->render('profile/edit.html.twig', [
            'controller_name' => 'ProfileController','EditProfileForm' => $form->createView()
        ]);
    }


    /**
     * @Route("/pass-edit", name="password_edit")
     */
    public function edit_pass(Request $request,UserPasswordEncoderInterface $passwordEncoder)
    {
       
    $user = $this->getUser();
    $form = $this->createForm(EditPasswordType::class,$user);
    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {

        $user = $form->getData();
        $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
        $user->setPassword($password);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($user);
        $entityManager->flush();
        return $this->redirectToRoute('profile');
    }
        return $this->render('profile/password.html.twig', [
            'controller_name' => 'ProfileController','EditPasswordForm' => $form->createView()
        ]);
    }
     /**
     * @Route("/photo-edit", name="photo_edit")
     */
    public function edit_photo(Request $request)
    {
        
    $user = $this->getUser();
    $photo= $user->getPhoto();
    $form = $this->createForm(EditPhotoType::class,$photo);
    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
        $file=$photo->getImage();
        $fileName=md5(uniqid()).'.'.$file->guessExtension();
        $file->move($this->getParameter('images_directory'), $fileName);
        $photo->setImage($fileName);
        $photo = $form->getData();
        $user->setPhoto($photo);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($photo);
        $entityManager->persist($user);
        $entityManager->flush();

        return $this->redirectToRoute('profile');
    }
        return $this->render('profile/editPhoto.html.twig', [
            'controller_name' => 'ProfileController','EditPhotoForm' => $form->createView()
        ]);
    }
}
