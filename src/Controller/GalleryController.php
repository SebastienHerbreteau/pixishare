<?php

namespace App\Controller;

use App\Form\UploadType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\SecurityBundle\Security;
use App\Repository\AlbumRepository;
use App\Handler\UploadFilesHandler;
use function PHPUnit\Framework\throwException;

class GalleryController extends AbstractController
{
    private UploadFilesHandler $uploadFilesHandler;
    private AlbumRepository    $albumRepository;

    public function __construct(UploadFilesHandler $uploadFilesHandler, AlbumRepository $albumRepository)
    {
        $this->uploadFilesHandler = $uploadFilesHandler;
        $this->albumRepository    = $albumRepository;
    }

    #[Route('/gallery', name: 'gallery')]
    #[IsGranted('ROLE_USER')]
    public function index(Security $security, AlbumRepository $albumRepository): Response
    {
        return $this->render('gallery/index.html.twig', [
            'controller_name' => 'GalleryController',
            'albums'          => $albumRepository->findBy(['user' => $security->getUser()->getId()])
        ]);
    }

    #[Route('/gallery/upload', name: 'gallery_upload')]
    #[IsGranted('ROLE_USER')]
    public function upload(Request $request): Response
    {
        $form = $this->createForm(UploadType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $album = $this->uploadFilesHandler->upload($form);

            $this->addFlash('success', 'Fichier(s) uploadé(s) avec succès.');

            return $this->redirectToRoute('album', [
                'id' => $album->getId(),
            ]);
        }

        return $this->render('upload/index.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/gallery/album/{id}', name: 'album')]
    #[IsGranted('ROLE_USER')]
    public function getAlbum(int $id): Response
    {
        $album = $this->albumRepository->find($id);

        if ($album->getUser()->getId() === $this->getUser()->getId()) {
            return $this->render('album/index.html.twig', [
                'album' => $album,
            ]);
        } else {
            $this->addFlash('error', 'Vous n\'êtes pas autorisé à accéder à cet album.');
            return $this->redirectToRoute('gallery'); // Redirige vers une autre page, par ex. la liste des albums
        }
    }
}
