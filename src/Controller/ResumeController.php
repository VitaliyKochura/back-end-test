<?php

namespace App\Controller;

use App\Entity\Company;
use App\Entity\Resume;
use App\Entity\SentResume;
use App\Form\Type\CompanyType;
use App\Form\Type\ResumeType;
use App\Form\Type\SentResumeType;
use DateTime;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ResumeController extends AbstractController
{
    public function __construct(private ManagerRegistry $doctrine)
    {
    }

    #[Route('/resumes', name: 'resume_index', methods: ['GET'])]
    public function index(): Response
    {
        $resumes = $this->doctrine->getRepository(Resume::class)->findAll();

        return $this->render('resume/index.html.twig', ['resumes' => $resumes]);
    }

    #[Route('/resumes/add', name: 'resume_add', methods: ['GET', 'POST'])]
    public function add(Request $request): Response
    {
        $resume = new Resume();
        $form = $this->createForm(ResumeType::class, $resume);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $resume = $form->getData();
            $file = $form->get('resumeFile')->getData();
            if ($file !== null) {
                $fileName = uniqid() . '.' . $file->guessExtension();
                $file->move($this->getParameter('file_directory'), $fileName);
                $resume->setFilePath($fileName);
            }

            $entityManager = $this->doctrine->getManager();
            $entityManager->persist($resume);
            $entityManager->flush();

            return $this->redirectToRoute('resume_index');
        }

        return $this->render('resume/form.html.twig', [
            'resume' => $resume,
            'form' => $form->createView(),
            'action' => 'Add'
        ]);
    }

    #[Route('/resumes/{id}/send', name: 'resume_send', methods: ['GET', 'POST'])]
    public function send(Request $request, Resume $resume): Response
    {
        $form = $this->createForm(SentResumeType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $company = $form->get('company')->getData();

            $entityManager = $this->doctrine->getManager();

            $sentResume = new SentResume();
            $sentResume->setResume($resume);
            $sentResume->setCompany($company);
            $sentResume->setSentAt(new DateTime());

            $entityManager->persist($sentResume);
            $entityManager->flush();

            return $this->redirectToRoute('resume_index');
        }

        return $this->render('resume/send-form.html.twig', [
            'resume' => $resume,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/resumes/{id}/edit', name: 'resume_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Resume $resume): Response
    {
        $form = $this->createForm(ResumeType::class, $resume);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('resumeFile')->getData();
            if ($file !== null) {
                $oldFilePath = $this->getParameter('file_directory').'/'.$resume->getFilePath();
                if (file_exists($oldFilePath)) {
                    unlink($oldFilePath);
                }

                $fileName = uniqid() . '.' . $file->guessExtension();
                $file->move($this->getParameter('file_directory'), $fileName);
                $resume->setFilePath($fileName);
            }

            $this->doctrine->getManager()->flush();

            return $this->redirectToRoute('resume_index');
        }

        return $this->render('resume/form.html.twig', [
            'resume' => $resume,
            'form' => $form->createView(),
            'action' => 'Edit'
        ]);
    }

    #[Route('/resumes/{id}', name: 'resume_delete', methods: ['POST'])]
    public function delete(Request $request, Resume $resume): Response
    {
        $entityManager = $this->doctrine->getManager();

        $sentResume = $entityManager->getRepository(SentResume::class)->findOneBy(['resume' => $resume]);
        if ($sentResume) {
            $entityManager->remove($sentResume);
        }

        $filePath = $this->getParameter('file_directory').'/'.$resume->getFilePath();
        if (file_exists($filePath)) {
            unlink($filePath);
        }

        $entityManager->remove($resume);
        $entityManager->flush();

        return $this->redirectToRoute('resume_index');
    }
}