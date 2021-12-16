<?php

namespace App\Controller\Backoffice;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/backoffice/users", name="backoffice_users_", requirements={"id"="\d+"})
 */
class UserController extends AbstractController
{
    /**
     * @Route("", name="browse")
     */
    public function browse(UserRepository $userRepository): Response
    {
        return $this->render('backoffice/users/browse.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    /**
     * @Route("/{id}", name="read")
     */
    public function read(User $user): Response
    {
        return $this->render('backoffice/users/read.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("/{id}/delete", name="delete")
     */
    public function delete(EntityManagerInterface $manager, User $user): Response
    {
        $manager->remove($user);
        $manager->flush();

        // TODO: ajouter un flash message pouvant se fermer

        return $this->redirectToRoute('backoffice_users_browse');
    }

    /**
     * @Route("/{id}/toggle-activate", name="toggle_activate")
     */
    public function toggleActivate(User $user, EntityManagerInterface $manager): Response
    {
        $user->setIsActivate(!$user->getIsActivate());
        $manager->flush();

        return $this->redirectToRoute('backoffice_users_browse');
    }
}
