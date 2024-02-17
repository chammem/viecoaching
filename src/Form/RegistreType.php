<?php

namespace App\Form;

use App\Entity\Utilisateur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints as Assert;

class RegistreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('nom', TextType::class, [
            'label' => 'Nom:',
        ])
        ->add('prenom', TextType::class, [
            'label' => 'Prénom:',
        ])
        ->add('email',TextType::class, [
            'label' => 'Adresse email:',
        ])
            ->add('tel', TextType::class,  [
                'label' => 'Numéro de téléphone:',
            ], [
                'attr' => [
                    'placeholder' => 'Entrez votre numéro de téléphone',
                    'class' => 'msform'
                ]] , 
               )
            ->add('mdp', RepeatedType::class, [
                'type' => PasswordType::class,
                'first_options'  => ['label' => 'Mot de passe:'],
                'second_options' => ['label' => 'Confirmation mot de passe:'],
                'invalid_message' => 'Les mots de passe ne correspondent pas.'
            ])
            ->add('genre', ChoiceType::class, [
                'choices'  => [
                    'Femme' => 'femme',
                    'Homme' => 'homme',
                ],
                'expanded' => true,
                'multiple' => false,
                'attr' => ['class' => 'radio-option']
            ], [
                'label' => 'Genre:',
            ])
            ->add('image', FileType::class, [
                'label' => 'Télecharger vote photo:',   
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/*',
                        ],
                        
                    ])
                ],
            ])
            ->add('ville', TextType::class , 
            [
                'label' => 'Ville',
            ])
            
            ->add('Suivant',SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Utilisateur::class,
        ]);
    }
}
