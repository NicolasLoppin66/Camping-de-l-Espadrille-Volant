<?php

namespace App\Controller;

use App\Entity\Clients;
use App\Form\ClientType;
use App\Repository\ClientsRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ClientController extends AbstractController
{
    private ClientsRepository $clientRepo;

    public function __construct(ClientsRepository $clientRepo)
    {
        $this->clientRepo = $clientRepo;
    }

    /**
     * Affiche la liste des clients
     */
    #[Route('/clients', name: 'app_client')]
    public function clientsList(): Response
    {
        $clients = $this->clientRepo->findAll();
        return $this->render('admin/clients_list.html.twig', [
            'clients' => $clients,
        ]);
    }

    /**
     * Donne le détail client
     */
    #[Route('/client_detail/{id}', name: 'client_detail', requirements: ['id' => '\d+'])]
    public function clientDetail($id): Response
    {
        $client = $this->clientRepo->find($id);

        return $this->render('admin/client_detail.html.twig', [
            'client' => $client
        ]);
    }
}
