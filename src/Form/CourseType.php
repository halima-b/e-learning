<?php


namespace App\Form;


use App\Entity\Category;
use App\Entity\Course;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CourseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class,[
                "attr" => [
                    "placeholder" => "course title",
                    "class" => "form-control"

                ]
            ])
            ->add('description', TextType::class,[
                "attr" => [
                    "placeholder" => "description",
                    "class" => "form-control"

                ]
            ])
            ->add('level', ChoiceType::class,[
                "attr" => [
                    "placeholder" => "level",
                    "class" => "form-control"

                ],

                'choices' => [
                    'Beginner' => 'Beginner',
                    'Intermediate' => 'Intermediate',
                    'Advanced'   => 'Advanced',
                ]
            ])
            ->add('language', ChoiceType::class,[
                "attr" => [
                    "placeholder" => "language",
                    "class" => "form-control"

                ],

                    'choices' => [
                        'English' => 'English',
                        'Spanish' => 'Spanish',
                        'French'   => 'French',
                        'Arabic' => 'Arabic',
                    ]
            ])
            ->add('price', TextType::class,[
                "attr" => [
                    "placeholder" => "price",
                    "class" => "form-control"

                ]
            ])
            ->add('image', FileType::class,

                [

                "attr" => [
                    "class" => "form-control "

                ],

                'data_class' => null
            ])
            ->add('category', EntityType::class,

                [
                    "attr" => [
                        "class" => "form-control "

                    ],
                   'class' => Category::class,
                    'choice_label' => 'title'

                ])
        ;

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Course::class,
        ]);
    }
}