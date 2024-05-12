<?php

namespace App\Form;

use App\Entity\Ingredient;
use App\Entity\PreparationIngredient;
use App\Repository\IngredientRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PreparationIngredientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('weight',NumberType::class,[
                'required' => false,
                'attr' => [
                    'placeholder' => 'Poids (en g)',
                    'data-input-type' => 'weight'
                ]
            ])
            ->add('volume',NumberType::class,[
                'required' => false,
                'attr' => [
                    'placeholder' => 'Volume (en L)',
                    'data-input-type' => 'volume'
                ]
            ])
            ->add('quantity',NumberType::class,[
                'required' => false,
                'attr' => [
                    'placeholder' => 'Nb pièce',
                    'data-input-type' => 'quantity'
                ]
            ])
            ->add('ingredient',EntityType::class,[
                'class' => Ingredient::class,
                'placeholder' => 'Selectionner un ingrédient',
                'attr' => [
                    'class' => 'select2_autoload'
                ],
                'choice_attr'=> function ($choice, string $key, mixed $value) {
                    return [
                        'data-measure_unit' => $choice->getMeasureUnit()->getName(),
                    ];
                },
                'query_builder' => function (IngredientRepository $r): QueryBuilder {
                    return $r->createQueryBuilder('i')
                        ->orderBy('i.code', 'ASC');
                },
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => PreparationIngredient::class,
        ]);
    }
}
