<?php


namespace App\Form;


use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CategoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class,[
                "attr" => [
                    "placeholder" => "category title",
                    "class" => "form-control"

                ]
            ])
            ->add('description', TextType::class,[
                "attr" => [
                    "placeholder" => "category description",
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
        ;

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Category::class,
        ]);
    }
}