<?php

namespace App\Form;

use App\Entity\Preparation;
use App\Entity\Ingredient;
use App\Entity\RecipeComposition;
use App\Repository\PreparationRepository;
use App\Repository\IngredientRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RecipeCompositionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('weight', IntegerType::class, [
                'attr'=>[
                    'placeholder' => 'Poids (g)',
                    'data-input-type' => 'weight'
                ],
                'required' => false
            ])
            ->add('volume',NumberType::class,[
                'required' => false,
                'attr' => [
                    'placeholder' => 'Volume (en L)',
                    'data-input-type' => 'volume'
                ]
            ])
            ->add('quantity', IntegerType::class, [
                'attr'=>[
                    'placeholder' => 'Qte',
                    'data-input-type' => 'quantity'
                ],
                'required' => false
            ])
            ->add('preparation', EntityType::class,[
                'class' => Preparation::class,
                'query_builder' => function (PreparationRepository $repo) {
                    return $repo->createQueryBuilder('p')
                        ->orderBy('p.name', 'ASC');
                },
                'choice_attr'=> function ($choice, string $key, mixed $value) {
                    if($choice->getWeight() >0){
                        $measureUnit = 'Kg';
                    }else{
                        $measureUnit = 'Unité';  
                    }
                    return [
                        'data-measure_unit' => $measureUnit,
                    ];
                },
                'required' => false,
                'placeholder' => 'Selectionner une préparation',
                'attr' => [
                    'class' => 'select2_autoload'
                ]
            ])
            ->add('ingredient', EntityType::class,[
                'class' => Ingredient::class,
                'query_builder' => function (IngredientRepository $repo) {
                    return $repo->createQueryBuilder('i')
                        ->orderBy('i.name', 'ASC');
                },
                'choice_attr'=> function ($choice, string $key, mixed $value) {
                    return [
                        'data-measure_unit' => $choice->getMeasureUnit()->getName(),
                    ];
                },
                'required' => false,
                'placeholder' => 'Selectionner un ingrédient',
                'attr' => [
                    'class' => 'select2_autoload'
                ]
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => RecipeComposition::class,
        ]);
    }
}
