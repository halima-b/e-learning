<?php


namespace App\Form;


use App\Entity\Course;
use App\Entity\Student;
use App\Entity\Enrolement;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EnrolmentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
           
            ->add('student', EntityType::class,

                [
                    "attr" => [
                        "class" => "form-control "

                    ],
                   'class' => Student::class,
                    'choice_label' => function (Student $student) {
                        return $student->getNom() . ' ' . $student->getPrenom();},

                ])
                ->add('course', EntityType::class,

                [
                    "attr" => [
                        "class" => "form-control "

                    ],
                   'class' => Course::class,
                    'choice_label' => 'title'

                ])
        ;

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Enrolement::class,
        ]);
    }
}