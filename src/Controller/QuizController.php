<?php

namespace App\Controller;
use App\Entity\Quiz;
use App\Entity\Question;
use App\Entity\Answer;
use App\Form\QuizType;
use App\Form\QuestionType;
use App\Form\AnswerType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class QuizController extends AbstractController
{
    /**
     * @Route("/instructor/quizzes", name="quizzes")
     */
    public function index()

    {
        $user = $this->getUser();
        $courses = $user->getCourses();
        
        return $this->render('quiz/index.html.twig', [
            'controller_name' => 'QuizController','courses'=>$courses
        ]);
    }

/**
     * @Route("/instructor/newquiz", name="newquiz")
     */
    public function form(Request $request)
    {
        
        $quiz = new Quiz();
        $form = $this->createForm(QuizType::class, $quiz);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $quiz = $form->getData();
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($quiz);
          
            $entityManager->flush();

            return $this->redirectToRoute('quizzes',['id' => $quiz->getId()]);
        }

        return $this->render('quiz/new.html.twig', [
            'QuizForm' => $form->createView(),
            'editMode' => $quiz->getId() !== null
        ]);
    }


    /**
     * @Route("/instructor/editquiz/{id}", name="editquiz")
     */
    public function update(Request $request,$id)
    {
        $quiz = $this->getDoctrine()->getRepository(Quiz::class)->find($id);
        $form = $this->createForm(QuizType::class, $quiz);
        $field = $form->get('course');
        $attrs = $field->getConfig()->getOptions();
        $attrs['attr']['readonly'] ='readonly';
        $attrs['disabled'] ='disabled';
        $form->remove($field->getName());
        $form->add($field->getName(),
             get_class($field->getConfig()->getType()->getInnerType()),
             $attrs);
        
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $quiz = $form->getData();


            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($quiz);
          
            $entityManager->flush();

            return $this->redirectToRoute('quizzes',['id' => $quiz->getId()]);
        }

        return $this->render('quiz/new.html.twig', [
            'QuizForm' => $form->createView(),
            'editMode' => $quiz->getId() !== null
        ]);
    }
     

     /**
     * @Route("/instructor/quiz_details/{id}", name="quiz_details")
     */
    public function more($id)
    {
        
        $quiz = $this->getDoctrine()->getRepository(Quiz::class)->find($id);
        $questions = $quiz->getQuestion();
       
        return $this->render('quiz/details.html.twig', [
            'quiz' => $quiz, 'questions' => $questions,
            
        ]);
    }

     /**
     * @Route("/instructor/newquestion/{id}", name="new_question")
     */
    public function new(Request $request,$id)
    {

        $quiz = $this->getDoctrine()->getRepository(Quiz::class)->find($id);
        $question = new Question();

        $form = $this->createForm(QuestionType::class, $question, array('rightAnswer' => $fields->getAnswer()));
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $question->setQuiz($quiz);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($question);
            $entityManager->flush();
  
            return $this->redirect($this->generateUrl('quiz_details',['id' => $quiz->getId()]));
        }
        return $this->render('quiz/newQuestion.html.twig', [
            'quiz' => $quiz,'QuestionForm' => $form->createView(),
            'editMode' => $question->getId() !== null

        ]);

        }

     /**
     * @Route("/instructor/editquestion/{id}", name="edit_question")
     */
    public function edit(Request $request,$id)
    {

        $question =  $this->getDoctrine()->getRepository(Question::class)->find($id);
        $quiz =$question->getQuiz();
        $form = $this->createForm(QuestionType::class, $question);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $question->setQuiz($quiz);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($question);
            $entityManager->flush();
  
            return $this->redirect($this->generateUrl('quiz_details',['id' => $quiz->getId()]));
        }
        return $this->render('quiz/newQuestion.html.twig', [
            'quiz' => $quiz,'QuestionForm' => $form->createView(),
            'editMode' => $question->getId() !== null

        ]);

        }

        /**
     * @Route("/instructor/newanswer/{id}", name="newlesson")
     */
    public function add(Request $request,$id)
    {
       
        $quiz = $this->getDoctrine()->getRepository(Quiz::class)->find($id);
        $answer = new Answer();
        $form = $this->createForm(AnswerType::class, $answer);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
        
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($answer);
            $entityManager->flush();

            return $this->redirect($this->generateUrl('quiz_details',['id' => $quiz->getId()]));
        }
        return $this->render('quiz/newAnswer.html.twig', [
             'quiz' => $quiz,'AnswerForm' => $form->createView(),
            'editMode' => $answer->getId() !== null

        ]);
    }
}
