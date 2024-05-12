<?php

namespace App\Form;

use App\Entity\Customer;
use App\Entity\Tag;
use App\Repository\TagRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CustomerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class,[
                'label' => 'Nom'
            ])
            ->add('address', TextType::class,[
                'label' => 'Adresse',
                'required' => false
            ])
            ->add('address2', TextType::class,[
                'label' => 'Adresse 2',
                'required' => false
            ])
            ->add('zipcode', TextType::class,[
                'label' => 'Code postal',
                'required' => false
            ])
            ->add('city', TextType::class,[
                'label' => 'Ville',
                'required' => false
            ])
            ->ADD('route',TextType::class,[
                'label' => 'Itinéraire',
                'required' => false
            ])
            ->add('internal', CheckboxType::class, [
                'label' => 'Interne à Napaj',
                'required' => false
            ])
            ->add('subscribed_tags', EntityType::class,[
                'label' => 'Tags suivis',
                'class' => Tag::class,
                'required' => false,
                'multiple' => true,
                'attr' => [
                    'class' => 'select2_autoload',                
                ],
                'query_builder' => function (TagRepository $r): QueryBuilder {
                    return $r->createQueryBuilder('t')
                        ->where('t.public = false')
                        ->orderBy('t.name', 'ASC');
                },
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Customer::class,
        ]);
    }
}
