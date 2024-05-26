<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ThumbnailRepository;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(ThumbnailRepository $thumbnailRepository): Response
    {
        $thumbnails = $thumbnailRepository->findAll();
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'thumbnails'      => $thumbnails,
        ]);
    }
}
