<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\OrderLine;
use App\Form\OrderType;
use App\Repository\OrderRepository;
use App\Repository\CustomerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/order')]
#[IsGranted('ROLE_USER')]
class OrderController extends AbstractController
{
    public function isAllowed(){
        if(!$this->getUser()->getCompany()->isNapaj() && !$this->getUser()->getCompany()->isFeatureOrder()){
            throw $this->createAccessDeniedException('Section non autorisée');;
        }
    }
    
    public function isAllowedForNapaj(){
        if(!$this->getUser()->getCompany()->isNapaj()){
            throw $this->createAccessDeniedException('Section non autorisée');;
        }
    }

    #[Route('/', name: 'app_order_index', methods: ['GET'])]
    public function index(OrderRepository $orderRepository, CustomerRepository $customerRepository): Response
    {
        $this->isAllowed();
        if($this->getUser()->getCompany()->isNapaj()){
            return $this->render('order/index.html.twig', [
                'orders' => $orderRepository->findAllWithInternal(false),
            ]);
        }else{
            return $this->render('order/index_for_customer.html.twig', [
                'orders' => $orderRepository->findBy([],['delivery_date' => 'ASC'])
            ]);
        }
    }

    #[Route('/new', name: 'app_order_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $this->isAllowed();

        $userCompany = $this->getUser()->getCompany();
        $isNapaj = $userCompany->isNapaj();
        if(!$isNapaj && !$userCompany->isFeatureOrder()){
            throw $this->createAccessDeniedException('Section non autorisée');;
        }
        $order = new Order();
        $order->setCompany($userCompany);
        if (!$isNapaj){
            $order->setCustomer($userCompany->getRelativeCustomer());
        }
        $form = $this->createForm(OrderType::class, $order, ['isNapaj'=>$isNapaj]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $order->setArchived(false);
            $order->setDone(false);
            $entityManager->persist($order);
            foreach( $order->getOrderLines() as $orderLine){
                $orderLine->setOrder($order);
                $orderLine->setDone(false);
            }
            $entityManager->flush();

            return $this->redirectToRoute('app_order_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('order/new.html.twig', [
            'order' => $order,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_order_show', methods: ['GET'])]
    public function show(Order $order): Response
    {
        $this->isAllowed();

        if(!$this->getUser()->getCompany()->isNapaj() && !$this->getUser()->getCompany()->isFeatureOrder()){
            throw $this->createAccessDeniedException('Section non autorisée');;
        }
        return $this->render('order/show.html.twig', [
            'order' => $order,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_order_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Order $order, EntityManagerInterface $entityManager): Response
    {
        $this->isAllowed();

        $userCompany = $this->getUser()->getCompany();
        $isNapaj = $userCompany->isNapaj();
        if(!$this->getUser()->getCompany()->isNapaj() && !$this->getUser()->getCompany()->isFeatureOrder()){
            throw $this->createAccessDeniedException('Section non autorisée');;
        }
        $form = $this->createForm(OrderType::class, $order,['isNapaj'=>$isNapaj]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            foreach( $order->getOrderLines() as $orderLine){
                $orderLine->setOrder($order);
                if (null == $orderLine->isDone()){
                    $orderLine->setDone(false);
                }
            }
            $entityManager->flush();

            return $this->redirectToRoute('app_order_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('order/edit.html.twig', [
            'order' => $order,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_order_delete', methods: ['POST'])]
    public function delete(Request $request, Order $order, EntityManagerInterface $entityManager): Response
    {
        $this->isAllowedForNapaj();

        if(!$this->getUser()->getCompany()->isNapaj()){
            throw $this->createAccessDeniedException('Section non autorisée');;
        }
        if ($this->isCsrfTokenValid('delete'.$order->getId(), $request->request->get('_token'))) {
            $entityManager->remove($order);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_order_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/archive/index', name: 'app_order_archive_index', methods: ['GET'])]
    public function archiveIndex(OrderRepository $orderRepository): Response
    {
        $this->isAllowedForNapaj();

        if(!$this->getUser()->getCompany()->isNapaj() && !$this->getUser()->getCompany()->isFeatureOrder()){
            throw $this->createAccessDeniedException('Section non autorisée');;
        }
        return $this->render('order/archive.html.twig', [
            'orders' => $orderRepository->findBy(['archived' => true],['delivery_date' => 'ASC']),
        ]);
    }

    #[Route('/archive/{id}', name: 'app_order_archive', methods: ['POST'])]
    public function archive(Request $request, Order $order, EntityManagerInterface $entityManager): Response
    {  
        $this->isAllowedForNapaj();

        if ($request->isXmlHttpRequest()){
            if(null !== $order){
                $order->setArchived(true);
                $entityManager->flush();
                return new Response("OK");
            }else{
                return new Response("Commande non trouvée");
            }
        }else {
            throw $this->createAccessDeniedException('Section non autorisée');;
        }
    }

    #[Route('/unarchive/{id}', name: 'app_order_unarchive', methods: ['POST'])]
    public function unArchive(Request $request,Order $order, EntityManagerInterface $entityManager): Response
    {  
        $this->isAllowedForNapaj();

        if ($request->isXmlHttpRequest()){
            if(null !== $order){
                $order->setArchived(false);
                $entityManager->flush();
                return new Response("OK");
            }else{
                return new Response("Commande non trouvée");
            }
        }else {
            throw $this->createAccessDeniedException('Section non autorisée');;
        }
    }


    #[Route('/order_line_do/{id}', name: 'app_order_line_do', methods: ['POST'])]
    public function doOrderLine(Request $request, OrderLine $orderLine, EntityManagerInterface $entityManager): Response
    {  
        $this->isAllowedForNapaj();

        $done = ($request->request->get('done') == 'true');
        if ($request->isXmlHttpRequest()){
            if(null !== $orderLine){
                $orderLine->setDone($done);
                $entityManager->flush();
                return new Response("OK");
            }else{
                return new Response("Commande non trouvée");
            }
        }else {
            throw $this->createAccessDeniedException('Section non autorisée');;
        }
    }
}
