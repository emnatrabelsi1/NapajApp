<?php

namespace App\Controller;

use App\Entity\MeasureUnit;
use App\Form\MeasureUnitType;
use App\Repository\MeasureUnitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/measure/unit')]
#[IsGranted('ROLE_ADMIN')]
class MeasureUnitController extends AbstractController
{   
    #[Route('/', name: 'app_measure_unit_index', methods: ['GET'])]
    public function index(MeasureUnitRepository $measureUnitRepository): Response
    {
        return $this->render('measure_unit/index.html.twig', [
            'measure_units' => $measureUnitRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_measure_unit_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $measureUnit = new MeasureUnit();
        $form = $this->createForm(MeasureUnitType::class, $measureUnit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($measureUnit);
            $entityManager->flush();

            return $this->redirectToRoute('app_measure_unit_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('measure_unit/new.html.twig', [
            'measure_unit' => $measureUnit,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_measure_unit_show', methods: ['GET'])]
    public function show(MeasureUnit $measureUnit): Response
    {
        return $this->render('measure_unit/show.html.twig', [
            'measure_unit' => $measureUnit,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_measure_unit_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, MeasureUnit $measureUnit, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(MeasureUnitType::class, $measureUnit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_measure_unit_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('measure_unit/edit.html.twig', [
            'measure_unit' => $measureUnit,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_measure_unit_delete', methods: ['POST'])]
    public function delete(Request $request, MeasureUnit $measureUnit, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$measureUnit->getId(), $request->request->get('_token'))) {
            $entityManager->remove($measureUnit);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_measure_unit_index', [], Response::HTTP_SEE_OTHER);
    }
}
