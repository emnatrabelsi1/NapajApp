<?php

namespace App\Controller;

use App\Entity\SupplyOrder;
use App\Entity\SupplyOrderState;
use App\Form\SupplyOrderType;
use App\Repository\SupplyOrderRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;


#[Route('/supply/order')]
#[IsGranted('ROLE_USER')]
class SupplyOrderController extends AbstractController
{
    public function isAllowed(){
        if(!$this->getUser()->getCompany()->isNapaj()){
            throw $this->createAccessDeniedException('Section non autorisée');;
        }
    }

    #[Route('/', name: 'app_supply_order_index', methods: ['GET'])]
    public function index(SupplyOrderRepository $supplyOrderRepository): Response
    {
        $this->isAllowed();
        return $this->render('supply_order/index.html.twig', [
            'supply_orders' => $supplyOrderRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_supply_order_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $this->isAllowed();
        $supplyOrder = new SupplyOrder();
        $supplyOrder->setCreatedAt(new DateTime());
        $sopR = $entityManager->getRepository(SupplyOrderState::class);
        $supplyOrder->setState($sopR->findBy(['code' => 'pending'])[0]);
        $supplyOrder->setUser($this->getUser());
        $supplyOrder->setCompany($this->getUser()->getCompany());

        $form = $this->createForm(SupplyOrderType::class, $supplyOrder);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($supplyOrder);
            $entityManager->flush();

            return $this->redirectToRoute('app_supply_order_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('supply_order/new.html.twig', [
            'supply_order' => $supplyOrder,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_supply_order_show', methods: ['GET'])]
    public function show(SupplyOrder $supplyOrder): Response
    {
        $this->isAllowed();
        return $this->render('supply_order/show.html.twig', [
            'supply_order' => $supplyOrder,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_supply_order_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, SupplyOrder $supplyOrder, EntityManagerInterface $entityManager): Response
    {
        $this->isAllowed();
        $form = $this->createForm(SupplyOrderType::class, $supplyOrder);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_supply_order_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('supply_order/edit.html.twig', [
            'supply_order' => $supplyOrder,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_supply_order_delete', methods: ['POST'])]
    public function delete(Request $request, SupplyOrder $supplyOrder, EntityManagerInterface $entityManager): Response
    {
        $this->isAllowed();
        if ($this->isCsrfTokenValid('delete'.$supplyOrder->getId(), $request->request->get('_token'))) {
            $entityManager->remove($supplyOrder);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_supply_order_index', [], Response::HTTP_SEE_OTHER);
    }
}
