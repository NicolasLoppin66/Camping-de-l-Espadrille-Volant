<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class Middleware extends AbstractController
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    #[Route('/middleware', name: 'middleware')]
    public function index(): Response
    {
        $user = $this->security->getUser();

        if ($user->getRoles() === 'ROLE_ADMIN') {
            $render = $this->redirectToRoute('bookingsList', []);
        }
        else if ($user->getRoles() === 'ROLE_OWNER') {
            $render = $this->redirectToRoute('bookings_owner', ['id' => $user->eraseCredentials()]);
        }
        else {
            dump('mais sa marche pas');
            die;
        }

        return $render;
    }
}
