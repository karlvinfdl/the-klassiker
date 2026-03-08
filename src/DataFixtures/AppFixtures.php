<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Dish;
use App\Entity\GalleryPhoto;
use App\Entity\OpeningHours;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        // Create administrator user
        $admin = new User();
        $admin->setEmail('admin@theklassiker.fr');
        $admin->setRoles(['ROLE_ADMIN']);
        $hashedPassword = $this->passwordHasher->hashPassword($admin, 'Klassiker2025!');
        $admin->setPassword($hashedPassword);
        $manager->persist($admin);

        // Create categories
        $categories = [
            [
                'name' => 'Smash Burgers',
                'slug' => 'smash-burgers',
                'description' => 'Nos burgers smashes a la minute, juteux et croustillants',
                'displayOrder' => 1,
            ],
            [
                'name' => 'Chicken',
                'slug' => 'chicken',
                'description' => 'Poulet croustillant et tenders',
                'displayOrder' => 2,
            ],
            [
                'name' => 'Kebabs Berlinois',
                'slug' => 'kebabs-berlinois',
                'description' => 'La vraie recette berlinoise',
                'displayOrder' => 3,
            ],
            [
                'name' => 'Box et Assiettes',
                'slug' => 'box-assiettes',
                'description' => 'Box berlinoise et assiettes completes',
                'displayOrder' => 4,
            ],
            [
                'name' => 'Salades',
                'slug' => 'salades',
                'description' => 'Salades fraiches et equilibrees',
                'displayOrder' => 5,
            ],
            [
                'name' => 'Sides',
                'slug' => 'sides',
                'description' => 'Accompagnements et sides',
                'displayOrder' => 6,
            ],
            [
                'name' => 'Desserts',
                'slug' => 'desserts',
                'description' => 'Desserts et douceurs',
                'displayOrder' => 7,
            ],
            [
                'name' => 'Boissons',
                'slug' => 'boissons',
                'description' => 'Boissons fraiches et cocktails',
                'displayOrder' => 8,
            ],
        ];

        $categoryEntities = [];
        foreach ($categories as $catData) {
            $category = new Category();
            $category->setName($catData['name']);
            $category->setSlug($catData['slug']);
            $category->setDescription($catData['description']);
            $category->setDisplayOrder($catData['displayOrder']);
            $category->setIsActive(true);
            $manager->persist($category);
            $categoryEntities[$catData['slug']] = $category;
        }

        // Create dishes
        $dishes = [
            // Smash Burgers
            [
                'name' => 'The Klassiker',
                'description' => 'Steak frais smashe, cheddar fondant, pickles maison, sauce klassiker, oignons grilles',
                'price' => 12.90,
                'category' => 'smash-burgers',
                'displayOrder' => 1,
                'isFeatured' => true,
            ],
            [
                'name' => 'Double Klassiker',
                'description' => 'Double steak smashe, double cheddar, bacon croustillant, sauce klassiker',
                'price' => 15.90,
                'category' => 'smash-burgers',
                'displayOrder' => 2,
                'isFeatured' => true,
            ],
            [
                'name' => 'Bacon Cheese',
                'description' => 'Steak smashe, cheddar, bacon croustillant, oignons',
                'price' => 11.90,
                'category' => 'smash-burgers',
                'displayOrder' => 3,
            ],
            [
                'name' => 'Mushroom',
                'description' => 'Steak smashe, cheddar, champignons sautes, sauce burger',
                'price' => 12.90,
                'category' => 'smash-burgers',
                'displayOrder' => 4,
            ],
            // Chicken
            [
                'name' => 'Chicken crispy',
                'description' => 'Poulet croustillant, sauce mayo, salade, tomate',
                'price' => 10.90,
                'category' => 'chicken',
                'displayOrder' => 1,
            ],
            [
                'name' => 'Nashville Hot',
                'description' => 'Poulet pane epice, pickles, sauce spicy',
                'price' => 12.90,
                'category' => 'chicken',
                'displayOrder' => 2,
            ],
            [
                'name' => 'Tenders 6pcs',
                'description' => '6 tenders de poulet, frites, sauce au choix',
                'price' => 9.90,
                'category' => 'chicken',
                'displayOrder' => 3,
            ],
            // Kebabs
            [
                'name' => 'Kebab Boeuf',
                'description' => 'Viande de boeuf, sauce au choix, salade, tomates, oignons',
                'price' => 11.90,
                'category' => 'kebabs-berlinois',
                'displayOrder' => 1,
            ],
            [
                'name' => 'Kebab Poulet',
                'description' => 'Poulet roti, sauce au choix, salade, tomates, oignons',
                'price' => 10.90,
                'category' => 'kebabs-berlinois',
                'displayOrder' => 2,
            ],
            [
                'name' => 'Doner Mixte',
                'description' => 'Melange boeuf et poulet, sauce au choix',
                'price' => 12.90,
                'category' => 'kebabs-berlinois',
                'displayOrder' => 3,
            ],
            // Box
            [
                'name' => 'Box Berlinoise',
                'description' => 'Frites, viande au choix, sauce, fromage fondant',
                'price' => 13.90,
                'category' => 'box-assiettes',
                'displayOrder' => 1,
            ],
            [
                'name' => 'Assiette Classique',
                'description' => 'Frites, steak ou poulet, legumes grilles',
                'price' => 14.90,
                'category' => 'box-assiettes',
                'displayOrder' => 2,
            ],
            // Salades
            [
                'name' => 'Salade Verte',
                'description' => 'Laitue, tomates, concombres, olives',
                'price' => 8.90,
                'category' => 'salades',
                'displayOrder' => 1,
            ],
            [
                'name' => 'Salade Poulet',
                'description' => 'Poulet grille, salade, tomates, mais, croutons',
                'price' => 12.90,
                'category' => 'salades',
                'displayOrder' => 2,
            ],
            // Sides
            [
                'name' => 'Frites Classiques',
                'description' => 'Frites croustillantes, sel, poivre',
                'price' => 3.90,
                'category' => 'sides',
                'displayOrder' => 1,
            ],
            [
                'name' => 'Frites Fromage',
                'description' => 'Frites gratinees au fromage fondant',
                'price' => 5.90,
                'category' => 'sides',
                'displayOrder' => 2,
            ],
            [
                'name' => 'Onion Rings',
                'description' => '6 anneaux d oignon frits, sauce',
                'price' => 4.90,
                'category' => 'sides',
                'displayOrder' => 3,
            ],
            [
                'name' => 'Mozzarella Sticks',
                'description' => '6 sticks de mozzarella panes, sauce marinara',
                'price' => 5.90,
                'category' => 'sides',
                'displayOrder' => 4,
            ],
            // Desserts
            [
                'name' => 'Tiramisu',
                'description' => 'Tiramisu maison au cafe',
                'price' => 5.90,
                'category' => 'desserts',
                'displayOrder' => 1,
            ],
            [
                'name' => 'Brownie Chocolat',
                'description' => 'Brownie fondant, glace vanilla',
                'price' => 5.90,
                'category' => 'desserts',
                'displayOrder' => 2,
            ],
            [
                'name' => 'Donuts',
                'description' => 'Donuts assorted, chocolat, sucre',
                'price' => 3.90,
                'category' => 'desserts',
                'displayOrder' => 3,
            ],
            // Boissons
            [
                'name' => 'Coca-Cola',
                'description' => '33cl',
                'price' => 2.50,
                'category' => 'boissons',
                'displayOrder' => 1,
            ],
            [
                'name' => 'Sprite',
                'description' => '33cl',
                'price' => 2.50,
                'category' => 'boissons',
                'displayOrder' => 2,
            ],
            [
                'name' => 'Fanta',
                'description' => '33cl',
                'price' => 2.50,
                'category' => 'boissons',
                'displayOrder' => 3,
            ],
            [
                'name' => 'Eau 50cl',
                'description' => 'Eau minerale',
                'price' => 1.50,
                'category' => 'boissons',
                'displayOrder' => 4,
            ],
            [
                'name' => 'Ice Tea',
                'description' => '33cl peche ou citron',
                'price' => 2.50,
                'category' => 'boissons',
                'displayOrder' => 5,
            ],
        ];

        foreach ($dishes as $dishData) {
            $dish = new Dish();
            $dish->setName($dishData['name']);
            $dish->setDescription($dishData['description']);
            $dish->setPrice($dishData['price']);
            $dish->setCategory($categoryEntities[$dishData['category']]);
            $dish->setDisplayOrder($dishData['displayOrder']);
            $dish->setIsActive(true);
            $dish->setIsFeatured($dishData['isFeatured'] ?? false);
            $manager->persist($dish);
        }

        // Create opening hours
        $openingHours = [
            ['dayName' => 'Lundi', 'dayOfWeek' => 1, 'morningOpen' => '11:00', 'morningClose' => '14:30', 'afternoonOpen' => '18:00', 'afternoonClose' => '23:00', 'isClosed' => false, 'displayOrder' => 1],
            ['dayName' => 'Mardi', 'dayOfWeek' => 2, 'morningOpen' => '11:00', 'morningClose' => '14:30', 'afternoonOpen' => '18:00', 'afternoonClose' => '23:00', 'isClosed' => false, 'displayOrder' => 2],
            ['dayName' => 'Mercredi', 'dayOfWeek' => 3, 'morningOpen' => '11:00', 'morningClose' => '14:30', 'afternoonOpen' => '18:00', 'afternoonClose' => '23:00', 'isClosed' => false, 'displayOrder' => 3],
            ['dayName' => 'Jeudi', 'dayOfWeek' => 4, 'morningOpen' => '11:00', 'morningClose' => '14:30', 'afternoonOpen' => '18:00', 'afternoonClose' => '23:00', 'isClosed' => false, 'displayOrder' => 4],
            ['dayName' => 'Vendredi', 'dayOfWeek' => 5, 'morningOpen' => '11:00', 'morningClose' => '14:30', 'afternoonOpen' => '18:00', 'afternoonClose' => '00:00', 'isClosed' => false, 'displayOrder' => 5],
            ['dayName' => 'Samedi', 'dayOfWeek' => 6, 'morningOpen' => '11:00', 'morningClose' => '00:00', 'afternoonOpen' => null, 'afternoonClose' => null, 'isClosed' => false, 'displayOrder' => 6],
            ['dayName' => 'Dimanche', 'dayOfWeek' => 0, 'morningOpen' => '11:00', 'morningClose' => '00:00', 'afternoonOpen' => null, 'afternoonClose' => null, 'isClosed' => false, 'displayOrder' => 7],
        ];

        foreach ($openingHours as $hoursData) {
            $hours = new OpeningHours();
            $hours->setDayName($hoursData['dayName']);
            $hours->setDayOfWeek($hoursData['dayOfWeek']);
            $hours->setMorningOpen(new \DateTime($hoursData['morningOpen']));
            $hours->setMorningClose(new \DateTime($hoursData['morningClose']));
            if ($hoursData['afternoonOpen']) {
                $hours->setAfternoonOpen(new \DateTime($hoursData['afternoonOpen']));
            }
            if ($hoursData['afternoonClose']) {
                $hours->setAfternoonClose(new \DateTime($hoursData['afternoonClose']));
            }
            $hours->setIsClosed($hoursData['isClosed']);
            $hours->setDisplayOrder($hoursData['displayOrder']);
            $manager->persist($hours);
        }

        // Create gallery photos
        $photos = [
            ['filename' => 'galerie-11.webp', 'altText' => 'Ambiance restaurant', 'displayOrder' => 1],
            ['filename' => 'galerie-12.webp', 'altText' => 'Cuisine', 'displayOrder' => 2],
            ['filename' => 'galerie-13.webp', 'altText' => 'Burgers', 'displayOrder' => 3],
            ['filename' => 'galerie-14.webp', 'altText' => 'Salle', 'displayOrder' => 4],
            ['filename' => 'galerie-15.webp', 'altText' => 'Equipe', 'displayOrder' => 5],
        ];

        foreach ($photos as $photoData) {
            $photo = new GalleryPhoto();
            $photo->setFilename($photoData['filename']);
            $photo->setAltText($photoData['altText']);
            $photo->setDisplayOrder($photoData['displayOrder']);
            $photo->setIsActive(true);
            $manager->persist($photo);
        }

        $manager->flush();
    }
}

