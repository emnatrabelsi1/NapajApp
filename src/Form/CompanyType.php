<?php

namespace App\Form;

use App\Entity\Company;
use App\Entity\Customer;
use App\Repository\CustomerRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CompanyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('feature_recipe', CheckboxType::class, [
                'label' => 'Module recettes',
                'required' => false
            ])
            ->add('manage_cutting', CheckboxType::class, [
                'label' => 'Gestion des découpes',
                'required' => false
            ])
            ->add('feature_product', CheckboxType::class, [
                'label' => 'Module produits',
                'required' => false
            ])
            ->add('feature_order', CheckboxType::class, [
                'label' => 'Module commande',
                'required' => false
            ])
            ->add('is_napaj', CheckboxType::class, [
                'label' => 'Rattaché à Napaj',
                'required' => false
            ])
            ->add('relative_customer', EntityType::class,[
                'label' => 'Client lié',
                'class' => Customer::class,
                'required' => false,
                'placeholder' => 'Selectionner un client',
                'attr' => [
                    'class' => 'select2_autoload',
                    
                ],
                'query_builder' => function (CustomerRepository $r): QueryBuilder {
                    return $r->createQueryBuilder('c')
                        ->orderBy('c.name', 'ASC');
                }
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Company::class,
        ]);
    }
}
