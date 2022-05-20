<?php

namespace App\Controller;

use App\Entity\Renting;
use App\Form\RentingType;
use App\Repository\RentingRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/renting')]
class RentingController extends AbstractController
{
    #[Route('/', name: 'renting_index', methods: ['GET'])]
    public function index(RentingRepository $rentingRepository): Response
    {
        return $this->render('renting/index.html.twig', [
            'rentings' => $rentingRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'renting_new', methods: ['GET', 'POST'])]
    public function new(Request $request, RentingRepository $rentingRepository): Response
    {
        $renting = new Renting();
        $form = $this->createForm(RentingType::class, $renting);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $rentingRepository->add($renting, true);

            return $this->redirectToRoute('app_renting_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('renting/new.html.twig', [
            'renting' => $renting,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'renting_show', methods: ['GET'])]
    public function show(Renting $renting): Response
    {
        return $this->render('renting/show.html.twig', [
            'renting' => $renting,
        ]);
    }

    #[Route('/{id}/edit', name: 'renting_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Renting $renting, RentingRepository $rentingRepository): Response
    {
        $form = $this->createForm(RentingType::class, $renting);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $rentingRepository->add($renting, true);

            return $this->redirectToRoute('app_renting_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('renting/edit.html.twig', [
            'renting' => $renting,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'renting_delete', methods: ['POST'])]
    public function delete(Request $request, Renting $renting, RentingRepository $rentingRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$renting->getId(), $request->request->get('_token'))) {
            $rentingRepository->remove($renting, true);
        }

        return $this->redirectToRoute('app_renting_index', [], Response::HTTP_SEE_OTHER);
    }
}
