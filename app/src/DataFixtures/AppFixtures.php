<?php

namespace App\DataFixtures;

use Faker;
use DateTime;
use DateInterval;
use App\Entity\Owners;
use App\Entity\Clients;
use App\Entity\Bookings;
use App\Entity\Products;
use App\Entity\Addresses;
use App\Entity\BillLines;
use App\Entity\ExtraCharges;
use App\Entity\RentalsTypes;
use App\Entity\Disponibilites;
use App\Entity\OwnersContracts;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasher;

class AppFixtures extends Fixture
{
    private $hasher;

    public function __construct(UserPasswordHasherInterface $hasher) {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        // Récupération du module de randomisation
        $faker = Faker\Factory::create('fr_FR');

        // Partie Utilisateur
        // Création des address de façon aléatoire
        $road = ['rue', 'avenue', 'place', 'boulevard', 'impasse'];
        for ($a = 0; $a < 51; $a++) {
            $address = new Addresses();
            $address->setCity($faker->city)
                ->setRoadType($road[rand(0, count($road) - 1)])
                ->setRoadName($faker->name)
                ->setZip(rand(1000, 9999) * 10)
                ->setNum(rand(1, 300));

            $manager->persist($address);
            $this->addReference('address-' . $a, $address);
        }

        // Création des client de façon aléatoire
        for ($c = 0; $c < 20; $c++) {
            $client = new Clients();
            $client->setAddressId($this->getReference('address-' . rand(1, 50)))
                ->setDataRetentionConsent(rand(0, 1))
                ->setEmail($faker->email)
                ->setEraseDataDay($faker->dateTime)
                ->setFirstName($faker->firstName)
                ->setLastName($faker->lastName)
                ->setTelephone($faker->phoneNumber);

            $manager->persist($client);
            $this->addReference('client-' . $c, $client);
        }

        // Partie propriétaire
        // Création du compte admin
        $camping = new Owners();

        $camping->setAddressId($this->getReference('address-1'))
            ->setDateRetentionConsent(rand(0, 1))
            ->setEmail('camping-lespadrillevolante@contact.fr')
            ->setFirstName('SAS')
            ->setLastName("l'Espadrille Volante ⭐⭐⭐")
            ->setTelephone($faker->phoneNumber)
            ->setPassword($this->hasher->hashPassword($camping, 'admin'))
            ->setRole('ROLE_ADMIN');

        $manager->persist($camping);
        $this->addReference('owner-1', $camping);

        for ($o = 0; $o > 20; $o++) {
            $owner = new Owners();

            $owner->setAddressId($this->getReference('address-' . rand(2, 50)))
                ->setDateRetentionConsent(rand(0, 1))
                ->setEmail($faker->email)
                ->setFirstName($faker->firstName)
                ->setLastName($faker->lastName)
                ->setTelephone($faker->phoneNumber)
                ->setPassword($this->hasher->hashPassword($camping, 'owner'))
                ->setRole('ROLE_OWNER');

            $manager->persist($owner);
            $this->addReference('owner-' . ($o + 1), $owner);
        }

        // Création des contracts de façon aléatoire
        for ($c = 0; $c < 30; $c++) {
            $contract = new OwnersContracts();
            $contract->setProductId(rand(1, 30));
        }

        // Création des charge supplémentaire de façon aléatoire
        $label = ['Access Piscine', 'Taxe de séjour'];

        $adult = [1.5, 0.6];
        $kid = [1, 0.35];

        for ($ex = 0; $ex < count($label); $ex++) {
            $charges = new ExtraCharges();
            $charges->setLabel($label[$ex])
                ->setAmountAdults($adult[$ex])
                ->setAmountKids($kid[$ex]);

            $manager->persist($charges);
        }

        //Type d'hébergement
        $label = ['emplacement 8m2', 'emplacement 12m2', 'mobile-home 3P', 'mobile-home 4P', 'mobile-home 5P', 'mobile-home 6-8P', 'caravane 2P', 'caravane 4P', 'caravane 6P'];
        $capacity = ['8', '12', '3', '4', '5', '6-8', '2', '4', '6'];
        $price = [12, 14, 20, 24, 27, 34, 15, 18, 24];
        for ($th = 0; $th < count($label); $th++) {
            $rental = new RentalsTypes();
            $rental->setLabel($label[$th])
                ->setCapacity($capacity[$th])
                ->setPrice($price[$th]);

            $manager->persist($rental);
            $this->addReference('type-' . $th, $rental);
        }

        // Parti Produits
        // emplacements
        for ($i = 0; $i < 30; $i++) {
            $product = new Products();
            $product->setOwnerId($this->getReference('owner-1'))
                ->setRentalType($this->getReference('type-' . rand(0, 1)))
                ->setLabel($faker->sentence(8, false))
                ->setDescription(($faker->sentence(100)));

            $manager->persist($product);
            $this->addReference('product-' . $i, $product);
        }

        // caravanes
        for ($i = 0; $i < 10; $i++) {
            $product = new Products();
            $product->setOwnerId($this->getReference('owner-1'))
                ->setRentalType($this->getReference('type-' . rand(2, 5)))
                ->setLabel($faker->sentence(8))
                ->setDescription(($faker->sentence(100)));

            $manager->persist($product);
            $this->addReference('product-' . ($i + 30), $product);

        }

        //mobiles-homes
        for ($i = 0; $i < 50; $i++) {
            $product = new Products();
            $product->setOwnerId($this->getReference('owner-' . rand(1, 20)))
                ->setRentalType($this->getReference('type-' . rand(6, 8)))
                ->setLabel($faker->sentence(15))
                ->setDescription(($faker->sentence(100)));

            $manager->persist($product);
            $this->addReference('product-' . ($i + 40), $product);

        }

        //réservations et lignes de facturation
        for ($i = 0; $i < 100; $i++) {
            $booking = new Bookings();
            $adults = rand(1, 4);
            $kids = rand(0, 3);
            $days = rand(5, 18);
            $nb_days = '+' . $days . ' days';

            $check_in = $faker->dateTimeInInterval('-1 week', '+12 weeks');
            $check_out = $faker->dateTimeInInterval($check_in, $nb_days);

            $nb_poolAccessAdult = rand(0, $adults);
            $nb_poolAccessKids = rand(0, $kids);

            $product = $this->getReference('product-' . rand(0, 89));

            $booking->setClientId($this->getReference('client-' . rand(0, 19)))
                ->setNbAdults($adults)
                ->setNbKids($kids)
                ->setPoolAccessAdults($nb_poolAccessAdult)
                ->setPoolAccessKids($nb_poolAccessKids)
                ->setProductId($product)
                ->setCheckIn($check_in)
                ->setCheckOut($check_out)
                ->setDiscount(0);

            // Lignes de faturation

            //Montant de la location
            $bill_rental = new BillLines();
            $bill_rental->setBookingId($i)
                ->setLabel('Prix location (par jour)')
                ->setQuantity($days)
                ->setPu(rand(12, 34));

            //Piscine adulte
            $bill_adult_pool = new BillLines();
            $bill_adult_pool->setBookingId($i)
                ->setLabel('Piscine adulte (par jour et par personne)')
                ->setQuantity($nb_poolAccessAdult)
                ->setPu(1.5);

            //Piscine Enfant
            $bill_Kid_pool = new BillLines();
            $bill_Kid_pool->setBookingId($i)
                ->setLabel('Piscine enfant (par jour et par personne)')
                ->setQuantity($nb_poolAccessKids)
                ->setPu(1);

            //Taxe de séjour Adulte
            $bill_adult_tax = new BillLines();
            $bill_adult_tax->setBookingId($i)
                ->setLabel('Taxe de séjour Adulte')
                ->setQuantity($days)
                ->setPu(.6);

            //Taxe de séjour enfant
            $bill_kid_tax = new BillLines();
            $bill_kid_tax->setBookingId($i)
                ->setLabel('Taxe de séjour Enfant')
                ->setQuantity($days)
                ->setPu(.35);

            $manager->persist($booking);

            $manager->persist($bill_rental);
            $manager->persist($bill_adult_pool);
            $manager->persist($bill_Kid_pool);
            $manager->persist($bill_adult_tax);
            $manager->persist($bill_kid_tax);
        }


        //disponibilites
        $begin = new DateTime('2022-05-05');
        $end = new DateTime('2022-10-10');
        $interval = DateInterval::createFromDateString('1 day');
        $dates_in_interval = new \DatePeriod($begin, $interval, $end);

        for ($i = 0; $i < 50; $i++) {
            foreach ($dates_in_interval as $date) {
                $dispo = new Disponibilites();
                $dispo->setProductId($this->getReference('product-' . rand(0, 89)))
                    ->setDay($date->format('Y-m-d'))
                    ->setIsBooked(false);

                $manager->persist($dispo);
            }
        }

        $manager->flush();
    }

}