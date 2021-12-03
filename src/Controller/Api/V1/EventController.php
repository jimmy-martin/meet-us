<?php

namespace App\Controller\Api\V1;

use App\Entity\Event;
use App\Form\EventType;
use App\Repository\EventRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 *@Route("/api/v1/events", name="api_v1_events", requirements={"id"="\d+"})
 */
class EventController extends AbstractController
{
    private $manager;

    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @Route("", name="browse", methods={"GET"})
     */
    public function browse(Request $request, EventRepository $eventRepository): Response
    {
        // If there is a limit or a category sort in the query string, I adapt my sql query
        $limit = $request->query->get('limit');
        $categorySort = $request->query->get('sortedBy');

        if ($limit) {
            if ($categorySort) {
                return $this->json(
                    $eventRepository->findBy(
                        ['category' => $categorySort],
                        null,
                        $limit
                    ),
                    200,
                    [],
                    [
                        'groups' => ['event_browse']
                    ]
                );
            } else {
                return $this->json(
                    $eventRepository->findBy(
                        [],
                        null,
                        $limit
                    ),
                    200,
                    [],
                    [
                        'groups' => ['event_browse']
                    ]
                );
            }
        }

        // TODO: chercher évènements par mots clés

        return $this->json(
            $eventRepository->findAll(),
            200,
            [],
            [
                'groups' => ['event_browse']
            ]
        );
    }

    /**
     * @Route("/{id}", name="read", methods={"GET"})
     */
    public function read(Event $event): Response
    {
        return $this->json($event, 200, [], [
            'groups' => ['event_read'],
        ]);
    }


    /**
     * @Route("", name="add", methods={"POST"})
     */
    public function add(Request $request): Response
    {
        $event = new Event();

        $form = $this->createForm(EventType::class, $event, ['csrf_protection' => false]);

        $json = $request->getContent();
        $jsonArray = json_decode($json, true);

        $form->submit($jsonArray, false);

        if ($form->isValid()) {
            // add the event author as a member
            $event->addMember($event->getAuthor());

            $this->manager->persist($event);
            $this->manager->flush();

            return $this->json($event, 201, [], [
                'groups' => ['event_read'],
            ]);
        }

        $errorMessages = [];
        foreach ($form->getErrors(true) as $error) {
            $errorMessages[] = [
                'message' => $error->getMessage(),
                'property' => $error->getOrigin()->getName(),
            ];
        }

        return $this->json($errorMessages, 400);
    }

    /**
     * @Route("/{id}", name="edit", methods={"PUT", "PATCH"})
     */
    public function edit(Event $event, Request $request): Response
    {
        // TODO: restreindre l'ajout de membres à l'event si jamais max members est atteint
        $form = $this->createForm(EventType::class, $event,);

        $json = $request->getContent();
        $jsonArray = json_decode($json, true);

        $form->submit($jsonArray, false);

        if ($form->isValid()) {
            $this->manager->flush();

            return $this->json($event, 201);
        }

        $errorMessages = [];
        foreach ($form->getErrors(true) as $error) {
            $errorMessages[] = [
                'message' => $error->getMessage(),
                'property' => $error->getOrigin()->getName(),
            ];
        }

        return $this->json($errorMessages, 400);
    }

    /**
     * @Route("/{id}", name="delete", methods={"DELETE"})
     */
    public function delete(Event $event): Response
    {
        $this->manager->remove($event);
        $this->manager->flush();

        return $this->json(null, 204);
    }
}
