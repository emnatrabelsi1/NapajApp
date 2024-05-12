<?php

namespace App\Form;

use App\Entity\Customer;
use App\Entity\Order;
use App\Repository\CustomerRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $customerId = $builder->getData()->getCustomer() ? $builder->getData()->getCustomer()->getId() : null;
        $builder
            ->add('name', TextType::class, [
                    'label' => 'Description',
                    'attr' => [
                        'autocomplete' => 'off'
                    ],
                    'required' => false
                ])
            ->add('delivery_date', DateTimeType::class,[
                'label' =>  'Date livraison souhaitée*',
                "attr"=>[
                    "class"=> 'datepicker',
                    'autocomplete' => 'off'
                ],
                'html5' => false,
                'widget' => 'single_text',
                'format' => 'dd/MM/yy',
            ])
            ->add('event_date', DateTimeType::class,[
                'label' =>  'Date évènement',
                "attr"=>[
                    "class"=> 'datepicker',
                    'autocomplete' => 'off'
                ],
                'required' => false,
                'html5' => false,
                'widget' => 'single_text',
                'format' => 'dd/MM/yy',
            ])
            ->add('customer',EntityType::class,[
                'label' => 'Client',
                'class' => Customer::class,
                'placeholder' => 'Selectionner un client',
                'query_builder' => function (CustomerRepository $r) use ($customerId, $options): QueryBuilder {
                    $qb = $r->createQueryBuilder('c');
                    if($options['isNapaj'] == false){
                        $qb->where('c.id = :customerId')->setParameter('customerId', $customerId);
                    }
                    return $qb->orderBy('c.name', 'ASC');
                },
                'attr' => [
                    'class' => 'select2_autoload'
                ],
                'disabled' => ($options['isNapaj'] == false)
            ])
            ->add('orderLines', CollectionType::class, [
                'entry_type' => OrderLineType::class,
                'entry_options'  => array(
                    'isNapaj' => $options['isNapaj'],
                    'customerId' => $customerId
                ),
                'label' => false,
                'allow_add' => true,
                'allow_delete' => true,
                'delete_empty' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Order::class,
            'isNapaj' => false,
        ]);
    }
}
