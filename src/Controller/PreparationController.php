<?php

namespace App\Controller;

use App\Entity\Preparation;
use App\Form\PreparationType;
use App\Repository\PreparationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/preparation')]
#[IsGranted('ROLE_USER')]
class PreparationController extends AbstractController
{
    public function isAllowed(){
        if(!$this->getUser()->getCompany()->isNapaj() && !$this->getUser()->getCompany()->isFeatureRecipe()){
            throw $this->createAccessDeniedException('Section non autorisée');;
        }
    }

    #[Route('/', name: 'app_preparation_index', methods: ['GET'])]
    public function index(PreparationRepository $preparationRepository): Response
    {
        $this->isAllowed();
        $preparations= $preparationRepository->findAll();
       
        return $this->render('preparation/index.html.twig', [
            'preparations' => $preparations,
        ]);
    }

    #[Route('/new', name: 'app_preparation_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $this->isAllowed();

        $preparation = new Preparation();
        $preparation->setCompany($this->getUser()->getCompany());
        $form = $this->createForm(PreparationType::class, $preparation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($preparation);
            foreach( $preparation->getPreparationIngredients() as $preparationIngredient){
                $preparationIngredient->setPreparation($preparation);
            }
            $entityManager->flush();

            return $this->redirectToRoute('app_preparation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('preparation/new.html.twig', [
            'preparation' => $preparation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_preparation_show', methods: ['GET'])]
    public function show(Preparation $preparation): Response
    {
        $this->isAllowed();
        
        return $this->render('preparation/show.html.twig', [
            'preparation' => $preparation,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_preparation_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Preparation $preparation, EntityManagerInterface $entityManager): Response
    {
        $this->isAllowed();

        $originalIngredients = new ArrayCollection();
        foreach ($preparation->getPreparationIngredients() as $ingredient) {
            $originalIngredients->add($ingredient);
        }
        
        $form = $this->createForm(PreparationType::class, $preparation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            foreach ($originalIngredients as $ingredient) {
                if (false === $preparation->getPreparationIngredients()->contains($ingredient)) {
                    $entityManager->remove($ingredient);
                }
            }
            $entityManager->persist($preparation);
            
            foreach( $preparation->getPreparationIngredients() as $preparationIngredient){
                $preparationIngredient->setPreparation($preparation);
            }
            $entityManager->flush();

            return $this->redirectToRoute('app_preparation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('preparation/edit.html.twig', [
            'preparation' => $preparation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_preparation_delete', methods: ['POST'])]
    public function delete(Request $request, Preparation $preparation, EntityManagerInterface $entityManager): Response
    {
        $this->isAllowed();

        if($preparation->isDeletable()){
            if ($this->isCsrfTokenValid('delete'.$preparation->getId(), $request->request->get('_token'))) {
                $entityManager->remove($preparation);
                $entityManager->flush();
                $this->addFlash('success', 'Préparation supprimée');
            }
        }else{
            $this->addFlash('warning', 'La préparation ne peut pas être supprimée. Elle est utilisée dans une ou plusieurs recettes.');
        }
        
        return $this->redirectToRoute('app_preparation_index', [], Response::HTTP_SEE_OTHER);
    }
}
