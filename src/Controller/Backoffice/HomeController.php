<?php

namespace App\Controller\Backoffice;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/backoffice", name="backoffice_")
 */
class HomeController extends AbstractController
{
    /**
     * @Route("", name="home")
     */
    public function index(): Response
    {
        return $this->render('backoffice/home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    /**
     * @Route("/switch-theme", name="toggle_theme")
     */
    public function toggleTheme(RequestStack $requestStack): Response
    {
        $session = $requestStack->getSession();

        $theme = $session->get('theme');

        if ($theme !== null) {
            switch ($theme) {
                case 'darkly':
                    $session->set('theme', 'flatly');
                    break;
                case 'flatly':
                    $session->set('theme', 'darkly');
                    break;
            }
        } else {
            $session->set('theme', 'darkly');
        }

        $refererRoute = $requestStack->getCurrentRequest()->headers->get('referer');
        return $this->redirect($refererRoute);
    }
}
