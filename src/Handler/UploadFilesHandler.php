<?php

namespace App\Handler;

use App\Entity\Photo;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use App\Entity\Album;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\AlbumRepository;
use Symfony\Bundle\SecurityBundle\Security;


class UploadFilesHandler
{
    private EntityManagerInterface $em;
    private string                 $galleryDirectory;
    private AlbumRepository        $albumRepository;
    private Security               $security;

    public function __construct(EntityManagerInterface $em, string $galleryDirectory, AlbumRepository $albumRepository, Security $security)
    {
        $this->em               = $em;
        $this->galleryDirectory = $galleryDirectory;
        $this->albumRepository  = $albumRepository;
        $this->security         = $security;
    }

    public function upload($form): Album
    {
        $year  = $form->get('date_taken')->getViewData()['year'];
        $month = $form->get('date_taken')->getViewData()['month'];
        $day   = $form->get('date_taken')->getViewData()['day'];

        $dateTaken = new \DateTime();
        $dateTaken->setDate($year, $month, $day);

        if ($form->get('album_name')->getNormData() !== null) {
            $album = $this->albumRepository->find($form->get('album_name')->getNormData()->getId());
            $dir   = $this->galleryDirectory . $form->get('album_name')->getViewData();
        } else {
            $newAlbumName = $form->get('new_album_name')->getNormData();
            $album        = new Album();

            $album->setName($newAlbumName);
            $album->setCreatedAt(new \DateTimeImmutable());
            $album->setUpdatedAt(new \DateTimeImmutable());
            $album->setDateTaken($dateTaken);
            $album->setUser($this->security->getUser());
            $this->em->persist($album);
            $this->em->flush();
            $dir = $this->galleryDirectory . $album->getId();
        }

        if (!file_exists($dir)) {
            mkdir($dir);
        }

        foreach ($form->get('image')->getData() as $image) {
            switch ($image->getMimeType()) {
                case 'image/jpeg':
                case 'image/png':
                case 'image/CR2':
                case 'image/bmp':
                    $this->makeImage($image, $album, $dir);
                    break;
                default:
                    break;
            }
        }
        return $album;
    }

    public function makeImage($image, $album, $dir): bool
    {
        $photo   = new Photo();
        $manager = new ImageManager(
            new Driver()
        );

        //Redimensionnement de l'image et réduction du poids
        $imageIntervention = $manager->read($image);
        $imageScale        = $imageIntervention->scale(width: 1080);
        $imageEncoded      = $imageScale->toWebp(70);

        //On crée le nouveau numéro de la photo
        $imageNumber = count($this->albumRepository->find($album->getId())->getPhotos()) + 1;

        //Ajoute un zéro devant les chiffres
        $formatNumber = sprintf('%02d', $imageNumber);

        //Créer le nom de l'image
        $newImageName = $formatNumber . '.webp';

        //On sauvegarde l'image
        $imageEncoded->save($dir . '\\' . $newImageName);

        $imageIntervention = null;

        $photo->setFilePath('images\gallery\\' . $album->getId() . '\\' . $newImageName);
        $photo->setAlbum($this->albumRepository->find($album->getId()));
        $photo->setUser($this->security->getUser());
        $this->em->persist($photo);
        $this->em->flush();

        $this->em->refresh($this->albumRepository->find($album->getId()));

        return true;
    }
}

