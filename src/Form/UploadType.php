<?php

namespace App\Form;

use App\Entity\Album;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UploadType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('image', FileType::class, [
            'label'    => 'Images',
            'multiple' => true,
            'required' => false,
            'mapped'   => false,
            'attr'     => [
                'accept' => 'image/*',
                'class'   => 'widget',
            ],
        ])
                ->add('album_name', EntityType::class, [
                    'class'       => Album::class,
                    'attr' => [
                        'class'   => 'widget',
                    ],
                    'label'       => "Choisir un album",
                    'required'    => false,
                ])
                ->add('new_album_name', TextType::class, [
                    'required' => false,
                    'attr' => [
                        'class'   => 'widget',
                    ],
                    'label'    => 'Créer un nouvel album',
                ])
                ->add('date_taken', DateType::class, [
                    'attr' => [
                        'class'   => 'widget',
                    ],
                    'label' => "Date de l'album",
                    'years' => range(2000, date('Y')),
                ])
                ->add('submit', SubmitType::class, [
                    'attr' => [
                        'class'   => 'widget',
                    ],
                    'label' => 'Valider',
                ]);

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
