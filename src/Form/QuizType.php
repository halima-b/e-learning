<?php


namespace App\Form;


use App\Entity\Quiz;
use App\Entity\Course;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class QuizType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class,[
                "attr" => [
                    "placeholder" => "quiz title",
                    "class" => "form-control"

                ]
            ])
            ->add('course', EntityType::class,

            [
                "attr" => [
                    "class" => "form-control "

                ],
               
               'class' => Course::class,
                'choice_label' => 'title',
               

            ])

        ;

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Quiz::class,
            
        ]);
    }
}