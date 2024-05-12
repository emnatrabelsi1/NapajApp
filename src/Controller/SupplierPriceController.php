<?php

namespace App\Controller;

use App\Entity\SupplierPrice;
use App\Form\SupplierPriceType;
use App\Repository\IngredientRepository;
use App\Repository\SupplierPriceRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/supplier_price')]
#[IsGranted('ROLE_USER')]
class SupplierPriceController extends AbstractController
{
    public function isAllowed(){
        if(!$this->getUser()->getCompany()->isNapaj() && !$this->getUser()->getCompany()->isFeatureRecipe()){
            throw $this->createAccessDeniedException('Section non autorisée');;
        }
    }

    #[Route('/', name: 'app_supplier_price_index', methods: ['GET'])]
    public function index(SupplierPriceRepository $supplierPriceRepository): Response
    {
        $this->isAllowed();
        
        return $this->render('supplier_price/index.html.twig', [
            'supplier_prices' => $supplierPriceRepository->findAllOrderByCode(),
        ]);
    }

    #[Route('/new', name: 'app_supplier_price_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, IngredientRepository $ingredientRepository): Response
    {
        $this->isAllowed();

        $supplierPrice = new SupplierPrice();
        $supplierPrice->setCreatedAt(new DateTime());

        if($request->get('ingredient_id')){
            $ingredient = $ingredientRepository->find($request->get('ingredient_id'));
            $supplierPrice->setIngredient($ingredient);
            $supplierPrice->setName($ingredient->getCode());
            $supplierPrice->setCompany($this->getUser()->getCompany());
        }
        $form = $this->createForm(SupplierPriceType::class, $supplierPrice);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($supplierPrice);
            $entityManager->flush();

            return $this->redirectToRoute('app_supplier_price_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('supplier_price/new.html.twig', [
            'supplier_price' => $supplierPrice,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_supplier_price_show', methods: ['GET'])]
    public function show(SupplierPrice $supplierPrice): Response
    {
        $this->isAllowed();

        return $this->render('supplier_price/show.html.twig', [
            'supplier_price' => $supplierPrice,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_supplier_price_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, SupplierPrice $supplierPrice, EntityManagerInterface $entityManager): Response
    {
        $this->isAllowed();

        $form = $this->createForm(SupplierPriceType::class, $supplierPrice);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_supplier_price_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('supplier_price/edit.html.twig', [
            'supplier_price' => $supplierPrice,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_supplier_price_delete', methods: ['POST'])]
    public function delete(Request $request, SupplierPrice $supplierPrice, EntityManagerInterface $entityManager): Response
    {
        $this->isAllowed();
        
        if ($this->isCsrfTokenValid('delete'.$supplierPrice->getId(), $request->request->get('_token'))) {
            $entityManager->remove($supplierPrice);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_supplier_price_index', [], Response::HTTP_SEE_OTHER);
    }
}
