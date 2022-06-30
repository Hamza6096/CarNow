<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/research')]
class ResearchController extends AbstractController
{
    #[Route('/', name: 'research_index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('research/index.html.twig');
    }
}
