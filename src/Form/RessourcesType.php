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
            ->add('url', FileType::class, [
                'empty_data' => '',
                

            ])
            ->add('Ajouter', SubmitType::class, [
                'label' => 'Add',
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
