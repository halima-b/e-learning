<?php


namespace App\Form;

use App\Entity\Comment;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('content', TextareaType::class,[
                "attr" => [
                    "class" => "form-control "

                ],

              
            ])
            ->add('rating', ChoiceType::class,[
                "attr" => [
                    "rating" => "rating",
                    "class" => "form-control "

                ],

                    'choices' => [
                        '1 of 5' => '1',
                        '2 of 5' => '2',
                        '3 of 5'   => '3',
                        '4 of 5' => '4',
                        '5 of 5' => '5',

                        ]
            ])

        ;

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Comment::class,
        ]);
    }

   
}