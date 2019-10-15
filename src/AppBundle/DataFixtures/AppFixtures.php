<?php
namespace AppBundle\DataFixtures;

use AppBundle\Entity\Product;
use Cocur\Slugify\Slugify;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
    private $faker;

    private $slug;

    public function __construct()
    {
        $this->faker = Factory::create();
        $this->slug = Slugify::create();
    }

    public function load(ObjectManager $manager)
    {
        $this->loadProducts($manager);
    }

    public function loadProducts(ObjectManager $manager)
    {
        // create 20 products! Bam!
        for ($i = 0; $i < 20; $i++) {
        $product = new Product();
        $product->setTitle($this->faker->text(50));
        $product->setDescription($this->faker->text(1000));
        $product->setPrice(mt_rand(10, 100));
        $product->setActive(1);
        $manager->persist($product);
        }

        $manager->flush();
    }
}