<?php

namespace App\Form;

use App\Entity\Actor;
use App\Entity\Movie;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MovieFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'attr'=>array(
                    'class'=>'bg-transparent block border-b-2 w-full h-20 text-6xl outline-none',
                    'placeholder'=>'Enter the title...'
                ),
                'label'=>false,
                'required'=>false
            ])
            ->add('releaseYear', IntegerType::class,[
                'attr'=>array(
                    'class'=>'bg-transparent block mt-10 border-b-2 w-full h-20 text-6xl outline-none',
                    'placeholder'=>'Enter release year...'
                ),
                'label'=>false,
                'required'=>false
            ])
            ->add('description', TextareaType::class, [
                'attr'=>array(
                    'class'=>'bg-transparent block mt-10 border-b-2 w-full h-60 text-6xl outline-none',
                    'placeholder'=>'Enter description...'
                ),
                'label'=>false,
                'required'=>false
            ])
            ->add('imagePath', FileType::class, array(
                'attr'=> array(
                    'class' => 'block w-full text-sm text-gray-900 mt-10 border border-gray-300 rounded-lg cursor-pointer dark:text-gray-400 focus:outline-none dark:border-gray-600 dark:placeholder-gray-400'
                ),
                'required' => false,
                'mapped' => false,
            ))
//            ->add('actors', EntityType::class, [
//                'class' => Actor::class,
//                'choice_label' => 'id',
//                'multiple' => true,
//            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Movie::class,
        ]);
    }
}
