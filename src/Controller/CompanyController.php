<?php

namespace App\Controller;

use App\Entity\Company;
use App\Entity\Ingredient;
use App\Entity\SupplierPrice;
use App\Entity\Preparation;
use App\Entity\PreparationIngredient;
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

#[Route('/company')]
#[IsGranted('ROLE_ADMIN')]
class CompanyController extends AbstractController
{

    #[Route('/', name: 'app_company_index', methods: ['GET'])]
    public function index(CompanyRepository $companyRepository, EntityManagerInterface $em): Response
    {
        $em->getFilters()->disable('company');
        return $this->render('company/index.html.twig', [
            'companies' => $companyRepository->findBy([],['name' => 'ASC']),
        ]);
    }

    #[Route('/new', name: 'app_company_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $company = new Company();
        $company->setIsNapaj(false);
        $form = $this->createForm(CompanyType::class, $company);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($company);
            $em->flush();

            return $this->redirectToRoute('app_company_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('company/new.html.twig', [
            'company' => $company,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_company_show', methods: ['GET'])]
    public function show(Company $company, EntityManagerInterface $em): Response
    {
        $em->getFilters()->disable('company');
        return $this->render('company/show.html.twig', [
            'company' => $company,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_company_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Company $company, EntityManagerInterface $em, IngredientRepository $ingredientRepository): Response
    {
        $em->getFilters()->disable('company');
        $form = $this->createForm(CompanyType::class, $company);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            return $this->redirectToRoute('app_company_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('company/edit.html.twig', [
            'company' => $company,
            'form' => $form,
            'ingredientCount' => $ingredientRepository->nbForCompanny($company)[1]
        ]);
    }

    #[Route('/{id}', name: 'app_company_delete', methods: ['POST'])]
    public function delete(Request $request, Company $company, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete'.$company->getId(), $request->request->get('_token'))) {
            $em->remove($company);
            $em->flush();
        }

        return $this->redirectToRoute('app_company_index', [], Response::HTTP_SEE_OTHER);
    }
}
