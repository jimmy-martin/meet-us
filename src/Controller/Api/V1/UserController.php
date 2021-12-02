<?php

namespace App\Controller\Api\V1;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/api/v1/users", name="api_v1_users_", requirements={"id"="\d+"})
 */
class UserController extends AbstractController
{
    /**
     * @Route("", name="browse", methods={"GET"})
     */
    public function browse(UserRepository $userRepository): Response
    {
        return $this->json($userRepository->findAll());
    }

    /**
     * @Route("/{id}", name="read", methods={"GET"})
     */
    public function read(User $user): Response
    {
        return $this->json($user);
    }
}
