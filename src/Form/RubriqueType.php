<?php

namespace App\Form;

use App\Entity\Rubrique;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;

class RubriqueType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre')
            ->add('contenu')
            /*->add('dateCreation')*/
            /*->add('datePublication')*/
            ->add('etat')
            /*->add('commentaires')*/
            ->add('image', FileType::class, [
                'label' => 'Image (JPEG, PNG)',
                'mapped' => false, // This means it's not a property of Rubrique but a separate field
                'required' => false, // Set it to true if the image is mandatory
            ])
            ->add('save',SubmitType::class);
            
                
            }
        

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Rubrique::class,
            'addImage' => false,
            
        ]);
    }

}

