<?php


namespace App\Form;



use App\Entity\Answer;
use App\Entity\Question;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AnswerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('answer', TextType::class,[
                "attr" => [
                    "placeholder" => "choice",
                    "class" => "form-control"

                ]
            ])
           
          
            ->add('question', EntityType::class,

                [
                    "attr" => [
                        "class" => "form-control "

                    ],
                   'class' => Question::class,
                    'choice_label' => 'question'

                ])
        ;

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Answer::class,
        ]);
    }
}