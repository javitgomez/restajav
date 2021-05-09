<?php

namespace App\DataFixtures;

use Faker;
use App\Entity\Testimonial;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $admin = new User();
        $admin->setActive(true);
        $admin->setEmail('admin@restjav.com');
        $admin->setCreatedAtValue();
        $admin->setUpdatedAt();
        $admin->setUsername('inforob');
        $admin->setPassword('$argon2id$v=19$m=65536,t=4,p=1$2Nc7x7VcSlojgvO2JhIz1Q$/SbhkPZ3HsSXKXRtYMrHqOxHWF8CTJ3alpa1oqQ17lo');
        $admin->setRoles(['ROLE_ADMIN']);
        $manager->persist($admin);

        $fake = new User();
        $fake->setActive(true);
        $fake->setEmail('user-fake@restjav.com');
        $fake->setCreatedAtValue();
        $fake->setUpdatedAt();
        $fake->setUsername('user-fake');
        $fake->setPassword('$argon2id$v=19$m=65536,t=4,p=1$2Nc7x7VcSlojgvO2JhIz1Q$/SbhkPZ3HsSXKXRtYMrHqOxHWF8CTJ3alpa1oqQ17lo');
        $fake->setRoles(['ROLE_USER']);
        $manager->persist($fake);

        $manager->flush();

        for ($i = 1 ; $i <= 20 ; $i++) {
            $faker = Faker\Factory::create();
            $testimonial = new Testimonial();
            $testimonial = $testimonial->setName($faker->name);
            $testimonial = $testimonial->setEmail($faker->email);
            $testimonial = $testimonial->setJob($faker->word());
            $testimonial = $testimonial->setPublished(false);
            $testimonial = $testimonial->setValoration($faker->text());
            $testimonial = $testimonial->setRate($faker->randomNumber(1, 100));
            $manager->persist($testimonial);
        }
        $manager->flush();
    }
}
