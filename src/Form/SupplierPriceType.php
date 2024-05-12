<?php

namespace App\Form;

use App\Entity\Company;
use App\Entity\Ingredient;
use App\Entity\Supplier;
use App\Entity\SupplierPrice;
use App\Repository\IngredientRepository;
use App\Repository\SupplierRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SupplierPriceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('ingredient',EntityType::class,[
                'label' => 'Code ingrédient interne',
                'class' => Ingredient::class,
                'placeholder' => 'Selectionner un ingrédient',
                'query_builder' => function (IngredientRepository $r): QueryBuilder {
                    return $r->createQueryBuilder('i')
                        ->orderBy('i.code', 'ASC');
                },
                'attr' => [
                    'class' => 'select2_autoload'
                ]
            ])
            ->add('supplier',EntityType::class,[
                'label' => 'Fournisseur',
                'class' => Supplier::class,
                'placeholder' => 'Selectionner un fournisseur',
                'query_builder' => function (SupplierRepository $r): QueryBuilder {
                    return $r->createQueryBuilder('s')
                        ->orderBy('s.name', 'ASC');
                },
                'attr' => [
                    'class' => 'select2_autoload'
                ]
            ])
            ->add('name', TextType::class, [
                'label' =>  'Référence ingrédient chez le fournisseur'
            ])
            ->add('price', NumberType::class, [
                'label' => 'Prix'
            ])
            ->add('created_at', DateTimeType::class,[
                'label' =>  'Date ajout',
                "attr"=>[
                    "class"=> 'datepicker',
                ],
                'html5' => false,
                'widget' => 'single_text',
                'format' => 'dd/MM/yy',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SupplierPrice::class,
        ]);
    }
}
