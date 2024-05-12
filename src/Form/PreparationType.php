<?php

namespace App\Form;

use App\Entity\Preparation;
use App\Form\PreparationIngredientType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\OptionsResolver\OptionsResolver;


class PreparationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name',TextType::class, ['label'=> 'Nom préparation'])
            ->add('weight', NumberType::class,[
                'label'=> 'Poids préparation (en g)',
                'required' => false,
                ])
            ->add('preparationIngredients', CollectionType::class, [
                'entry_type' => PreparationIngredientType::class,
                'label' => false,
                'allow_add' => true,
                'allow_delete' => true,
                'delete_empty' => true,
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Preparation::class,
        ]);
    }
}
