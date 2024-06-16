<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\SecurityBundle\Security;
use App\Repository\AlbumRepository;

class GalleryController extends AbstractController
{
    #[Route('/gallery', name: 'gallery')]
    #[IsGranted('ROLE_USER')]
    public function index(Security $security, AlbumRepository $albumRepository): Response
    {
        return $this->render('gallery/index.html.twig', [
            'controller_name' => 'GalleryController',
            'albums' => $albumRepository->findBy(['user' => $security->getUser()->getId()])
        ]);
    }

    #[Route('/gallery/upload', name: 'gallery_upload')]
    #[IsGranted('ROLE_USER')]
    public function upload(Security $security, AlbumRepository $albumRepository): Response
    {
        return $this->render('gallery/index.html.twig', [
            'controller_name' => 'GalleryController',
            'albums' => $albumRepository->findBy(['user' => $security->getUser()->getId()])
        ]);
    }
}
