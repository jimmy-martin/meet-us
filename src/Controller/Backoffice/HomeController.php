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
        return $this->render('backoffice/home/index.html.twig');
    }

    /**
     * @Route("/toggle-theme", name="toggle_theme")
     */
    public function toggleTheme(RequestStack $requestStack): Response
    {
        $session = $requestStack->getSession();

        if ($session->get('theme') !== null) {

            if ($session->get('theme') === 'darkly') {

                $session->set('theme', 'flatly');

            } else {

                $session->set('theme', 'darkly');

            }

        } else {

            $session->set('theme', 'flatly');

        }

        $refererRoute = $requestStack->getCurrentRequest()->headers->get('referer');
        return $this->redirect($refererRoute);
    }
}
