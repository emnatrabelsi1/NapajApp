<?php

namespace App\Form;

use App\Entity\Noncompliance;
use App\Entity\NoncomplianceState;
use App\Entity\NoncomplianceType as EntityNoncomplianceType;
use App\Entity\Order;
use App\Repository\NoncomplianceStateRepository;
use App\Repository\NoncomplianceTypeRepository;
use App\Repository\OrderRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NoncomplianceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        if($options['action'] == 'edit'){
            $disableMainFields = true;
        }else{
            $disableMainFields = false;
        }
        $builder
            ->add('declarant')
            ->add('comment', TextareaType::class, [ 'label' => 'Commentaire'])
            ->add('declaration_date',DateTimeType::class,[
                'label' =>  'Date déclaration',
                "attr"=>[
                    "class"=> 'datepicker',
                    'autocomplete' => 'off'
                ],
                'html5' => false,
                'widget' => 'single_text',
                'format' => 'dd/MM/yy',
                'disabled' =>  $disableMainFields
            ])
            ->add('processing_date',DateTimeType::class,[
                'label' =>  'Date traitement',
                'required' => false,
                "attr"=>[
                    "class"=> 'datepicker',
                    'autocomplete' => 'off'
                ],
                'html5' => false,
                'widget' => 'single_text',
                'format' => 'dd/MM/yy',
            ])
            ->add('processing_comment', TextareaType::class, [ 'label' => 'Commentaire traitement','required' => false,])
            ->add('assigned')
            ->add('noncomplianceType',EntityType::class,[
                'label' => 'Type',
                'class' => EntityNoncomplianceType::class,
                'placeholder' => 'Selectionner un type',
                'query_builder' => function (NoncomplianceTypeRepository $r): QueryBuilder {
                    return $r->createQueryBuilder('c')
                        ->orderBy('c.name', 'ASC');
                },
                'attr' => [
                    'class' => 'select2_autoload'
                ],
                'disabled' => $disableMainFields
            ])
            ->add('relativeOrder',EntityType::class,[
                'label' => 'Commande concernée',
                'class' => Order::class,
                'required' => false,
                'placeholder' => 'Selectionner la commande',
                'query_builder' => function (OrderRepository $r): QueryBuilder {
                    return $r->createQueryBuilder('o')
                        ->orderBy('o.delivery_date', 'DESC');
                },
                'attr' => [
                    'class' => 'select2_autoload'
                ],
                'disabled' => $disableMainFields
            ])
            ->add('noncomplianceState',EntityType::class,[
                'label' => 'Statut ',
                'class' => NoncomplianceState::class,
                'required' => false,
                'placeholder' => '',
                'query_builder' => function (NoncomplianceStateRepository $r): QueryBuilder {
                    return $r->createQueryBuilder('o')
                        ->orderBy('o.name', 'DESC');
                },
                'attr' => [
                    'class' => 'select2_autoload'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Noncompliance::class,
        ]);
    }
}
