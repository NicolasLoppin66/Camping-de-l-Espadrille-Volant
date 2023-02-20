<?php

namespace App\Controller;

use App\Entity\Disponibilites;
use App\Entity\Products;
use App\Form\ProductType;
use App\Repository\DisponibilitesRepository;
use App\Repository\ProductsRepository;
use Doctrine\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\FormTypeInterface;


class ProductsController extends AbstractController
{
    private ProductsRepository $productsRepo;
    private DisponibilitesRepository $dispoRepo;

    public function __construct(ProductsRepository $productsRepo, DisponibilitesRepository $dispoRepo)
    {
        $this->productsRepo = $productsRepo;
        $this->dispoRepo = $dispoRepo;
    }

    /**
     * Liste de tous les produits en accès public (mobile-home, emplacements et caravanes)
     */
    #[Route("/housings", name: "housings")]
    public function housings(): Response
    {
        $products = $this->productsRepo->findHousing();
        return $this->render('front/products.html.twig', [
            'products' => $products,
        ]);
    }

    /**
     * Liste de toutes les tentes en accès public
     */
    #[Route("/tentsites", name: "tentsites")]
    public function tentSites(): Response
    {
        $products = $this->productsRepo->findTentSites();
        return $this->render('front/tentsites.html.twig', [
            'products' => $products,
        ]);
    }

    /**
     * Liste de tous les produits côté admin
     */
    #[Route("/admin/productslist", name: "app_products")]
    public function listProducts()
    {
        $products = $this->productsRepo->findAll();

        return $this->render('products/list.html.twig', ['products' => $products]);
    }

    /**
     * Ajouter un nouveau produit
     */
    #[Route("/admin/addproduct/{id}", name: "add_product", requirements: ["id" => "\d+"], methods: ["POST", "GET"])]
    #[IsGranted("ROLE_ADMIN")]
    public function addProduct (Request $request, ManagerRegistry $manager, int $id = 0)
    {
        $product = $id > 0 ? $this->productsRepo->find($id) : new Products();

        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $em = $manager->getManager();
            $em->persist($product);
            $em->flush();

            $this->addFlash('success', 'Bravo ' . $product->getLabel() . ' est bien enregistré !');
            return $this->redirectToRoute('app_products');
        }
        else {
            return $this->render('products/editProduct.html.twig', ['form' => $form->createView()]);
        }
    }

    /**
     * Supprimer un produit
     */
    #[Route("/admin/deleteProduct/{id}", name: "delProduct", requirements: ["id" => "\d+"])]
    public function deleteProduct(Request $request, ManagerRegistry $manager, int $id)
    {
        if($this->isCsrfTokenValid('delete'.$id, $request->get('_token')))
        {
            $product = $this->productsRepo->find($id);
            $em = $manager->getManager();
            $em->remove($product);
            $em->flush();

            $this->addFlash('success', 'Bravo ' . $product->getLabel() . ' a bien été supprimé :D');
            return $this->redirectToRoute('app_products');
        }
        else
        {
            return new Response("<h1>C'est pas bien !!!</h1>");
        }
    }

    /**
     * Détail d'un produit en accès public
     */
    #[Route("/productDetails/{id}", name: "product_details", requirements: ["id" => "\d+"])]
    public function details($id)
    {
        $product = $this->productsRepo->find($id);
        $mai = $this->dispoRepo->allDatesForOneMonth($id, '2022-05-01', '2022-05-31');
        //dump($mai);
        return $this->render('admin/product_details.html.twig', ['product' => $product, 'mai' => $mai]);
    }

    /**
     * Gestion de la saison (ouverture, fermeture de la période) -> todo:à mettre dans seasonController
     */
    #[Route("/admin/openSeason/{id}", name: "open_season", requirements: ["id" => "\d+"])]
    public function openSeason(Request $request, ManagerRegistry $manager, int $id)
    {
        $current_date = new \DateTime();
        $year = $current_date->format('Y');

        $start = $year . '-05-05';
        $end = $year . '-10-11';
        $is_already_opened = $this->dispoRepo->allDispoForOneProduct($id, $start, $end);


        if(!empty($is_already_opened))
        {
            $this->addFlash('success', 'la saison est déjà ouverte pour ce logement');
            return $this->redirectToRoute('app_products');
        }

        if($this->isCsrfTokenValid('open'.$id, $request->get('_token')))
        {
            $product = $this->productsRepo->find($id);

            $season = $this->dispoRepo->daysBetween2Dates($start, $end);

            foreach ($season as $day)
            {
                $dispo = new Disponibilites();
                $dispo->setDay($day);
                $dispo->setProductId($product);
                $dispo->setIsBooked(false);

                $em = $manager->getManager();
                $em->persist($dispo);
                $em->flush();
            }


            $this->addFlash('success', 'Bravo !');
            return $this->redirectToRoute('app_products');
        }
        else
        {
            return new Response("<h1>C'est pas bien !!!</h1>");
        }
    }
}