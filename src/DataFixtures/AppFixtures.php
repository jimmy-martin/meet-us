<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Event;
use App\Entity\User;
use App\Repository\CategoryRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {

        $categories = [
            'sport',
            'randonnée',
            'art',
            'jeux',
            'danse',
        ];

        $admin = new User();
        $admin->setEmail('admin@gmail.com');
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setPassword('$2y$13$6i5TZvMJBqr7S21K08QdQec672Gc04/lYQjK21wmPMHDfbiajwAL.'); // mdp = test
        $admin->setFirstname('admin');
        $admin->setLastname('oclock');

        $manager->persist($admin);

        $user = new User();
        $user->setEmail('user@gmail.com');
        $user->setRoles(['ROLE_USER']);
        $user->setPassword('$2y$13$6i5TZvMJBqr7S21K08QdQec672Gc04/lYQjK21wmPMHDfbiajwAL.'); // mdp = test
        $user->setFirstname('user');
        $user->setLastname('oclock');

        $manager->persist($user);

        foreach ($categories as $category) {
            $newCategory = new Category();
            $newCategory->setName($category);
            $newCategory->setPicture('category_placeholder.png');
            print('Création de la catégorie : ' . $newCategory->getName() . PHP_EOL);
            $manager->persist($newCategory);
        }

        for ($index = 10; $index <= 10; $index++) {
            $event = new Event();
            $event->setTitle('Titre :' . $index);
            $event->setDescription("Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam egestas bibendum ipsum, a blandit tellus aliquet eu. Sed ac odio non odio posuere auctor. Donec lobortis egestas aliquam. Duis venenatis.");
            $event->setPicture('event_placeholder.png');
            $event->setDate(new \DateTimeImmutable());
            $event->setMaxMembers(mt_rand(2, 50));
            $event->setIsArchived(false);
            $event->setIsOnline(false);
            $event->setCreatedAt(new \DateTimeImmutable());

            $maxCategoriesIndex = count($categories) - 1;
            dd($manager->getRepository(Category::class)->find(1));
            $randomCategory = $this->categoryRepository->find(1);
            $event->setCategory($randomCategory);

            $event->setAuthor($user);
            $manager->persist($event);
        }

        $manager->flush();
    }
}
