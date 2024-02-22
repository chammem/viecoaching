<?php

namespace App\Form;

use App\Entity\Categorie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class CategorieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('nomCategorie', TextType::class, [
            'empty_data' => '',
            // other options...
        ])
        ->add('description', TextType::class, [
            'empty_data' => '',
            // other options...
        ])
        ->add('image', FileType::class, [
            'label' => 'Image (JPEG, PNG, GIF)',
            // Champ facultatif
            'required' => false,
            // Champ non lié à une propriété de l'entité Groupe
            'mapped' => false,
            'constraints' => [
                new File([
                    'maxSize' => '1024k',
                    'mimeTypes' => [
                        'image/jpeg',
                        'image/png',
                        'image/gif',
                    ],
                    'mimeTypesMessage' => 'Veuillez télécharger une image valide (JPEG, PNG, GIF)',
                ])
            ],
        ])
            ->add('ressource')
            ->add('Ajouter',SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'attr' => [
                'novalidate' => 'novalidate', // comment me to reactivate the html5 validation!
            ]
                ]);
    }
}
