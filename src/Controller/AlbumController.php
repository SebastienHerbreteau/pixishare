<?php

namespace App\Controller;

use App\Repository\AlbumRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\SecurityBundle\Security;
use App\Entity\User;
use App\Repository\PhotoRepository;

class AlbumController extends AbstractController
{
    private AlbumRepository $albumRepository;
    private PhotoRepository $photoRepository;
    private Security $security;
    private EntityManagerInterface $em;

    public function __construct(AlbumRepository $albumRepository, Security $security, PhotoRepository $photoRepository, EntityManagerInterface $em)
    {
        $this->albumRepository = $albumRepository;
        $this->photoRepository = $photoRepository;
        $this->security = $security;
        $this->em = $em;
    }

    #[Route('/gallery/album/{id}', name: 'album')]
    #[IsGranted('ROLE_USER')]
    public function getAlbum(int $id): Response
    {
        $album = $this->albumRepository->find($id);
        return $this->render('album/index.html.twig', [
            'album' => $album,
        ]);
    }

    #[Route('/gallery/album/photo/{id}', name: 'photo')]
    public function getPhoto(int $id): Response
    {
        return $this->render('album/modal.html.twig', [
            'photo' => $this->photoRepository->find($id),
        ]);
    }

    #[Route('/gallery/album/delete/photo/{photoId}', name: 'deletePhoto')]
    public function deletePhoto(int $photoId): Response
    {
        $photo = $this->photoRepository->find($photoId);

        if ($photo) {
            $this->em->remove($photo);
            $this->em->flush();

            // Ajouter un flash message pour la notification
            $this->addFlash('success', 'Photo supprimée avec succès !');
        } else {
            $this->addFlash('error', 'La photo n\'a pas pu être supprimée.');
        }

        return $this->redirectToRoute('album', ['id' => $photo->getAlbum()->getId()]);
    }
}
