<?php


namespace App\Form;


use App\Entity\Application;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ApplicationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class,[
                "attr" => [
                    "placeholder" => "Last Name",
                    "class" => "form-control"

                ]
            ])
            ->add('prenom', TextType::class,[
                "attr" => [
                    "placeholder" => "First Name",
                    "class" => "form-control"

                ]
            ])
            ->add('email', EmailType::class,[
                "attr" => [
                    "placeholder" => "Email",
                    "class" => "form-control"

                ]
            ])
            ->add('message', TextareaType::class,[
                "attr" => [
                    "placeholder" => "Motivations",
                    "class" => "form-control"

                ]
            ])
            ->add('phone', TextType::class,[
                "attr" => [
                    "placeholder" => "Phone Number",
                    "class" => "form-control"

                ]
            ])
            ->add('cv', FileType::class,[
                "attr" => [
                    
                    "class" => "form-control"

                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Application::class,
            
        ]);
    }
}