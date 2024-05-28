<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class GalleryController extends AbstractController
{
    #[Route('/gallery', name: 'gallery')]
    #[IsGranted('ROLE_USER')]
    #[IsGranted('ROLE_ADMIN')]
    public function index(): Response
    {
        return $this->render('gallery/index.html.twig', [
            'controller_name' => 'GalleryController',
        ]);
    }
}
