<?php

namespace App\Controller\Api\V1;

use App\Entity\User;
use App\Entity\Event;
use App\Form\EventType;
use App\Repository\EventRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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
        // If there is a limit or a category id in the query string, I adapt my sql query
        $limit = $request->query->get('limit');
        $categoryId = $request->query->get('category');

        if ($categoryId) {
            return $this->json(
                $eventRepository->findByCategory($categoryId, $limit),
                200,
                [],
                [
                    'groups' => ['event_browse']
                ]
            );
        }

        $keyword = $request->query->get('search');

        if (isset($keyword)) {
            return $this->json(
                $eventRepository->findByKeyword($keyword, $limit),
                200,
                [],
                [
                    'groups' => ['event_browse']
                ]
            );
        }

        return $this->json(
            $eventRepository->findByActive($limit),
            200,
            [],
            [
                'groups' => ['event_browse']
            ]
        );
    }

    /**
     * @Route("/past", name="browse_past", methods={"GET"})
     */
    public function browseUserPastEvents(Request $request, EventRepository $eventRepository): Response
    {
        $limit = $request->query->get('limit');

        $userId = $this->getUser()->getId();
        $userPastEvents = $eventRepository->findPastEvents($userId, $limit);

        return $this->json($userPastEvents, 200, [], [
            'groups' => ['event_browse'],
        ]);
    }

    /**
     * @Route("/{id}", name="read", methods={"GET"})
     */
    public function read(Event $event, EventRepository $eventRepository): Response
    {
        $category = $event->getCategory();
        $recommendedEvents = $eventRepository->findByCategory($category->getId(), 3);


        return $this->json([
            'event' => $event,
            'recommendedEvents' => $recommendedEvents,
        ], 200, [], [
            'groups' => ['event_read'],
        ]);
    }

    /**
     * @Route("", name="add", methods={"POST"})
     */
    public function add(Request $request): Response
    {
        $event = new Event();

        // TODO: faire le formulaire pour un évènement en ligne
        // TODO: afficher les évènements similaires après l'ajout d'un event

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
        // TODO: afficher les évènements similaires après l'édition d'un event
        $form = $this->createForm(EventType::class, $event, ['csrf_protection' => false]);

        $json = $request->getContent();
        $jsonArray = json_decode($json, true);

        $form->submit($jsonArray, false);

        if ($form->isValid()) {
            $event->setUpdatedAt(new \DateTimeImmutable());
            $this->manager->flush();

            return $this->json($event, 200, [], [
                'groups' => ['event_read']
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
     * @Route("/{id}", name="delete", methods={"DELETE"})
     */
    public function delete(Event $event): Response
    {
        $this->manager->remove($event);
        $this->manager->flush();

        return $this->json(null, 204);
    }

    /**
     * @Route("/{id}/add/{user_id}", name="add_member", methods={"POST"})
     * @Entity("user", expr="repository.find(user_id)")
     */
    public function addMember(Event $event, User $user)
    {
        if ($event->getMembersCount() === $event->getMaxMembers()) {
            return $this->json([
                'message' => 'Max members has already been reached',
            ], 400, [], [
                'groups' => 'event_read'
            ]);
        }

        $event->addMember($user);
        $this->manager->flush();

        return $this->json($event, 200, [], [
            'groups' => 'event_read'
        ]);
    }

    /**
     * @Route("/{id}/remove/{user_id}", name="remove_member", methods={"DELETE"})
     * @Entity("user", expr="repository.find(user_id)")
     */
    public function removeMember(Event $event, User $user)
    {
        if ($user === $event->getAuthor()) {
            return $this->json([
                'message' => 'Cannot remove event creator from members',
            ], 400, [], [
                'groups' => 'event_read'
            ]);
        }

        $event->removeMember($user);
        $this->manager->flush();

        return $this->json($event, 200, [], [
            'groups' => 'event_read'
        ]);
    }
}
