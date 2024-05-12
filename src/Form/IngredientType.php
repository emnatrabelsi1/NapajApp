<?php

namespace App\Form;

use App\Entity\Allergen;
use App\Entity\Company;
use App\Entity\Ingredient;
use App\Entity\IngredientCategory;
use App\Entity\MeasureUnit;
use App\Repository\AllergenRepository;
use App\Repository\CompanyRepository;
use App\Repository\IngredientCategoryRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class IngredientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('company',EntityType::class,[
                'label' => 'Entreprise',
                'class' => Company::class,
                'attr' => [
                    'read_only' => true,
                    'class' => 'select2_autoload'
                ],
                'query_builder' => function (CompanyRepository $r): QueryBuilder {
                    return $r->createQueryBuilder('c')
                        ->orderBy('c.name', 'ASC');
                },
            ])
            ->add('code')
            ->add('name', TextType::class,[
                'label' => 'Libellé sur liste d\'ingrédients'
            ])
            ->add('measure_unit', EntityType::class,[
                'label' => 'Unité',
                'class' => MeasureUnit::class,
                'attr' => [
                    'class' => 'select2_autoload'
                ]
            ])
            ->add('ingredient_category', EntityType::class,[
                'label' => 'Catégorie',
                'class' => IngredientCategory::class,
                'placeholder' => 'Selectionner une catégorie',
                'required' => false,
                'query_builder' => function (IngredientCategoryRepository $r): QueryBuilder {
                    return $r->createQueryBuilder('ic')
                        ->orderBy('ic.name', 'ASC');
                },
                'attr' => [
                    'class' => 'select2_autoload'
                ],
            ])
            ->add('allergen',EntityType::class,[
                'label' => 'Allergène',
                'class' => Allergen::class,
                'required' => false,
                'attr' => [
                    'class' => 'select2_autoload',
                ],
                'query_builder' => function (AllergenRepository $r): QueryBuilder {
                    return $r->createQueryBuilder('a')
                        ->orderBy('a.name', 'ASC');
                },
                'placeholder' => 'Aucun',
            ])
            ->add('supplierPrices', CollectionType::class, [
                'entry_type' => SupplierPriceType::class,
                'label' => false,
                'allow_add' => true,
                'allow_delete' => true,
                'delete_empty' => true
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Ingredient::class,
        ]);
    }
}
