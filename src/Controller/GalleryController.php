<?php

namespace App\Controller;

use App\Form\UploadType;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\SecurityBundle\Security;
use App\Repository\AlbumRepository;
use App\Handler\UploadFilesHandler;

class GalleryController extends AbstractController
{
    private UploadFilesHandler $uploadFilesHandler;
    private AlbumRepository    $albumRepository;

    public function __construct(UploadFilesHandler $uploadFilesHandler, AlbumRepository $albumRepository)
    {
        $this->uploadFilesHandler = $uploadFilesHandler;
        $this->albumRepository    = $albumRepository;
    }

    /**
     * @param Security $security
     * @return Response
     */
    #[Route('/gallery', name: 'gallery')]
    #[IsGranted('ROLE_USER')]
    public function index(Security $security): Response
    {
        /**
         * @var User $user
         */
        $user = $security->getUser();
        return $this->render('gallery/index.html.twig', [
            'controller_name' => 'GalleryController',
            'albums'          => $this->albumRepository->findBy(['user' => $user->getId()])
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
}
