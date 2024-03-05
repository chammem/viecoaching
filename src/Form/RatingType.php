<?php

namespace App\Form;

use App\Entity\Categorie;
use blackknight467\StarRatingBundle\Form\RatingType as StarRatingType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RatingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nomCategorie', TextType::class)
            // Ajoutez le champ de notation ici
            ->add('rating', StarRatingType::class, [
                'label' => 'Rating', // Optionnel : étiquette du champ de notation
            ]);
    }


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Categorie::class,
        ]);
    }
}
