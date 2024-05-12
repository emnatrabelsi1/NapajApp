<?php

namespace App\Controller;

use App\Entity\Preparation;
use App\Entity\Recipe;
use App\Form\RecipeType;
use App\Repository\PreparationRepository;
use App\Repository\RecipeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/recipe')]
#[IsGranted('ROLE_USER')]
class RecipeController extends AbstractController
{
    public function isAllowed(){
        if(!$this->getUser()->getCompany()->isNapaj() && !$this->getUser()->getCompany()->isFeatureRecipe()){
            throw $this->createAccessDeniedException('Section non autorisée');;
        }
    }
    
    #[Route('/', name: 'app_recipe_index', methods: ['GET'])]
    public function index(RecipeRepository $recipeRepository, PreparationRepository $preparationRepository): Response
    {
        $this->isAllowed();
        $company = $this->getUser()->getCompany();
        $recipeCosts = [];
        $recipes = $recipeRepository->findAllOfCompany($company);
        foreach($recipes as $recipe){
            $recipesCostOld = 0;
            $recipesCost = 0;
            foreach($recipe->getRecipeCompositions() as $rc){
                $cost = $rc->getCost();
                $recipesCost += $cost;
            }
            $recipeCosts[$recipe->getId()] = $recipesCost;
        }

        return $this->render('recipe/index.html.twig', [
            'recipes' => $recipes,
            'recipeCosts' => $recipeCosts
        ]);
    }

    #[Route('/new', name: 'app_recipe_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $this->isAllowed();
        
        $recipe = new Recipe();
        $recipe->setCompany($this->getUser()->getCompany());
 
        $form = $this->createForm(RecipeType::class, $recipe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($recipe);
            foreach( $recipe->getRecipeCompositions() as $recipeComposition){
                $recipeComposition->setRecipe($recipe);
            }

            foreach( $recipe->getRecipeCuttings() as $recipeCutting){
                $recipeCutting->setRecipe($recipe);
            }

            $entityManager->flush();

            return $this->redirectToRoute('app_recipe_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('recipe/new.html.twig', [
            'recipe' => $recipe,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_recipe_show', methods: ['GET'])]
    public function show(Recipe $recipe): Response
    {
        $company = $this->getUser()->getCompany();
        $this->isAllowed();
        if($company !== $recipe->getCompany()){
            throw $this->createAccessDeniedException('Section non autorisée');;
        }
        
        return $this->render('recipe/show.html.twig', [
            'recipe' => $recipe,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_recipe_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Recipe $recipe, EntityManagerInterface $entityManager): Response
    {
        $company = $this->getUser()->getCompany();
        $this->isAllowed();
        if($company !== $recipe->getCompany()){
            throw $this->createAccessDeniedException('Section non autorisée');;
        }
        
        $form = $this->createForm(RecipeType::class, $recipe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            foreach( $recipe->getRecipeCompositions() as $recipeComposition){
                $recipeComposition->setRecipe($recipe);
            }
            foreach( $recipe->getRecipeCuttings() as $recipeCutting){
                $recipeCutting->setRecipe($recipe);
            }
            $entityManager->flush();

            return $this->redirectToRoute('app_recipe_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('recipe/edit.html.twig', [
            'recipe' => $recipe,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_recipe_delete', methods: ['POST'])]
    public function delete(Request $request, Recipe $recipe, EntityManagerInterface $entityManager): Response
    {
        $this->isAllowed();
        
        if ($this->isCsrfTokenValid('delete'.$recipe->getId(), $request->request->get('_token'))) {
            $entityManager->remove($recipe);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_recipe_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/duplicate', name: 'app_recipe_duplicate', methods: ['GET'])]
    public function duplicate(Recipe $recipe, EntityManagerInterface $entityManager): Response
    {
        $this->isAllowed();
        
        $newRecipe = clone $recipe;
        $entityManager->persist($newRecipe); 
        $newRecipe->setCode($newRecipe->getCode().'_CLONE');
        foreach($recipe->getRecipeCompositions() as $composition){
            $newComposition = clone $composition;
            $newComposition->setRecipe($newRecipe);
            $entityManager->persist($newComposition); 
        }

        foreach($recipe->getRecipeCuttings() as $cutting){
            $newCutting = clone $cutting;
            $newCutting->setRecipe($newRecipe);
            $entityManager->persist($newCutting); 
        }

        $entityManager->flush();

        return $this->redirectToRoute('app_recipe_edit', ['id' => $newRecipe->getId()], Response::HTTP_SEE_OTHER);
    }
}
