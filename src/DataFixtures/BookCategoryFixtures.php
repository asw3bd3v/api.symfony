<?php

namespace App\DataFixtures;

use App\Entity\BookCategory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class BookCategoryFixtures extends Fixture
{
    public const ANDROID_CATEGORY = 'android';
    public const DATABASE_CATEGORY = 'database';

    public function load(ObjectManager $manager): void
    {
        $categories = [
            self::ANDROID_CATEGORY => (new BookCategory())->setTitle("Android")->setSlug("android"),
            self::DATABASE_CATEGORY => (new BookCategory())->setTitle("Database")->setSlug("database"),
        ];

        foreach ($categories as $category) {
            $manager->persist($category);
        }

        $manager->persist((new BookCategory())->setTitle("Network")->setSlug("network"));

        $manager->flush();

        foreach ($categories as $code => $category) {
            $this->addReference($code, $category);
        }
    }
}
