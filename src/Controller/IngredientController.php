<?php

namespace App\Controller;

use App\Entity\Ingredient;
use App\Form\IngredientType;
use App\Repository\IngredientRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use DateTime;

#[Route('/ingredient')]
#[IsGranted('ROLE_USER')]
class IngredientController extends AbstractController
{
    public function isAllowed(){
        if(!$this->getUser()->getCompany()->isNapaj() && !$this->getUser()->getCompany()->isFeatureRecipe()){
            throw $this->createAccessDeniedException('Section non autorisée');;
        }
    }

    #[Route('/', name: 'app_ingredient_index', methods: ['GET'])]
    public function index(IngredientRepository $ingredientRepository): Response
    {
        $this->isAllowed();

        return $this->render('ingredient/index.html.twig', [
            'ingredients' => $ingredientRepository->findAllOrderByCode(),
        ]);
    }

    #[Route('/new', name: 'app_ingredient_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $this->isAllowed();

        $ingredient = new Ingredient();
        $company = $this->getUser()->getCompany();
        $ingredient->setCompany($company);

        $form = $this->createForm(IngredientType::class, $ingredient);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($ingredient);
            foreach( $ingredient->getSupplierPrices() as $supplierPrice){
                $supplierPrice->setIngredient($ingredient);
                $supplierPrice->setCompany($company);
            }
            $entityManager->flush();

            return $this->redirectToRoute('app_ingredient_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('ingredient/new.html.twig', [
            'ingredient' => $ingredient,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_ingredient_show', methods: ['GET'])]
    public function show(Ingredient $ingredient): Response
    {
        $this->isAllowed();
        return $this->render('ingredient/show.html.twig', [
            'ingredient' => $ingredient,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_ingredient_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Ingredient $ingredient, EntityManagerInterface $entityManager): Response
    {
        $this->isAllowed();
        $company = $this->getUser()->getCompany();
        
        $originalSupplierPrices = new ArrayCollection();
        foreach ($ingredient->getSupplierPrices() as $supplierPrice) {
            $originalSupplierPrices->add($supplierPrice);
        }
        
        $form = $this->createForm(IngredientType::class, $ingredient);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            foreach ($originalSupplierPrices as $supplierPrice) {
                if (false === $ingredient->getSupplierPrices()->contains($supplierPrice)) {
                    $entityManager->remove($supplierPrice);
                }
            }
            $entityManager->persist($ingredient);
            
            foreach( $ingredient->getSupplierPrices() as $supplierPrice){
                $supplierPrice->setIngredient($ingredient);
                $supplierPrice->setCompany($company);
            }
            $entityManager->flush();

            return $this->redirectToRoute('app_ingredient_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('ingredient/edit.html.twig', [
            'ingredient' => $ingredient,
            'form' => $form,
        ]);
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/{id}/delete', name: 'app_ingredient_delete', methods: ['POST'])]
    public function delete(Request $request, Ingredient $ingredient, EntityManagerInterface $entityManager): Response
    {
        $this->isAllowed();
        $ingredient->setDeletedAt(new DateTime());
        $entityManager->persist($ingredient);    
        $entityManager->flush();

        if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest'){
            $response = new Response();
            $response->headers->set('Content-Type', 'application/json');
            return $response;
        }else{
            return $this->redirectToRoute('app_ingredient_index', [], Response::HTTP_SEE_OTHER);
        }
    }
}
