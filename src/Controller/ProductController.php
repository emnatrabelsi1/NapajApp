<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use App\Repository\TagRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
#[IsGranted('ROLE_USER')]
#[Route('/product')]
class ProductController extends AbstractController
{
    #[Route('/data/table', name: 'app_customer_data_table', methods: ['GET'])]
    public function dataTable(Request $request, ProductRepository $productRepository): Response
    {
        $data = [];
        $parameters = $request->query->all();
        $products = $productRepository->findByCriteria([
            'tags' => isset($parameters['tags']) ? $parameters['tags'] : null, 
            'out_of_stock' => isset($parameters['out_of_stock']) ? $parameters['out_of_stock'] : null,
            'company_id' => $this->getUser()->getCompany()->getId()
        ]);

        foreach($products as $product){
            $tags = [];
            foreach($product->getTags() as $tag){
                $tags[] =['id'=> $tag->getId(), 'name'=> $tag->getName()];
            }
            $data[] = [
                'id'=> $product->getId(), 
                'img'=> $product->getImageName(), 
                'name'=>[
                    'name' => $product->getName(), 
                    'url'=>$this->generateUrl('app_product_edit', ['id'=> $product->getId()]),
                    'quantity' => $product->getPieceQuantity()
                ],
                'tag' => $tags,
                'recipe' => [
                    'url'=> $product->getRecipe() ? $this->generateUrl('app_recipe_edit', ['id'=> $product->getRecipe()->getId()]) : '',
                    'recipeName' => $product->getRecipe() ? $product->getRecipe()->getName() : ''
                ],
                'price'=>$product->getPiecePrice(),
                'stock'=>[
                    'value' => $product->getStock(),
                    'minimum' => $product->getMinimumStock()
                ],
                'etiquette'=> [
                    'url'=>$this->generateUrl('app_product_label', ['id'=> $product->getId()]),
                ]
            ];
        }
        $response = new Response();
        $response->setContent(json_encode(['data' => $data]));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }
    
    public function isAllowed(){
        if(!$this->getUser()->getCompany()->isNapaj() && !$this->getUser()->getCompany()->isFeatureProduct()){
            throw $this->createAccessDeniedException('Section non autorisée');;
        }
    }

    #[Route('/', name: 'app_product_index', methods: ['GET'])]
    public function index(ProductRepository $productRepository, TagRepository $tagRepository): Response
    {
        $this->isAllowed();

        return $this->render('product/index.html.twig', [
            'products'  => $productRepository->findBy([],['name' => 'ASC']),
            'tags'      => $tagRepository->findAll()
        ]);
    }

    #[Route('/new', name: 'app_product_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $this->isAllowed();
        $product = new Product();
        $product->setCompany($this->getUser()->getCompany());

        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($product);
            $entityManager->flush();

            return $this->redirectToRoute('app_product_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('product/new.html.twig', [
            'product' => $product,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_product_show', methods: ['GET'])]
    public function show(Product $product): Response
    {
        $this->isAllowed();
        
        return $this->render('product/show.html.twig', [
            'product' => $product,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_product_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Product $product, EntityManagerInterface $entityManager): Response
    {
        $this->isAllowed();
        
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_product_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('product/edit.html.twig', [
            'product' => $product,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_product_delete', methods: ['POST'])]
    public function delete(Request $request, Product $product, EntityManagerInterface $entityManager): Response
    {
        $this->isAllowed();
        
        if ($this->isCsrfTokenValid('delete'.$product->getId(), $request->request->get('_token'))) {
            $entityManager->remove($product);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_product_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/label', name: 'app_product_label', methods: ['GET'])]
    public function label(Request $request, Product $product): Response
    {
        $this->isAllowed();
        return $this->render('product/label.html.twig', [
            'product' => $product,
            'allergens' => $product->getAllergens(),
            'ingredients' => $product->getIngredients(),
        ]);
    }
}
