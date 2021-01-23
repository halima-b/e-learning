<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


class CategoriesController extends AbstractController
{
    /**
     * @Route("/admin/categories", name="categories")
     */
    public function index()
    {
        $repo = $this->getDoctrine()->getRepository(Category::class);
        $categories = $repo->findAll();
        return $this->render('categories/index.html.twig', [
            'controller_name' => 'CategoriesController',
            'categories' => $categories
        ]);
    }
    /**
     * @Route("/admin/newcategory", name="newcategory")
     * @Route("/admin/{id}/editcategory", name="editcategory")
     */
    public function form(Category $category = null,Request $request)
    {
        if(! $category){
            $category = new Category();
         }

        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $file=$category->getImage();
            $fileName=md5(uniqid()).'.'.$file->guessExtension();
            $file->move($this->getParameter('images_directory'), $fileName);
            $category->setImage($fileName);
            $category = $form->getData();
            if(!$category->getId()){$category->setCreatedAt(new \DateTime());}


            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($category);
            $entityManager->flush();

            return $this->redirectToRoute('categories',['id' => $category->getId()]);
        }

        return $this->render('categories/new.html.twig', [
            'controller_name' => 'CategoriesController','CategoryForm' => $form->createView(),
            'editMode' => $category->getId() !== null
        ]);
    }

    /**
     * @Route("/admin/deletecategory/{id}",name="deletecategory")
     */
    public function delete($id){
        $repo = $this->getDoctrine()->getRepository(Category::class);
        $category = $repo->find($id);
        $em = $this->getDoctrine()->getManager();
        foreach ($category->getCourses() as $course){
            foreach ($course->getSections() as $section){
                foreach ($section->getLessons() as $lesson){
                    $em->remove($lesson);
                }
                $em->remove($section);
            }
            $em->remove($course);
        }
        $em->remove($category);
        $em->flush();
        return $this->redirectToRoute('categories');

    }

}
