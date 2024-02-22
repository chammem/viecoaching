<?php

namespace App\Form;

use App\Entity\Ressources;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\DataTransformer\FileToStringTransformer;
use Symfony\Component\Validator\Constraints\File;

class RessourcesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('TitreR', null, [
                'label' => 'Title',
                'empty_data' => '',

            ])
            ->add('TypeR', null, [
                'label' => 'Resource Type',
                'empty_data' => '',

            ])
            ->add('description', null, [
                'label' => 'Resource Type',
                'empty_data' => '',

            ])
            ->add('url', FileType::class, [
                'label' => 'Image (JPEG, PNG, GIF)',
                // Champ facultatif
                'required' => false,
                // Champ non lié à une propriété de l'entité Groupe
               // 'mapped' => false,
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
                
            ->add('Ajouter', SubmitType::class, [
               
            ]);
           
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'attr' => [
                'novalidate' => 'novalidate',
                'data_class' => Ressources::class,
            ]
                ]);
    }
}
