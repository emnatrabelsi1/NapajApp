<?php

namespace App\Form;

use App\Entity\Supplier;
use App\Entity\SupplyOrder;
use App\Entity\SupplyOrderState;
use App\Repository\SupplierRepository;
use App\Repository\SupplyOrderStateRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SupplyOrderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('comment', TextareaType::class, [
                'label' => 'Commentaire',
                'attr' => [
                    'autocomplete' => 'off',
                    'rows' => 4,
                ],
                'required' => false
            ])
            ->add('amount', NumberType::class, [
                'label' => 'Montant'
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
            ->add('state',EntityType::class,[
                'label' => 'Statut',
                'class' => SupplyOrderState::class,
                'placeholder' => 'Selectionner un statut',
                'query_builder' => function (SupplyOrderStateRepository $r): QueryBuilder {
                    return $r->createQueryBuilder('s')
                        ->orderBy('s.name', 'ASC');
                },
                'attr' => [
                    'class' => 'select2_autoload'
                ]
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
            'data_class' => SupplyOrder::class,
        ]);
    }
}
