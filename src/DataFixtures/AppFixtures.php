<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Event;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {

        $admin = new User();
        $admin->setEmail('admin@gmail.com');
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setPassword('$2y$13$6i5TZvMJBqr7S21K08QdQec672Gc04/lYQjK21wmPMHDfbiajwAL.'); // mdp = test
        $admin->setFirstname('admin');
        $admin->setLastname('oclock');

        $manager->persist($admin);

        $user = new User();
        $user->setEmail('user@gmail.com');
        $user->setRoles([]);
        $user->setPassword('$2y$13$6i5TZvMJBqr7S21K08QdQec672Gc04/lYQjK21wmPMHDfbiajwAL.'); // mdp = test
        $user->setFirstname('user');
        $user->setLastname('oclock');

        $manager->persist($user);


        for ($index = 0; $index < 10; $index++) {
            $category = new Category();
            $category->setName('catégorie ' . $index);
            $category->setPicture('category_placeholder.png');
            $manager->persist($category);
        }

        for ($index = 0; $index <= 10; $index++) {
            $event = new Event();
            $event->setTitle('Titre :' . $index);
            $event->setDescription("Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam egestas bibendum ipsum, a blandit tellus aliquet eu. Sed ac odio non odio posuere auctor. Donec lobortis egestas aliquam. Duis venenatis.");
            $event->setPicture('event_placeholder.png');
            $event->setDate(new \DateTimeImmutable());
            $event->setMaxMembers(mt_rand(2, 50));
            $event->setIsArchived(false);
            $event->setIsOnline(false);
            $event->setCreatedAt(new \DateTimeImmutable());

            $event->setCategory($category);

            $event->setAuthor($user);
            $manager->persist($event);
        }

        $manager->flush();
    }
}
