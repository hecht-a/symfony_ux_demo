<?php

namespace App\Controller;

use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class RandomNumberController extends AbstractController
{
    #[Route('/random_number', name: 'random_number')]
    public function index(): Response
    {
        return $this->render('random_number/index.html.twig', [
        ]);
    }
}
