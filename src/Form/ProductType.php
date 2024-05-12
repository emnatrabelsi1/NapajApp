<?php

namespace App\Form;

use App\Entity\Product;
use App\Entity\Recipe;
use App\Entity\Tag;
use App\Repository\RecipeRepository;
use App\Repository\TagRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom'
            ])
            ->add('imageFile', VichImageType::class, [
                'required' => false,
                'allow_delete' => true,
                'delete_label' => 'Supprimer',
                'download_uri' => false,
                'asset_helper' => false,
            ])
            ->add('piece_price', NumberType::class, [
                'label' => 'Prix HT/pièce (€)',
                'required' => false
            ])
            ->add('piece_quantity', NumberType::class, [
                'label' => 'Nb de pièces',
                'required' => false
            ])
            ->add('stock', NumberType::class, [
                'label' => 'Stock disponible',
                'required' => false
            ])
            ->add('minimum_stock', NumberType::class, [
                'label' => 'Stock minimal',
                'required' => false
            ])
            ->add('recipe',EntityType::class,[
                'label' => 'Recette',
                'class' => Recipe::class,
                'required' => false,
                'attr' => [
                    'class' => 'select2_autoload',                ],
                'query_builder' => function (RecipeRepository $r): QueryBuilder {
                    return $r->createQueryBuilder('r')
                        ->orderBy('r.name', 'ASC');
                },
            ])
            ->add('tags', EntityType::class,[
                'label' => 'Tags',
                'class' => Tag::class,
                'required' => false,
                'multiple' => true,
                'attr' => [
                    'class' => 'select2_autoload',                ],
                    'query_builder' => function (TagRepository $r): QueryBuilder {
                        return $r->createQueryBuilder('t')
                            ->orderBy('t.name', 'ASC');
                    },
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
