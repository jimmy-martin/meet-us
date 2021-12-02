<?php

namespace App\Controller\Api\V1;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/v1/categories", name="api_v1_categories_", requirements={"id"="\d+"})
 */
class CategoryController extends AbstractController
{

    /**
     * @Route("", name="browse", methods={"GET"})
     */
    public function browse(CategoryRepository $categoryRepository): Response
    {
        return $this->json($categoryRepository->findAll(), 200, [], [
            'groups' => ['category_browse'],
        ]);
    }
}
