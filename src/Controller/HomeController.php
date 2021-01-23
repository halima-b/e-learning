<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Course;
use App\Entity\Enrolement;
use App\Entity\Comment;
use App\Entity\WishList;
use App\Form\CommentType;
use App\Repository\WishListRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Security;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(SessionInterface $session)
    {
        $security_context = $this->container->get('security.authorization_checker');
        $repo1 = $this->getDoctrine()->getRepository(Category::class);
        $categories = $repo1->findAll();
        $repo2 = $this->getDoctrine()->getRepository(Course::class);
        $courses = $repo2->findAll();
        $wishlist_courses_n=0;
        if($security_context->isGranted('IS_AUTHENTICATED_FULLY')){
        $student = $this->getUser();
        $student_id = $student->getId();
        $wishlist_courses_n = $this->getDoctrine()->getRepository(WishList::class)->countAllCourses($student_id);
        
        }
        $panier = $session->get('panier',[]);
        $panierWithdata = [];
        foreach($panier as $id => $quantity){
            $panierWithdata[] = [
                'course' => $this->getDoctrine()->getRepository(Course::class)->find($id),
                'quantity' => $quantity
            ];
        }


        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController','categories' => $categories, 'courses' => $courses,'w_c_n' => $wishlist_courses_n,'items' => $panierWithdata,
        ]);
    }
    /**
     * @Route("/profile", name="profile")
     */
    public function profile()
    {


        if ( $this->isGranted('ROLE_ADMIN')) {
        return $this->redirectToRoute('admin');
    }
        if ($this->isGranted('ROLE_STUDENT')) {
            return $this->redirectToRoute('student');
        }
        if ($this->isGranted('ROLE_INSTRUCTOR')) {
            return $this->redirectToRoute('instructor');
        }
        return $this->redirectToRoute('app_login');

    }
    /**
     * @Route("/categories/{id}", name="courses")
     */
    public function courses($id)
    {
        $repo1 = $this->getDoctrine()->getRepository(Category::class);
        $category = $repo1->find($id);
        $courses = $category->getCourses();

        return $this->render('home/courses_categorie.html.twig', [
            'controller_name' => 'HomeController', 'courses' => $courses
        ]);
    }
    /**
     * @Route("/courses/{id}", name="course")
     */
    public function course($id,Request $request,SessionInterface $session)
    {
        $p = null;
        $panier = $session->get('panier',[]);
        if(!empty($panier[$id])){
            $p = 1;
        }
        $security_context = $this->container->get('security.authorization_checker');
        $course = $this->getDoctrine()->getRepository(Course::class)->find($id);
        
        $comments = $course->getComments();
        $wl = null;
        $e=null;
        $c=null;
        $average = round($this->getDoctrine()->getRepository(Comment::class)->countRating($id));
        $total = $this->getDoctrine()->getRepository(Comment::class)->countComments($id);
        $students = $this->getDoctrine()->getRepository(Enrolement::class)->countStudents($id);
        
        if($security_context->isGranted('IS_AUTHENTICATED_FULLY')){
        $student = $this->getUser();
        $student_id = $student->getId();
        $wl = $this->getDoctrine()->getRepository(WishList::class)->findOneById($student_id, $id);
        $e = $this->getDoctrine()->getRepository(Enrolement::class)->findOneById($student_id, $id);
        $c = $this->getDoctrine()->getRepository(Comment::class)->findOneById($student_id, $id);
        

    
    }

        $user = $this->getUser();
        $comment = new Comment();
        $comment->setCourse($course);
        $comment->setUser($user);
        $form = $this->createForm(CommentType::class, $comment);
    
       
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $comment = $form->getData();
            $comment->setCreatedAt(new \DateTime());
            $comment->setUser($user);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($comment);
            $entityManager->flush();
           
            return $this->redirect($this->generateUrl('course',['id' => $id]));
        }

        return $this->render('home/coursedetails.html.twig', [
            'controller_name' => 'HomeController','course' => $course, 'p'=> $p,'wl' => $wl,'e' => $e,'c' => $c,'avg' => $average,'total' => $total,'students' => $students, 'form_comment' => $form->createView(),'comments' => $comments
        ]);
    }
    /**
     * @Route("/student/check/{id}", name="check_wishlist")
     */
    public function check($id)
    {
        $security_context = $this->container->get('security.authorization_checker');
        $course = $this->getDoctrine()->getRepository(Course::class)->find($id);
        $student = $this->getUser();
        $student_id = $student->getId();
        $wl = $this->getDoctrine()->getRepository(WishList::class)->findOneById($student_id, $id);

        if($security_context->isGranted('IS_AUTHENTICATED_FULLY')){

            if( $wl == null){
            $wishlist = new WishList();
            $wishlist->setStudent($student);
            $wishlist->setCourse($course);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($wishlist);
            $entityManager->persist($course);
            $entityManager->persist($student);
            $entityManager->flush();
        }

            if($wl != null){
           
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($wl);
            $entityManager->flush();


        }
        return $this->redirect($this->generateUrl('course',['id' => $id ,'course' => $course, 'wl' => $wl]));
      
         
        }

        return $this->redirectToRoute('app_login');
    }

   /**
     * @Route("/panier/add/{id}", name="cart_add")
     */
    public function cart($id,SessionInterface $session )
    {   
        $p = null;
        
        $panier = $session->get('panier',[]);
        if(!empty($panier[$id])){
            $p = 1;
        }else{$panier[$id] = 1;}
        
        $session->set('panier',$panier);
        
        return $this->redirect($this->generateUrl('course',['id' => $id , 'p'=> $p]));
       
    }
    /**
     * @Route("/panier", name="panier")
     */
    public function panier(SessionInterface $session )
    {   

        $panier = $session->get('panier',[]);
        $panierWithdata = [];
        foreach($panier as $id => $quantity){
            $panierWithdata[] = [
                'course' => $this->getDoctrine()->getRepository(Course::class)->find($id),
                'quantity' => $quantity
            ];
        }
        $total =0;

        foreach($panierWithdata as $item){
            $totalitem = $item['course']->getPrice() * $item['quantity'];
            $total += $totalitem;
        }
        $wishlist_courses_n=0;
        if ( $this->isGranted('ROLE_ADMIN')) {
        $student = $this->getUser();
        $student_id = $student->getId();
        $wishlist_courses_n = $this->getDoctrine()->getRepository(WishList::class)->countAllCourses($student_id);}
        return $this->render('home/panier.html.twig',[ 'items' => $panierWithdata,'total' => $total,'w_c_n' => $wishlist_courses_n]);

       
    }

    /**
     * @Route("/panier/remove/{id}", name="cart_remove")
     */
    public function remove($id,SessionInterface $session ){

        $panier = $session->get('panier',[]);
        if(!empty($panier[$id])){
            unset($panier[$id]);
        }
        $session->set('panier',$panier);

        return $this->redirect($this->generateUrl('panier'));

    }


}
