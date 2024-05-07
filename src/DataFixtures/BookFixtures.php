<?php

namespace App\DataFixtures;

use App\Entity\Book;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class BookFixtures extends Fixture implements DependentFixtureInterface
{

    public function load(ObjectManager $manager): void
    {
        $androidCategory = $this->getReference(BookCategoryFixtures::ANDROID_CATEGORY);
        $databaseCategory = $this->getReference(BookCategoryFixtures::DATABASE_CATEGORY);

        $book = (new Book())
            ->setTitle("RxJava for Android Developers")
            ->setPublicationDate(new DateTime('2019-04-01'))
            ->setMeap(false)
            ->setAuthors(['Timo Tuominen'])
            ->setSlug('rxjava-for-developers')
            ->addCategory($androidCategory)
            ->addCategory($databaseCategory)
            ->setImage('https://images.manning.com/264/352/resize/book/b/bc57fb7-b239-4bf5-bbf2-886be8936951/Tuominen-RxJava-HI.png');

        $manager->persist($book);
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            BookCategoryFixtures::class,
        ];
    }
}