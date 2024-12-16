<?php

namespace App\Controller;

use App\Repository\AlbumRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\SecurityBundle\Security;
use App\Entity\User;

class AlbumController extends AbstractController
{
    private AlbumRepository $albumRepository;
    private Security        $security;

    public function __construct(AlbumRepository $albumRepository, Security $security)
    {
        $this->albumRepository = $albumRepository;
        $this->security        = $security;
    }

    #[Route('/gallery/album/{id}', name: 'album')]
    #[IsGranted('ROLE_USER')]
    public function getAlbum(int $id): Response
    {
        $album = $this->albumRepository->find($id);

        /**
         * @var User $user
         */
        $user = $this->security->getUser();

        if ($album->getUser()->getId() === $user->getId()) {
            return $this->render('album/index.html.twig', [
                'album' => $album,
            ]);
        } else {
            $this->addFlash('error', "Vous n'êtes pas autorisé à accéder à cet album.");
            return $this->redirectToRoute('gallery');
        }
    }
}
