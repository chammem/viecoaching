<?php

namespace App\Form;

use App\Entity\Role;
use App\Entity\Utilisateur;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints as Assert;

class UtilisateurType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('nom')
        ->add('prenom')
        ->add('email', EmailType::class);

    if (!$builder->getData()->getId()) {
        $builder->add('mdp', RepeatedType::class, [
            'type' => PasswordType::class,
            'first_options'  => ['label' => 'Mot de passe'],
            'second_options' => ['label' => 'Confirmer le mot de passe'],
        ]);
    }

    $builder
        ->add('tel')
        ->add('genre', ChoiceType::class, [
            'choices'  => [
                'Femme' => 'femme',
                'Homme' => 'homme',
            ],
            'expanded' => true,
            'multiple' => false,
            'attr' => ['class' => 'radio-option']
        ])
        ->add('ville')
        ->add('imageFile', FileType::class, [
            'label' => 'Télécharger votre photo:',
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
        ->add('save', SubmitType::class);}
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Utilisateur::class,
        ]);
    }
}
