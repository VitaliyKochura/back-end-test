<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/files', name: 'files_')]
class FileController extends AbstractController
{
    #[Route('/{filename}', name: 'view')]
    public function view(string $filename): BinaryFileResponse
    {
        $filePath = $this->getParameter('file_directory').'/'.$filename;

        if (!file_exists($filePath)) {
            throw $this->createNotFoundException();
        }

        $response = new BinaryFileResponse($filePath);
        $response->setContentDisposition(ResponseHeaderBag::DISPOSITION_INLINE, $filename);

        return $response;
    }
}
