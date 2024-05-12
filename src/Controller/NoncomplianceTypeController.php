<?php

namespace App\Controller;

use App\Entity\NoncomplianceType;
use App\Form\NoncomplianceTypeType;
use App\Repository\NoncomplianceTypeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/noncompliance/type')]
class NoncomplianceTypeController extends AbstractController
{
    
    #[Route('/new', name: 'app_noncompliance_type_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $noncomplianceType = new NoncomplianceType();
        $form = $this->createForm(NoncomplianceTypeType::class, $noncomplianceType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($noncomplianceType);
            $entityManager->flush();

            return $this->redirectToRoute('app_noncompliance_new', ['noncompliance_type_id' => $noncomplianceType->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('noncompliance_type/new.html.twig', [
            'noncompliance_type' => $noncomplianceType,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_noncompliance_type_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, NoncomplianceType $noncomplianceType, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(NoncomplianceTypeType::class, $noncomplianceType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_noncompliance_type_edit', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('noncompliance_type/edit.html.twig', [
            'noncompliance_type' => $noncomplianceType,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_noncompliance_type_delete', methods: ['POST'])]
    public function delete(Request $request, NoncomplianceType $noncomplianceType, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$noncomplianceType->getId(), $request->request->get('_token'))) {
            $entityManager->remove($noncomplianceType);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_noncompliance_type_new', [], Response::HTTP_SEE_OTHER);
    }
}
