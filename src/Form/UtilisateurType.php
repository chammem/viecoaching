<?php

namespace App\Form;

use App\Entity\Role;
use App\Entity\Utilisateur;
use phpDocumentor\Reflection\PseudoTypes\False_;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints as Assert;

class UtilisateurType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('nom',TextType::class,['label' => false])
        ->add('prenom',TextType::class,['label' => false])
        ->add('email', EmailType::class,['label' => false])
        ->add('mdp', RepeatedType::class, [
            'type' => PasswordType::class,
            'first_options'  => ['label' => false],
            'second_options' => ['label' => false],
            'invalid_message' => 'Les mots de passe ne correspondent pas.'
        ])
        ->add('tel',TextType::class,['label' => false])
        ->add('genre', ChoiceType::class, [
            'choices' => [
                'Femme' => 'femme',
                'Homme' => 'homme',
            ],
            'label' => false, // Mettez le label à false ici
            'expanded' => true,
            'multiple' => false,
            'attr' => ['class' => 'radio-option']
        ],)
        ->add('ville',TextType::class,['label' => false])
        ->add('imageFile', FileType::class, [
            'label' => False,
            'mapped' => false,
            'constraints' => [
                new Assert\File([
                    'maxSize' => '1024k',
                    'mimeTypes' => [
                        'image/*',
                    ],
                    'mimeTypesMessage' => 'Veuillez télécharger un fichier image valide',
                ])
            ],
        ])
        ->add('role', EntityType::class, [
            'class' => Role::class,
            'choice_label' => 'Nomrole',
        ])
        ->add('Ajouter', SubmitType::class);}
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Utilisateur::class,
        ]);
    }
}
