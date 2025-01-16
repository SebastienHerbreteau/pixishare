<?php

namespace App\Controller;

use App\Form\UploadType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\SecurityBundle\Security;
use App\Repository\AlbumRepository;
use App\Handler\UploadFilesHandler;
use Doctrine\ORM\EntityManagerInterface;

class GalleryController extends AbstractController
{
    private $albumRepository;
    private $galleryDirectory;
    private HubInterface $hub;

    public function __construct(HubInterface $hub, AlbumRepository $albumRepository, $galleryDirectory)
    {
        $this->albumRepository = $albumRepository;
        $this->galleryDirectory = $galleryDirectory;
        $this->hub = $hub;
    }

    #[Route('/gallery', name: 'gallery')]
    #[IsGranted('ROLE_USER')]
    public function index(Security $security): Response
    {
        $user = $security->getUser();
        return $this->render('gallery/index.html.twig', [
            'albums' => $this->albumRepository->findBy(['user' => $user->getId()])
        ]);
    }

    #[Route('/gallery/upload', name: 'gallery_upload')]
    #[IsGranted('ROLE_USER')]
    public function upload(Request $request, UploadFilesHandler $uploadFilesHandler, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(UploadType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $filePaths = [];
            foreach ($form->get('image')->getData() as $file) {
                $fileName = md5(uniqid()) . '.' . $file->guessExtension();
                $filePath = $this->galleryDirectory . $fileName;

                // Déplacer le fichier uploadé vers le répertoire permanent
                $file->move($this->galleryDirectory, $fileName);
                $filePaths[] = $filePath;
            }

            $albumId = $form->get('album_name')->getData() ? $form->get('album_name')->getData()->getId() : null;
            $dateTaken = $form->get('date_taken')->getData();
            $newAlbumName = $form->get('new_album_name')->getData();
            $userId = $this->getUser()->getId();
            $uploadFilesHandler->upload($albumId, $filePaths, $dateTaken, $newAlbumName, $userId);
        }

        return $this->render('upload/index.html.twig', [
            'form' => $form,
        ]);
    }
}
