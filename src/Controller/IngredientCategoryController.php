<?php

namespace App\Controller;

use App\Entity\IngredientCategory;
use App\Form\IngredientCategoryType;
use App\Repository\IngredientCategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
#[IsGranted('ROLE_ADMIN')]
#[Route('/ingredient/category')]
class IngredientCategoryController extends AbstractController
{
    #[Route('/', name: 'app_ingredient_category_index', methods: ['GET'])]
    public function index(IngredientCategoryRepository $ingredientCategoryRepository): Response
    {
        return $this->render('ingredient_category/index.html.twig', [
            'ingredient_categories' => $ingredientCategoryRepository->findBY([],['name'=>'ASC']),
        ]);
    }

    #[Route('/new', name: 'app_ingredient_category_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $ingredientCategory = new IngredientCategory();
        $form = $this->createForm(IngredientCategoryType::class, $ingredientCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($ingredientCategory);
            $entityManager->flush();

            return $this->redirectToRoute('app_ingredient_category_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('ingredient_category/new.html.twig', [
            'ingredient_category' => $ingredientCategory,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_ingredient_category_show', methods: ['GET'])]
    public function show(IngredientCategory $ingredientCategory): Response
    {
        return $this->render('ingredient_category/show.html.twig', [
            'ingredient_category' => $ingredientCategory,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_ingredient_category_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, IngredientCategory $ingredientCategory, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(IngredientCategoryType::class, $ingredientCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_ingredient_category_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('ingredient_category/edit.html.twig', [
            'ingredient_category' => $ingredientCategory,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_ingredient_category_delete', methods: ['POST'])]
    public function delete(Request $request, IngredientCategory $ingredientCategory, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$ingredientCategory->getId(), $request->request->get('_token'))) {
            $entityManager->remove($ingredientCategory);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_ingredient_category_index', [], Response::HTTP_SEE_OTHER);
    }
}
