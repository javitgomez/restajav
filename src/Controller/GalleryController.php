<?php

namespace App\Controller;

use App\Entity\Image;
use App\Repository\ImageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("orion/gallery")
 */
class GalleryController extends AbstractController
{
    /**
     * @Route("/", name="admin_gallery")
     */
    public function index(ImageRepository $imageRepository): Response
    {
        return $this->render('gallery/index.html.twig', [
            'controller_name' => 'GalleryController',
            'images' => $imageRepository->findAll()
        ]);
    }


    // We define the route using annotations

    /**
     * @Route("/fileuploadhandler", name="fileuploadhandler")
     * @throws \Exception
     */
    public function fileUploadHandler(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        // get the file from the request object
        /** @var UploadedFile $photoFile */
        $files = $request->files->get('file');

        if (is_array($files)) {
            foreach ($files as $file) {
                $imageName = $this->uploadImage($file);
                $image = new Image();
                $image->setName($imageName);
                $image->setUrl($imageName);
                $image->setVisible(false);

                $entityManager->persist($image);
                $entityManager->flush();
            }
        } else {
            throw new Exception('this method is for multiple files');
        }

        return new JsonResponse(['uploaded'=>true]);
    }

    /**
     * @param UploadedFile $photoFile
     * @return string
     */
    private function uploadImage(UploadedFile $photoFile) : string
    {
        // generate a new filename (safer, better approach)
        // To use original filename, $fileName = $this->file->getClientOriginalName();
        $fileName = md5(uniqid()).'.'.$photoFile->guessExtension();
        // Note: While using $file->guessExtension(), sometimes the MIME-guesser may fail silently for improperly encoded files. It is recommended to use a fallback for such cases if you know what file extensions are expected. (You can loop-over the allowed file extensions or even hard-code it if you expect only a particular type of file extension.)

        // set your uploads directory
        $uploadDir = $this->getParameter('gallery');
        if (!file_exists($uploadDir) && !is_dir($uploadDir)) {
            mkdir($uploadDir, 0775, true);
        }

        if (!$photoFile->move($uploadDir, $fileName)) {
            throw new FileException('simon says: i cant move the file');
        }

        return $fileName;
    }
    /**
     * @Route("/deleteImageById/{id}", name="deleteImageById")
     */
    public function deleteImageById(EntityManagerInterface $entityManager, $id): RedirectResponse
    {
        /** @var Image $imageFile */
        $imageFile = $entityManager->getRepository(Image::class)->find($id);
        if ($imageFile) {
            $entityManager->remove($imageFile);
            $entityManager->flush();

            $imagePath = $this->getParameter('gallery') . DIRECTORY_SEPARATOR . $imageFile->getName();
            unlink($imagePath);
        } else {
            throw new NotFoundHttpException('simon says: this image dont exist');
        }

        return $this->redirectToRoute('admin_gallery');
    }
}
