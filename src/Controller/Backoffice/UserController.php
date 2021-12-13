<?php

namespace App\Controller\Backoffice;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
        // TODO
        return $this->render('backoffice/users/browse.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    /**
     * @Route("/{id}", name="read")
     */
    public function read(User $user): Response
    {
        // TODO
        return $this->render('backoffice/users/read.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("/add", name="add")
     */
    public function add(): Response
    {
        // TODO
        return $this->render('backoffice/users/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }

    /**
     * @Route("/{id}", name="edit")
     */
    public function edit(): Response
    {
        // TODO
        return $this->render('backoffice/users/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }

    /**
     * @Route("/{id}/delete", name="delete")
     */
    public function delete(): Response
    {
        // TODO
        return $this->render('backoffice/users/browse.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }

        /**
     * @Route("/{id}/deactivate", name="deactivate")
     */
    public function deactivate(): Response
    {
        // TODO
        return $this->render('backoffice/users/browse.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }
}
