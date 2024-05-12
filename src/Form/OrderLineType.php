<?php

namespace App\Form;

use App\Entity\OrderLine;
use App\Entity\Product;
use App\Repository\ProductRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderLineType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('product', EntityType::class,[
                'label' => 'Produit',
                'class' => Product::class,
                'placeholder' => 'Selectionner un produit',
                'query_builder' => function (ProductRepository $r) use ($options): QueryBuilder {
                    $qb = $r->createQueryBuilder('p');

                    if ($options['isNapaj'] == false){
                        $qb->innerJoin('p.tags', 't')
                        ->leftJoin('t.customers', 'c')
                        ->where('c.id = :customerId or t.public = true')
                        ->setParameter('customerId', $options['customerId'])
                        ->orderBy('t.public ASC, p.name', 'ASC');
                    }else{
                        $qb = $qb->orderBy('p.name', 'ASC');
                    }
                    return $qb;
                },
                'choice_label' => function (Product $product): string {
                    return $product->getFullName();
                },
                'choice_attr'=> function ($choice, string $key, mixed $value) {
                    return [
                        'data-img' => $choice->getImageName(),
                        'data-name' => $choice->getName(),
                        'data-piece_quantity' => $choice->getPieceQuantity(),
                        'data-piece_price' => $choice->getPiecePrice(),
                        'data-description' => ($choice->getRecipe() ? $choice->getRecipe()->getDescription() : ''),
                    ];
                },
                'required' => false,
                'attr' => [
                    'class' => 'productOrderLine'
                ]
            ])
            ->add('quantity', IntegerType::class,[
                'label' =>  'Quantité',
                'required' => true,
                'attr' => [
                    'placeholder' => 'Qté',

                ]
            ])
            ->add('details', TextType::class,[
                'label' =>  'Précisions',
                'required' => false,
                "attr" => [
                    'placeholder' => 'Précisions',
                    'class' => 'precisionOrderLine'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => OrderLine::class,
            'isNapaj' => false,
            'customerId' => null
        ]);
    }
}
