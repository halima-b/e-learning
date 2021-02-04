<?php

namespace App\Controller;

use App\Entity\Course;
use App\Entity\Quiz;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class FormationController extends AbstractController
{
    /**
     * @Route("/formation/{id}", name="formation")
     */
    public function index($id)
    {
        $course = $this->getDoctrine()->getRepository(Course::class)->find($id);
        $quiz =$course->getQuiz();
        return $this->render('formation/index.html.twig', [
            'controller_name' => 'FormationController','course'=>$course,'quiz'=>$quiz
        ]);
    }


     /**
     * @Route("/student/quiz/{id}",name="pass_quiz")
     */
    public function quiz($id){

        $quiz = $this->getDoctrine()->getRepository(Quiz::class)->find($id);
        $count_qst = $this->getDoctrine()->getRepository(Quiz::class)->countQuestions($id);
        $tab = array();
        $t1=array();
        $t2=array();
        for($i=0;$i< $count_qst;$i++){
        foreach($quiz->getQuestion() as $question ){
            $q = $question->getQuestion();
            $ra =$question->getRightAnswer();

            foreach($question->getAnswer() as $answer){
                  
                array_push($t1,$answer->getAnswer());
              
        }
        
        $t2[]=array( $q,$t1,$ra);
         }
         $tab[$i]=array($t2);
        
   
} 
    
     

        return $this->render('formation/quiz.html.twig', [
            'controller_name' => 'FormationController','count_qst'=>$count_qst,'tab'=>$tab,'quiz'=>$quiz,'t1'=>$t1
        ]);

    }

}
