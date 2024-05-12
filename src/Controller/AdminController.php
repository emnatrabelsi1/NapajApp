<?php

namespace App\Controller;

use App\Entity\Company;
use App\Entity\Ingredient;
use App\Entity\SupplierPrice;
use App\Entity\Preparation;
use App\Entity\PreparationIngredient;
use App\Entity\Recipe;
use App\Entity\RecipeComposition;
use App\Form\CompanyType;
use DateTime;
use App\Repository\CompanyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use App\Repository\MeasureUnitRepository;
use App\Repository\IngredientCategoryRepository;
use App\Repository\SupplierRepository;
use App\Repository\IngredientRepository;
use App\Repository\AllergenRepository;
use App\Repository\RecipeRepository;
use App\Repository\PreparationRepository;

#[Route('/admin')]
#[IsGranted('ROLE_ADMIN')]
class AdminController extends AbstractController
{
    
    #[Route('/clear_ingredients_data/{id}', name: 'clear_ingredients_data', methods: ['GET', 'POST'])]
    public function clearIngredientsData(Request $request, Company $company, EntityManagerInterface $em, RecipeRepository $recipeRepository, PreparationRepository $preparationRepository, IngredientRepository $ingredientRepository): Response
    {
        $recipes = $recipeRepository->findAllOfCompany($company);
        $preparations = $preparationRepository->findAllOfCompany($company);
        $ingredients = $ingredientRepository->findAllOfCompany($company);

        foreach($recipes as $recipe){
            $recipe->setDeletedAt(new DateTime());
            $em->persist($recipe);        
        }
        foreach($preparations as $preparation){
            $preparation->setDeletedAt(new DateTime());
            $em->persist($preparation);        
        }
        foreach($ingredients as $ingredient){
            $ingredient->setDeletedAt(new DateTime());
            $em->persist($ingredient);        
        }
        $em->flush();
        $response = new Response();
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    #[Route('/generate_demo_data/{id}', name: 'generate_demo_data', methods: ['GET', 'POST'])]
    public function generateDemoData(Request $request, Company $company, EntityManagerInterface $em, MeasureUnitRepository $measureUnitRepository, IngredientCategoryRepository $ingredientCategoryRepository, SupplierRepository $supplierRepository, AllergenRepository $allergenRepository): Response
    {
        $unitKg = $measureUnitRepository->findBy(['name'=> 'Kg'])[0];
        $unitL = $measureUnitRepository->findBy(['name'=> 'L'])[0];
        $unitU = $measureUnitRepository->findBy(['name'=> 'Unité'])[0];

        $allergenOeuf = $allergenRepository->findBy(['name'=> 'Oeufs'])[0];
        $allergenLait = $allergenRepository->findBy(['name'=> 'Lait'])[0];
        $allergenGluten = $allergenRepository->findBy(['name'=> 'Gluten'])[0];

        $laitage = $ingredientCategoryRepository->findBy(['name'=> 'Laitage'])[0];
        $chocolat = $ingredientCategoryRepository->findBy(['name'=> 'Chocolat'])[0];
        $farine = $ingredientCategoryRepository->findBy(['name'=> 'Farine'])[0];
        $huile = $ingredientCategoryRepository->findBy(['name'=> 'Huile'])[0];
        $legume = $ingredientCategoryRepository->findBy(['name'=> 'Légumes'])[0];
        $viande = $ingredientCategoryRepository->findBy(['name'=> 'Viande'])[0];
        $fromage = $ingredientCategoryRepository->findBy(['name'=> 'Fromage'])[0];
        
        $supplier = $supplierRepository->findLikeName('inconnu')[0];

        $ingredientsData = [
            //Code, measure_unit_id, ingredient_category, allergen, name
            ["Beurre", $unitKg, $laitage, $allergenLait, 6.30],
            ["Beurre AOP", $unitKg, $laitage, $allergenLait, 11.20],
            ["Chocolat Noir", $unitKg, $chocolat, null, 10.40],
            ["Crème fraîche", $unitKg, $laitage, $allergenLait, 4.50],
            ["Farine de blé", $unitKg, $farine, $allergenGluten, 1.15],
            ["Fromage blanc", $unitKg, $laitage, $allergenLait, 3.55],
            ["Huile Tournesol", $unitL, $huile, null, 4.00],
            ["Lait demi écrémé", $unitL, $laitage, $allergenLait, 0.69],
            ["Miel", $unitKg, null, null, 16],
            ["Oeuf", $unitU, null, $allergenOeuf, 0.32],
            ["Sucre", $unitKg, null, null, 1.20],
            ["Pomme de terre", $unitKg, $legume, null, 1.80],
            ["Pain à burger", $unitU, null, $allergenGluten, 1.65],
            ["Boeuf haché", $unitKg, $viande, null, 14.60],
            ["Gésiers poulet confit", $unitKg, $viande, null, 12.79],
            ["Oignons", $unitKg, $legume, null, 1.80],
            ["Tomme Limousine", $unitKg, $fromage, $allergenLait, 19.30],
            ["Salade batavia", $unitKg, $legume, null, 7.30],
            ["Tomate", $unitKg, $legume, null, 3.50],
            ["Moutarde", $unitKg, null, null, 8.15],
            ["PDT", $unitKg, null, null, 1.20],
            ["Ail (au Kg)", $unitKg, null, null, 10.48],
            ["Graisse de canard", $unitKg, null, null, 14.90],
            ["Persil", $unitKg, null, null, 15],
        ];

        foreach($ingredientsData as $ingredientData){
            $ingredient = new Ingredient();
            $ingredient->setCode($ingredientData[0])
            ->setMeasureUnit($ingredientData[1])
            ->setCompany($company)
            ->setIngredientCategory($ingredientData[2])
            ->setAllergen($ingredientData[3])
            ->setName($ingredientData[0])
            ->setIsDemo(true)
            ->setIsDisabled(false);

            $price = new SupplierPrice();
            $price->setSupplier($supplier)
            ->setIngredient($ingredient)
            ->setPrice($ingredientData[4])
            ->setName($ingredientData[0])
            ->setCompany($company)
            ->setCreatedAt(new DateTime());
            $ingredient->addSupplierPrice($price);
            switch ($ingredientData[0]){
                case "Moutarde":
                    $moutarde = $ingredient;
                    break;
                case "Huile Tournesol":
                    $huile = $ingredient;
                    break;
                case "Oeuf":
                    $oeuf = $ingredient;
                    break;
                case "Pain à burger":
                    $painBurger = $ingredient;
                    break;
                case "Tomme Limousine":
                    $tomme = $ingredient;
                    break;
                case "Salade batavia":
                    $salade = $ingredient;
                    break;
                case "Tomate":
                    $tomate = $ingredient;
                    break;
                case "Boeuf haché":
                    $boeuf = $ingredient;
                    break;
                case "Oignons":
                    $oignon = $ingredient;
                    break;
                case "PDT":
                    $pdt = $ingredient;
                    break;
                case "Ail (au Kg)":
                    $ail = $ingredient;
                    break;
                case "Graisse de canard":
                    $graisse = $ingredient;
                    break;
                case "Persil":
                    $persil = $ingredient;
                    break;                   
            }

            $em->persist($ingredient);
            $em->flush();
        }
        
        /*========== MAYONNAISE ============= */
        $preparationMayo = new Preparation();
        $preparationMayo->setName('Mayonnaise')
        ->setWeight(600)
        ->setCompany($company);

        $preparationIngredientMoutarde = new PreparationIngredient();
        $preparationIngredientMoutarde->setPreparation($preparationMayo)
        ->setIngredient($moutarde)
        ->setWeight(50);
        $preparationMayo->addPreparationIngredient($preparationIngredientMoutarde);

        $preparationIngredientHuile = new PreparationIngredient();
        $preparationIngredientHuile->setPreparation($preparationMayo)
        ->setIngredient($huile)
        ->setVolume(0.50);
        $preparationMayo->addPreparationIngredient($preparationIngredientHuile);

        $preparationIngredientOeuf = new PreparationIngredient();
        $preparationIngredientOeuf->setPreparation($preparationMayo)
        ->setIngredient($oeuf)
        ->setQuantity(2);
        $preparationMayo->addPreparationIngredient($preparationIngredientOeuf);
        
        $em->persist($preparationMayo);

        /*========== Frites maison ============= */
        $preparationFrites = new Preparation();
        $preparationFrites->setName('Frites maison')
        ->setWeight(1000)
        ->setCompany($company);
        
        $preparationFritePdt = new PreparationIngredient();
        $preparationFritePdt->setPreparation($preparationFrites)
        ->setIngredient($pdt)
        ->setWeight(1000);
        $preparationFrites->addPreparationIngredient($preparationFritePdt);

        $em->persist($preparationFrites);

        /*========== PDT Sarladaise ============= */
        $preparationPdtSarla = new Preparation();
        $preparationPdtSarla->setName('PDT sarladaise')
        ->setWeight(1000)
        ->setCompany($company);

        $preparationIngredientPdt = new PreparationIngredient();
        $preparationIngredientPdt->setPreparation($preparationPdtSarla)
        ->setIngredient($pdt)
        ->setWeight(1000);
        $preparationPdtSarla->addPreparationIngredient($preparationIngredientPdt);

        $preparationIngredientAil = new PreparationIngredient();
        $preparationIngredientAil->setPreparation($preparationPdtSarla)
        ->setIngredient($ail)
        ->setWeight(15);
        $preparationPdtSarla->addPreparationIngredient($preparationIngredientAil);

        $preparationIngredientGraisse = new PreparationIngredient();
        $preparationIngredientGraisse->setPreparation($preparationPdtSarla)
        ->setIngredient($graisse)
        ->setWeight(100);
        $preparationPdtSarla->addPreparationIngredient($preparationIngredientGraisse);
        
        $preparationIngredientPersil = new PreparationIngredient();
        $preparationIngredientPersil->setPreparation($preparationPdtSarla)
        ->setIngredient($persil)
        ->setWeight(50);
        $preparationPdtSarla->addPreparationIngredient($preparationIngredientPersil);

        $em->persist($preparationPdtSarla);

        /*========== Burger ============= */
        $preparationBurger = new Preparation();
        $preparationBurger->setName('Burger Limousin')
        ->setCompany($company);

        $prepBMoutarde = new PreparationIngredient();
        $prepBMoutarde->setPreparation($preparationBurger)
        ->setIngredient($moutarde)
        ->setWeight(10);
        $preparationBurger->addPreparationIngredient($prepBMoutarde);

        $prepBOignons = new PreparationIngredient();
        $prepBOignons->setPreparation($preparationBurger)
        ->setIngredient($oignon)
        ->setWeight(80);
        $preparationBurger->addPreparationIngredient($prepBOignons);

        $prepBTomate = new PreparationIngredient();
        $prepBTomate->setPreparation($preparationBurger)
        ->setIngredient($tomate)
        ->setWeight(60);
        $preparationBurger->addPreparationIngredient($prepBTomate);

        $prepBTomme = new PreparationIngredient();
        $prepBTomme->setPreparation($preparationBurger)
        ->setIngredient($tomme)
        ->setWeight(100);
        $preparationBurger->addPreparationIngredient($prepBTomme);

        $prepBBoeuf= new PreparationIngredient();
        $prepBBoeuf->setPreparation($preparationBurger)
        ->setIngredient($boeuf)
        ->setWeight(120);
        $preparationBurger->addPreparationIngredient($prepBBoeuf);

        $prepBPain= new PreparationIngredient();
        $prepBPain->setPreparation($preparationBurger)
        ->setIngredient($painBurger)
        ->setQuantity(1);
        $preparationBurger->addPreparationIngredient($prepBPain);

        $em->persist($preparationBurger);

        /*========== Recette Burger/PDT ============= */
        $rec = new Recipe();
        $rec->setName("Burger Limousin / PDT sarladaise")
        ->setCompany($company)
        ->setCode("BURGER_LIM_P")
        ->setSellingPrice(17.00);
        
        $recCompB= new RecipeComposition();
        $recCompB->setRecipe($rec)
            ->setPreparation($preparationBurger)
            ->setQuantity(1);
        $em->persist($recCompB);

        $recCompP= new RecipeComposition();
        $recCompP->setRecipe($rec)
            ->setPreparation($preparationPdtSarla)
            ->setWeight(120);
        $em->persist($recCompP);
        $em->persist($rec);

         /*========== Recette Burger/Frite ============= */
         $rec2 = new Recipe();
         $rec2->setName("Burger Limousin / Frite maison")
         ->setCompany($company)
         ->setCode("BURGER_LIM_F")
         ->setSellingPrice(17.00);
         
         $recCompB= new RecipeComposition();
         $recCompB->setRecipe($rec2)
             ->setPreparation($preparationBurger)
             ->setQuantity(1);
         $em->persist($recCompB);
 
         $recCompP= new RecipeComposition();
         $recCompP->setRecipe($rec2)
             ->setPreparation($preparationFrites)
             ->setWeight(120);
         $em->persist($recCompP);
         $em->persist($rec2);

        $em->flush();

        $response = new Response();
        // $response->setContent(json_encode(['data' => $data]));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }
}
