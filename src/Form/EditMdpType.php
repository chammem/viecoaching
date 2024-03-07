<?php

namespace App\Form;

use App\Entity\Utilisateur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
class EditMdpType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
       
        ->add('mdp', RepeatedType::class, [
            'type' => PasswordType::class,
            'first_options'  => ['label' => false],
            'second_options' => ['label' => false],
            'invalid_message' => 'Les mots de passe ne correspondent pas.'
        ])
        ->add('newMdp', PasswordType::class, [
            'label' => 'Nouveau mot de passe',
            'constraints' => [new Assert\NotBlank()],
            'mapped' => false, 
        ])
        ->add('Modifier', SubmitType::class);
        
        }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Utilisateur::class,
        ]);
    }
}
