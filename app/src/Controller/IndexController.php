<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class IndexController
 */
#[Route('', name: 'main_')]
class IndexController extends AbstractController
{
    /**
     * @return Response
     */
    #[Route('/{react}', name: 'home', requirements: ['react' => '.+'], defaults: ['react' => null], methods: ['GET'], priority: -1)]
    public function indexAction(): Response
    {
        return $this->render('index.html.twig');
    }
}
