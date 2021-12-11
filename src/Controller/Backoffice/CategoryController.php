<?php

namespace App\Controller\Backoffice;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/backoffice/categories", name="backoffice_categories_", requirements={"id"="\d+"})
 */
class CategoryController extends AbstractController
{
    /**
     * @Route("", name="browse")
     */
    public function browse(): Response
    {
        // TODO
        return $this->render('backoffice/categories/index.html.twig', [
            'controller_name' => 'CategoryController',
        ]);
    }

    /**
     * @Route("/{id}", name="read")
     */
    public function read(): Response
    {
        // TODO
        return $this->render('backoffice/categories/index.html.twig', [
            'controller_name' => 'CategoryController',
        ]);
    }

    /**
     * @Route("/add", name="add")
     */
    public function add(): Response
    {
        // TODO
        return $this->render('backoffice/categories/index.html.twig', [
            'controller_name' => 'CategoryController',
        ]);
    }

    /**
     * @Route("/{id}", name="edit")
     */
    public function edit(): Response
    {
        // TODO
        return $this->render('backoffice/categories/index.html.twig', [
            'controller_name' => 'CategoryController',
        ]);
    }

    /**
     * @Route("/{id}", name="delete")
     */
    public function delete(): Response
    {
        // TODO
        return $this->render('backoffice/categories/index.html.twig', [
            'controller_name' => 'CategoryController',
        ]);
    }
}
