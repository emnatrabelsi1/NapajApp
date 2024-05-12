<?php

namespace App\Form;

use App\Entity\Company;
use App\Entity\Recipe;
use App\Entity\RecipeComposition;
use App\Entity\RecipeCutting;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RecipeType extends AbstractType
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
                ]
            ])
            ->add('name',TextType::class, ['label'=> 'Nom'])
            ->add('process', TextareaType::class, [
                'row_attr' => [
                    'class' => 'text-editor'
                ],
                'attr' => [
                    'rows' => 9
                ],
                'required' => false,
                'label' => 'Étapes de fabrication'
            ])
            ->add('description', TextareaType::class, [
                'label'=> 'Description',
                'required' => false,
                'attr' => [
                    'rows' => 2
                ]
                ])
            ->add('code')
            ->add('is_frame', CheckboxType::class,[
                'label' => 'En cadre',
                'required' => false
            ])
            ->add('recipeCompositions', CollectionType::class, [
                'entry_type' => RecipeCompositionType::class,
                'label' => false,
                'allow_add' => true,
                'allow_delete' => true,
                'delete_empty' => true,
            ])
            ->add('recipeCuttings', CollectionType::class, [
                'entry_type' => RecipeCuttingType::class,
                'label' => false,
                'allow_add' => true,
                'allow_delete' => true,
                'delete_empty' => true,
            ])
            ->add('selling_price',NumberType::class,[
                'required' => false,
                'label' => 'Prix de vente (€)',
                'attr' => [
                    'placeholder' => '',
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Recipe::class,
        ]);
    }
}
