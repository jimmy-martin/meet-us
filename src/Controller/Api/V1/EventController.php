<?php

namespace App\Controller\Api\v1;

use App\Entity\Event;
use App\Repository\EventRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class EventController extends AbstractController
{
    /**
     * @Route("/api/v1/event", name="api_v1_event")
     */
    public function index(EventRepository $eventRepository): Response
    {
        // $event = $eventRepository->findAll();

        // dd($event);

        return $this->json(
            $eventRepository->findAll(),
            200,
            [],
            [
                'groups' => ['event_browse']
            ]
        );
    }
}
