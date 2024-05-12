<?php

namespace App\Controller;

use App\Entity\Noncompliance;
use App\Form\NoncomplianceType;
use App\Repository\NoncomplianceRepository;
use App\Repository\NoncomplianceStateRepository;
use App\Repository\NoncomplianceTypeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/noncompliance')]
#[IsGranted('ROLE_USER')]
class NoncomplianceController extends AbstractController
{
    public function isAllowed(){
        if(!$this->getUser()->getCompany()->isNapaj()){
            throw $this->createAccessDeniedException('Section non autorisée');;
        }
    }

    #[Route('/', name: 'app_noncompliance_index', methods: ['GET'])]
    public function index(NoncomplianceRepository $noncomplianceRepository): Response
    {
        $this->isAllowed();

        return $this->render('noncompliance/index.html.twig', [
            'noncompliances' => $noncomplianceRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_noncompliance_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, NoncomplianceStateRepository $noncomplianceStateRepository, NoncomplianceTypeRepository $noncomplianceTypeRepository): Response
    {
        $this->isAllowed();
        $noncompliance = new Noncompliance();
        if($request->query->get('noncompliance_type_id') != null){
            $noncompliance->setNoncomplianceType($noncomplianceTypeRepository->find($request->query->get('noncompliance_type_id')));
        }
        $noncompliance->setDeclarationDate(new \DateTime());
        $noncompliance->setDeclarant($this->getUser() ? $this->getUser()->getEmail() : "inconnu");

        $ncState =  $noncomplianceStateRepository->findBy(['code' => 'OPEN'])[0];
        $noncompliance->setNoncomplianceState($ncState);
        $form = $this->createForm(NoncomplianceType::class, $noncompliance, ['action' => 'new']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($noncompliance);
            $entityManager->flush();

            return $this->redirectToRoute('app_noncompliance_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('noncompliance/new.html.twig', [
            'noncompliance' => $noncompliance,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_noncompliance_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Noncompliance $noncompliance, EntityManagerInterface $entityManager): Response
    {
        $this->isAllowed();
        $form = $this->createForm(NoncomplianceType::class, $noncompliance, ['action' => 'edit']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_noncompliance_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('noncompliance/edit.html.twig', [
            'noncompliance' => $noncompliance,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_noncompliance_delete', methods: ['POST'])]
    public function delete(Request $request, Noncompliance $noncompliance, EntityManagerInterface $entityManager): Response
    {
        $this->isAllowed();
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        if ($this->isCsrfTokenValid('delete'.$noncompliance->getId(), $request->request->get('_token'))) {
            $entityManager->remove($noncompliance);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_noncompliance_index', [], Response::HTTP_SEE_OTHER);
    }
}
