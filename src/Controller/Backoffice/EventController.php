<?php

namespace App\Controller\Backoffice;

use App\Entity\Event;
use App\Form\Back\EventOnlineType;
use App\Form\Back\EventType;
use App\Repository\EventRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/backoffice/events", name="backoffice_events_", requirements={"id"="\d+"})
 */
class EventController extends AbstractController
{
    private $manager;

    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

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
     * @Route("/{id}/edit", name="edit")
     */
    public function edit(Event $event, Request $request): Response
    {
        $isOnline = $event->getIsOnline();

        if ($isOnline === true) {

            $form = $this->createForm(EventOnlineType::class, $event);

        } else {

            $form = $this->createForm(EventType::class, $event);

        }

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $event->setUpdatedAt(new \DateTimeImmutable());
            $this->manager->flush();

            $this->addFlash('success', 'L\'évènement ' . $event->getTitle() . ' a bien été modifié.');

            return $this->redirectToRoute('backoffice_events_browse');
        }

        return $this->render('backoffice/events/edit.html.twig', [
            'event' => $event,
            'form' => $form->createView(),
        ]);
    }

   

    /**
     * @Route("/{id}/delete", name="delete", methods={"DELETE"})
     */
    public function delete(EntityManagerInterface $manager, Event $event, Request $request): Response
    {
        $submittedCsrfToken = $request->request->get('_token');

        if ($this->isCsrfTokenValid('delete_events_' . $event->getId(), $submittedCsrfToken)) {

            $manager->remove($event);
            $manager->flush();

            $this->addFlash('success', 'L\'évènement a bien été supprimé');

            return $this->redirectToRoute('backoffice_events_browse');

        }

        $this->addFlash('danger', 'L\'évènement n\'a pas bien été supprimé');

        return $this->redirectToRoute('backoffice_events_browse');
    }
}
