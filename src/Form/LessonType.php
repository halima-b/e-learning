<?php


namespace App\Form;


use App\Entity\Category;
use App\Entity\Course;
use App\Entity\Lesson;
use App\Entity\Section;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LessonType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class,[
                "attr" => [
                    "placeholder" => "lesson title",
                    "class" => "form-control"

                ]
            ])
            ->add('content', FileType::class,[
                "attr" => [
                    "placeholder" => "content",
                    "class" => "form-control"

                ],
                'data_class' => null
            ])

            ->add('section', EntityType::class,

                [
                    "attr" => [
                        "class" => "form-control "

                    ],
                   'class' => Section::class,
                    'choice_label' => 'title'

                ])
        ;

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Lesson::class,
        ]);
    }
}