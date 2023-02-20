<?php

namespace App\Controller;

use App\Repository\ProductsRepository;
use App\Repository\DisponibilitesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductsController extends AbstractController
{
    private ProductsRepository $productsRepo;
    private DisponibilitesRepository $dispoRepo;

    public function __construct(ProductsRepository $productsRepo, DisponibilitesRepository $dispoRepo)
    {
        $this->productsRepo = $productsRepo;
        $this->dispoRepo = $dispoRepo;
    }

    #[Route('/products', name: 'app_products')]
    public function index(): Response
    {
        return $this->render('products/index.html.twig', [
            'controller_name' => 'ProductsController',
        ]);
    }
}