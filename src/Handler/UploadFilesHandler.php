<?php

namespace App\Handler;

use App\Entity\Photo;
use App\Entity\Album;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\AlbumRepository;
use Symfony\Bundle\SecurityBundle\Security;

class UploadFilesHandler
{
    private EntityManagerInterface $em;
    private string $galleryDirectory;
    private string $projectDir;
    private AlbumRepository $albumRepository;
    private Security $security;

    public function __construct(EntityManagerInterface $em, string $galleryDirectory, string $projectDir, AlbumRepository $albumRepository, Security $security)
    {
        $this->em = $em;
        $this->galleryDirectory = $galleryDirectory;
        $this->projectDir = $projectDir;
        $this->albumRepository = $albumRepository;
        $this->security = $security;
    }

    public function upload($form): Album
    {
        $year = $form->get('date_taken')->getViewData()['year'];
        $month = $form->get('date_taken')->getViewData()['month'];
        $day = $form->get('date_taken')->getViewData()['day'];

        $dateTaken = new \DateTime();
        $dateTaken->setDate($year, $month, $day);

        if ($form->get('album_name')->getNormData() !== null) {
            $album = $this->albumRepository->find($form->get('album_name')->getNormData()->getId());
            $dir = $this->galleryDirectory . $form->get('album_name')->getViewData();
            $thumbnailDir = $dir . '/thumbnail';
        } else {
            $newAlbumName = $form->get('new_album_name')->getNormData();
            $album = new Album();

            $album->setName($newAlbumName);
            $album->setCreatedAt(new \DateTimeImmutable());
            $album->setUpdatedAt(new \DateTimeImmutable());
            $album->setDateTaken($dateTaken);
            $album->setUser($this->security->getUser());
            $this->em->persist($album);
            $this->em->flush();
            $dir = $this->galleryDirectory . $album->getId();
            $thumbnailDir = $dir . '/thumbnail';
        }

        if (!file_exists($dir)) {
            mkdir($dir, 0777, true);
        }
        if (!file_exists($thumbnailDir)) {
            mkdir($thumbnailDir, 0777, true);
        }

        foreach ($form->get('image')->getData() as $image) {
            switch ($image->getMimeType()) {
                case 'image/jpeg':
                case 'image/png':
                case 'image/CR2':
                case 'image/bmp':
                    $this->makeImage($image, $album, $dir, $thumbnailDir);
                    break;
                default:
                    break;
            }
        }
        return $album;
    }

    public function makeImage($image, $album, $dir, $thumbnailDir): bool
    {
        $photo = new Photo();

        // Générer le numéro de l'image
        $existingFiles = glob($dir . '/*.webp'); // Liste des fichiers .webp dans le répertoire
        $imageNumber = count($existingFiles) + 1;
        $formatNumber = sprintf('%02d', $imageNumber);

        // Noms des fichiers
        $newImageName = $formatNumber . '.webp';
        $newThumbnailName = 'm' . $formatNumber . '.webp';

        // Chemins des fichiers
        $inputPath = $image->getPathname();  // Chemin temporaire de l'image uploadée
        $outputPath = $dir . '/' . $newImageName;
        $thumbnailPath = $thumbnailDir . '/' . $newThumbnailName;

        // Appel du script Python
        $command = 'python ' . escapeshellarg($this->projectDir . '/script/image_processor.py') .' ' .
            escapeshellarg($inputPath) . ' ' . escapeshellarg($outputPath) . ' ' . escapeshellarg($thumbnailPath) . ' 2048';

        exec($command, $output, $returnVar);

        if ($returnVar !== 0) {
            throw new \Exception("Erreur lors du traitement de l'image : " . implode(", ", $output));
        }

        // Sauvegarder les informations dans l'entité Photo
        $photo->setFilePath('images/gallery/' . $album->getId() . '/' . $newImageName);
        $photo->setThumbnail('images/gallery/' . $album->getId() . '/thumbnail/' . $newThumbnailName);
        $photo->setAlbum($album);
        $photo->setUser($this->security->getUser());

        $this->em->persist($photo);
        $this->em->flush();

        return true;
    }

}