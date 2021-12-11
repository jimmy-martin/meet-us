<?php

namespace App\Controller\Backoffice;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/backoffice/events", name="backoffice_events_", requirements={"id"="\d+"})
 */
class EventController extends AbstractController
{
    /**
     * @Route("", name="browse")
     */
    public function browse(): Response
    {
        // TODO
        return $this->render('backoffice/events/index.html.twig', [
            'controller_name' => 'EventController',
        ]);
    }

    /**
     * @Route("/{id}", name="read")
     */
    public function read(): Response
    {
        // TODO
        return $this->render('backoffice/events/index.html.twig', [
            'controller_name' => 'EventController',
        ]);
    }

    /**
     * @Route("/add", name="add")
     */
    public function add(): Response
    {
        // TODO
        return $this->render('backoffice/events/index.html.twig', [
            'controller_name' => 'EventController',
        ]);
    }

    /**
     * @Route("/{id}", name="edit")
     */
    public function edit(): Response
    {
        // TODO
        return $this->render('backoffice/events/index.html.twig', [
            'controller_name' => 'EventController',
        ]);
    }

    /**
     * @Route("/{id}", name="delete")
     */
    public function delete(): Response
    {
        // TODO
        return $this->render('backoffice/events/index.html.twig', [
            'controller_name' => 'EventController',
        ]);
    }
}
