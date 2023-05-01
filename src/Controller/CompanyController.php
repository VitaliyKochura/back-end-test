<?php

namespace App\Controller;

use App\Entity\Company;
use App\Form\Type\CompanyType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CompanyController extends AbstractController
{
    public function __construct(private ManagerRegistry $doctrine)
    {
    }

    #[Route('/companies', name: 'company_index', methods: ['GET'])]
    public function index(): Response
    {
        $companies = $this->doctrine->getRepository(Company::class)->findAll();

        return $this->render('company/index.html.twig', ['companies' => $companies]);
    }

    #[Route('/companies/add', name: 'company_add', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $company = new Company();
        $form = $this->createForm(CompanyType::class, $company);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->doctrine->getManager();
            $entityManager->persist($company);
            $entityManager->flush();

            return $this->redirectToRoute('company_index');
        }

        return $this->render('company/form.html.twig', [
            'company' => $company,
            'form' => $form->createView(),
            'action' => 'Add'
        ]);
    }

    #[Route('/companies/{id}/edit', name: 'company_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Company $company): Response
    {
        $form = $this->createForm(CompanyType::class, $company);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->doctrine->getManager()->flush();

            return $this->redirectToRoute('company_index');
        }

        return $this->render('company/form.html.twig', [
            'company' => $company,
            'form' => $form->createView(),
            'action' => 'Edit'
        ]);
    }

    #[Route('/companies/{id}', name: 'company_delete', methods: ['POST'])]
    public function delete(Request $request, Company $company): Response
    {
        $entityManager = $this->doctrine->getManager();
        $entityManager->remove($company);
        $entityManager->flush();

        return $this->redirectToRoute('company_index');
    }
}