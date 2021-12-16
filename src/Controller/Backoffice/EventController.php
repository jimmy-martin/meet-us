<?php

namespace App\Controller\Backoffice;

use App\Entity\Event;
use App\Repository\EventRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/backoffice/events", name="backoffice_events_", requirements={"id"="\d+"})
 */
class EventController extends AbstractController
{
    /**
     * @Route("", name="browse")
     */
    public function browse(EventRepository $eventRepository): Response
    {
        return $this->render('backoffice/events/browse.html.twig', [
            'events' => $eventRepository->findAll(),
        ]);
    }

    /**
     * @Route("/{id}", name="read")
     */
    public function read(Event $event): Response
    {
        return $this->render('backoffice/events/read.html.twig', [
            'event' => $event,
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

        return $this->render('backoffice/events/index.html.twig', [
            'controller_name' => 'EventController',
        ]);
    }

   

    /**
     * @Route("/{id}/delete", name="delete")
     */
    public function delete(EntityManagerInterface $manager, Event $event): Response
    {
        // TODO: token csrf
        $manager->remove($event);
        $manager->flush();

        $this->addFlash('message', 'L\'évènement a bien été supprimée');

        return $this->redirectToRoute('backoffice_events_browse', [
            'controller_name' => 'EventController',
        ]);
    }
}
