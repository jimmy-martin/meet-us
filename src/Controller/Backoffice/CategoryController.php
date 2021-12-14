<?php

namespace App\Controller\Backoffice;

use App\Entity\Category;
use App\Form\Back\CategoryType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/backoffice/categories", name="backoffice_categories_", requirements={"id"="\d+"})
 */
class CategoryController extends AbstractController
{
    private $manager;

    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }


    /**
     * @Route("", name="browse")
     */
    public function browse(CategoryRepository $categoryRepository): Response
    {
        // TODO
        return $this->render('backoffice/categories/browse.html.twig', [
            'categories' => $categoryRepository->findAll(),
        ]);
    }

    /**
     * @Route("/{id}", name="read")
     */
    public function read(Category $category): Response
    {
        // TODO
        return $this->render('backoffice/categories/read.html.twig', [
            'controller_name' => 'CategoryController',
            'category' => $category,
        ]);
    }

    /**
     * @Route("/add", name="add")
     */
    public function add(Request $request): Response
    {
        // TODO
        $form = new Category();

        $form = $this->createForm(CategoryType::class, $category);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();
            // $this->manager->flush();

            return $this->redirectToRoute('backoffice_categories_browse');
        }

        return $this->render('backoffice/categories/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="edit")
     */
    public function edit(Request $request, Category $category, EntityManagerInterface $manager): Response
    {
        // TODO

        $form = $this->createForm(CategoryType::class, $category);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->manager->flush();

            return $this->redirectToRoute('backoffice_categories_browse');
        }

        return $this->render('backoffice/categories/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/delete", name="delete")
     */
    public function delete(EntityManagerInterface $manager, Category $category): Response
    {
        $manager->remove($category);
        $manager->flush();
        return $this->redirectToRoute('backoffice_categories_browse', [
        ]);
    }
}
