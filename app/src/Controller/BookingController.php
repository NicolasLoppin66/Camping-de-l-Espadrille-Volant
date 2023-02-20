<?php

namespace App\Controller;

use App\Entity\Addresses;
use App\Entity\BillLines;
use App\Entity\Bookings;
use App\Entity\Clients;
use App\Entity\Owners;
use App\Form\AddresseType;
use App\Form\BookingType;
use App\Form\ClientType;
use App\Repository\AddressesRepository;
use App\Repository\BillLinesRepository;
use App\Repository\BookingsRepository;
use App\Repository\ClientsRepository;
use App\Repository\DisponibilitesRepository;
use App\Repository\ExtraChargesRepository;
use App\Repository\ProductsRepository;
use App\Repository\RentalsTypesRepository;
use App\Repository\SessionPeriodsRepository;
use DateTime;
use Doctrine\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class BookingController extends AbstractController
{
    private BookingsRepository $bookingRepo;
    private ClientsRepository $clientsRepo;
    private AddressesRepository $addressesRepo;
    private SessionPeriodsRepository $seasonRepo;
    private DisponibilitesRepository $dispoRepo;
    private ExtraChargesRepository $chargesRepo;
    private RentalsTypesRepository $rentalTypesRepo;
    private BillLinesRepository $linesRepo;
    private ProductsRepository $productsRepo;

    public function __construct(
        BookingsRepository $bookingRepo,
        ClientsRepository $clientsRepo,
        AddressesRepository $addressesRepo,
        SessionPeriodsRepository $seasonRepo,
        DisponibilitesRepository $dispoRepo,
        ExtraChargesRepository $chargesRepo,
        RentalsTypesRepository $rentalTypesRepo,
        BillLinesRepository $linesRepo,
        ProductsRepository $productsRepo
    )
    {
        $this->bookingRepo = $bookingRepo;
        $this->clientsRepo = $clientsRepo;
        $this->addressesRepo = $addressesRepo;
        $this->seasonRepo = $seasonRepo;
        $this->dispoRepo = $dispoRepo;
        $this->chargesRepo = $chargesRepo;
        $this->rentalTypesRepo = $rentalTypesRepo;
        $this->linesRepo = $linesRepo;
        $this->productsRepo = $productsRepo;
    }

    #[Route("/admin/bookingsList", name: "bookingsList")]
    #[IsGranted('ROLE_ADMIN')]
    public function bookingsList(): Response
    {
        $bookings = $this->bookingRepo->findAll();
        return $this->render('admin/booking_list.html.twig', [
            'bookings' => $bookings,
        ]);
    }

    #[Route("/profile/bookingsOwner/{id}", name: "bookings_owner")]
    #[IsGranted('ROLE_OWNER')]
    public function bookingsOwnersList($id): Response
    {
        $bookings = $this->bookingRepo->findAllByOwnerId($id);

        $mb_bookings = [];
        $bookingsForOneMb = [$bookings[0]];
        $owner = new Owners();

        for ($i = 1; $i < count($bookings); $i++) {

            if ($bookings[$i]->getProductId()->getId() == $bookings[$i - 1]->getProductId()->getId()) {
                $bookingsForOneMb[] = $bookings[$i];
                if ($i == count($bookings) - 1)
                    $mb_bookings[] = $bookingsForOneMb;
            } else {
                $mb_bookings[] = $bookingsForOneMb;
                $bookingsForOneMb = [$bookings[$i]];
                if ($i == count($bookings) - 1)
                    $mb_bookings[] = $bookingsForOneMb;
            }


        }
        //dump($mb_bookings);
        return $this->render('owner/bookings_list.html.twig', [
            'mb_bookings' => $mb_bookings,
        ]);
    }


    /**
     * Détail de la location
     */
    #[Route('/admin/bookingDetails/{id}', name: 'booking_details', requirements: ['id' => '\d+'])]
    public function bookingDetails($id): Response
    {
        $booking = $this->bookingRepo->find($id);
        $lines = $this->linesRepo->findAllLinesForOneBooking($id);
        $total = 0;

        foreach ($lines as $line) {
            $total += $line->getPu() * $line->getQuantity();
        }

        return $this->render('admin/booking_details.html.twig', [
            'booking' => $booking,
            'lines' => $lines,
            'total' => $total,
        ]);
    }

    #[Route('/newBooking', name: 'new_booking')]
    public function newBooking(ManagerRegistry $manager, Request $request)
    {
        $em = $manager->getManager();

        // Création d'une nouvelle entité client + adresse
        $booking = new Bookings();
        $client = new Clients();
        $address = new Addresses();

        //Création du formulaire
        $bookingForm = $this->createForm(BookingType::class, $booking);
        $clientForm = $this->createForm(ClientType::class, $client);
        $addressForm = $this->createForm(AddresseType::class, $address);

        //Récupération des données
        $bookingForm->handleRequest($request);
        $clientForm->handleRequest($request);
        $addressForm->handleRequest($request);

        //Traitement des données
        if (
            $clientForm->isSubmitted() && $clientForm->isValid()
            && $addressForm->isSubmitted() && $addressForm->isValid()
            && $bookingForm->isSubmitted() && $bookingForm->isValid()
        ) {
            //mise à jour du booking
            $booking = $this->fillBookingProperties($booking, $bookingForm);

            //Dates d'arrivée et de départ
            $checkin = $booking->getCheckIn()->format('Y-m-d');
            $checkout = $booking->getCheckOut()->format('Y-m-d');

            //vérification de la validité des dates : date d'arrivée < date de départ et les deux dates sont valide :
            $datesAreValid = $this->dateIsValid($checkin, $checkout);
            if (!$datesAreValid) {
                $this->addFlash('success', 'Merci de saisir des dates correctes');
                return $this->redirectToRoute('booking_add');
            }

            //tableau de tous les jours de l'intervalle de dates
            $all_nights = $this->bookingRepo->daysBetween2Dates($checkin, $checkout);

            //Vérification des disponibilités
            $product_id = $booking->getProductId()->getId();
            $is_available = $this->isAvailable($product_id, $checkin, $checkout);
            if (!$is_available) {
                $this->addFlash('success', '1 - Il y a déjà une réservation pour cette période !');
                return $this->redirectToRoute('booking_add');
            }

            //nombre de nuitées
            $highSeasonNights = $this->hightSeasonNights($all_nights);
            $lowSeasonNights = $this->lowSeasonNights($highSeasonNights, $all_nights);


            //1 - vérification / enregistrement de l'adresse
            $addressExists = $this->addressIsExisting($addressForm);
            if (!is_null($addressExists))
                $address = $addressExists;
            else {
                $address = $this->createAddressFromForm($addressForm);
                $em->persist($address);
            }

            // 2 - vérification de l'existence du client :
            $clientIsExisting = $this->clientIsExisting($clientForm);

            if (!is_null($clientIsExisting))
                $client = $clientIsExisting;
            else
                $client = $this->createNewClientWithoutAddressNorDeleteDate($clientForm);

            //on complète le profil du client avec l'adresse et la date de suppression des données
            $consentRetentionDatas = $clientForm->get('dataRetentionConsent')->getNormData();
            $client->setDataRetentionConsent($consentRetentionDatas);
            if ($consentRetentionDatas)
                $client->setEraseDataDay(new DateTime('2023-12-31'));

            $em->persist($client);
            $em->flush();

            //mise à jour du booking
            $booking->setClientId($client);

            // 3 - création des lignes de facturation
            $billLines = $this->billLines($bookingForm, $highSeasonNights, $lowSeasonNights);

            // 4 - enregistrement de la réservation
            $em->persist($booking);
            $em->flush();

            // 5 - enregistrement des lignes de facturation
            foreach ($billLines as $line) {
                $billLine = new BillLines();
                $billLine->setLabel($line["label"])
                    ->setPu($line["pu"])
                    ->setQuantity($line["q"])
                    ->setBookingId($booking->getId());

                $em->persist($billLine);
            }

            // 6 - enregistrement des nuitées dans la table de dispo avec $all_nights
            $dispos = $this->dispoRepo->allDispoForOneProduct($booking->getProductId()->getId(), $checkin, $checkout);
            foreach ($dispos as $night) {
                $night->setIsBooked(true);
                $em = $manager->getManager();
                $em->persist($night);
            }
            $em->flush();
            $this->addFlash('success', 'Bravo votre réservation est bien enregistrée !');
            return $this->redirectToRoute('bookingsList');
        } else {
            return $this->render('front/booking.html.twig', [
                'booking' => $bookingForm->createView(),
                'client' => $clientForm->createView(),
                'address' => $addressForm->createView(),
            ]);
        }



    }

    #[Route('/booking_update/{id}', name: 'booking_update')]
    public function updateBooking(ManagerRegistry $manager, Request $request, int $id)
    {
        //todo: rajouter le discount
        $em = $manager->getManager();
        $booking = $this->bookingRepo->find($id);

        //si le formulaire est soumis -> vérification et mise à jour des infos
        //Création du formulaire
        $bookingForm = $this->createForm(BookingType::class, $booking);

        //Récupération des données
        $bookingForm->handleRequest($request);

        //Si le formulaire est valide
        if ($bookingForm->isSubmitted() && $bookingForm->isValid()) {
            // Modification des champs de la résa (sauf dates + produit)
            $booking->setNbAdults($bookingForm->get('nb_adults')->getNormData())
                ->setNbKids($bookingForm->get('nb_kids')->getNormData())
                ->setPoolAccessAdults($bookingForm->get('pool_access_adults')->getNormData())
                ->setPoolAccessKids($bookingForm->get('pool_access_kids')->getNormData());

            $em->persist($booking);
            $em->flush();

            // Modification des lignes de facturation
            $billLines = $this->linesRepo->findAllLinesForOneBooking($id);
            $nb_nights = 0;
            //todo:trouver une meilleure méthode
            foreach ($billLines as $line) {
                if ($line->getLabel() === 'Nuitées basse saison')
                    $nb_nights += $line->getQuantity();
                if ($line->getLabel() === 'Nuitées haute saison')
                    $nb_nights += $line->getQuantity();
                if ($line->getLabel() === 'Entrées piscine Adultes')
                    $line->setQuantity($booking->getPoolAccessAdults());
                if ($line->getLabel() === 'Entrées piscine Enfants')
                    $line->setQuantity($booking->getPoolAccessKids());
                if ($line->getLabel() === 'Taxe de séjour Adultes')
                    $line->setQuantity($booking->getNbAdults() * $nb_nights);
                if ($line->getLabel() === 'Taxe de séjour Enfants')
                    $line->setQuantity($booking->getNbKids() * $nb_nights);

                $em->persist($line);
            }

            $em->flush();

            return $this->redirectToRoute('booking_details', ['id' => $id]);

        } else {
            return $this->render('admin/booking_update.html.twig', [
                'booking' => $booking,
                'bookingForm' => $bookingForm->createView(),
            ]);
        }

    }

    #[Route("/delBooking/{id}", name: "booking_del", requirements: ["id" => "\d+"])]
    public function deleteBooking($id)
    {

    }

    /**
     * Retourne false si une des dates est nulle ou si la date de départ < date d'arrivée
     */
    public function dateIsValid($checkin, $checkout): bool
    {
        if (
            strtotime($checkout) - strtotime($checkin) > (24 * 60 * 60)
            && !is_null($this->bookingRepo->checkAndGetDate($checkout))
            && !is_null($this->bookingRepo->checkAndGetDate($checkin))
        ) {
            return true;
        }
        return false;
    }

    /**
     * Vérifie si les dates sont cohérentes (date d'arrivée < date de départ) sinon retourne false
     * Vérifie si les dates demandées sont disponibles sinon retourne false
     * Si aucun problème, retourne true
     */
    public function isAvailable($productId, $checkin, $checkout): bool
    {
        //tableau de tous les jours de l'intervalle de dates
        $all_nights = $this->bookingRepo->daysBetween2Dates($checkin, $checkout);

        // Récupération des dispos (tableau des jours où is_booked = false)
        $dispos = $this->dispoRepo->allDispoForOneProduct($productId, $checkin, $checkout);

        //si le nb de nuitées demandées et ne nb de dispo ne correspondent pas
        if (count($dispos) != count($all_nights))
            return false;
        else {
            //comparaison de chaque nuitée
            for ($i = 0; $i < count($all_nights); $i++) {
                if ($dispos[$i]->getDay() !== $all_nights[$i])
                    return false;
            }
        }
        return true;
    }

    /**
     * Retourne le nombre de nuitées dans la période de haute saison
     */
    public function hightSeasonNights(array $all_nights): int
    {
        return count(array_filter($all_nights, function ($date) {
            $season_begin = strtotime($this->seasonRepo->find(2)->getBegin()->format('d-m-Y'));
            $season_end = strtotime($this->seasonRepo->find(2)->getEnd()->format('d-m-Y'));
            return strtotime($date) >= $season_begin && strtotime($date) <= $season_end;
        }));
    }

    /**
     * Retourne le nombre de nuitées dans la période de basse saison
     */
    public function lowSeasonNights(int $high_season_nights, array $all_nights): int
    {
        return count($all_nights) - $high_season_nights;
    }

    /**
     * Retourne true si l'adresse du formaulaire passé en argument existe dans la base de donnée
     */
    public function addressIsExisting($address): ?Addresses
    {
        return $this->addressesRepo->findOneBy([
            'num' => $address->get('num')->getNormData(),
            'road_name' => $address->get('road_type')->getNormData(),
            'zip' => $address->get('zip')->getNormData(),
        ]);
    }

    /**
     * Retourne une entité Addresses créée à partir d'un formulaire d'adresse
     */
    public function createAddressFromForm($form_address): Addresses
    {
        $address = new Addresses();
        $address->setNum($form_address->get('num')->getNormData())
            ->setRoadType($form_address->get('road_type')->getNormData())
            ->setRoadName($form_address->get('road_name')->getNormData())
            ->setZip($form_address->get('zip')->getNormData())
            ->setCity($form_address->get('city')->getNormData());

        return $address;
    }


    public function clientIsExisting($client): Clients
    {
        return $this->clientsRepo->findOneBy(['email' => $client->get('email')->getNormData()]);
    }

    public function createNewClientWithoutAddressNorDeleteDate($form_client): Clients
    {
        $client = new Clients();
        $client->setFirstname($form_client->get('firstname')->getNormData())
            ->setLastname($form_client->get('firstname')->getNormData())
            ->setTelephone($form_client->get('firstname')->getNormData())
            ->setEmail($form_client->get('firstname')->getNormData());
        return $client;
    }

    /**
     * retourne le tableau des lignes de facturation à partir du formulaire de réservation et des nuits haute
     * et basse saison fournis en arguments
     */
    public function billLines($bookingForm, $high_season_nights, $low_season_nights): array
    {
        $majHighSeason = $this->seasonRepo->findOneBy(['begin' => new DateTime('2022-06-21')]);
        $nbDaysPoolAdults = $bookingForm->get('pool_access_adults')->getNormData();
        $nbDaysPoolKids = $bookingForm->get('pool_access_kids')->getNormData();
        $product_id = $bookingForm->get('product_id')->getNormData();
        $rentalType = $this->productsRepo->find($product_id)->getRentalType();

        $billLines = [
            'lowSeasonNights' => [
                'label' => 'Nuitées basse saison',
                'q' => $low_season_nights,
                'pu' => $rentalType->getPrice()
            ],
            'highSeasonNights' => [
                'label' => 'Nuitées haute saison',
                'q' => $high_season_nights,
                'pu' => $rentalType->getPrice() * (100 + $majHighSeason->getIncrease()) / 100
            ],
            'poolAdult' => [
                'label' => 'Entrées piscine Adultes',
                'q' => $nbDaysPoolAdults,
                'pu' => $this->chargesRepo->findOneBy(['label' => 'Piscine'])->getAmountAdults()
            ],
            'poolKid' => [
                'label' => 'Entrées piscine Enfants',
                'q' => $nbDaysPoolKids,
                'pu' => $this->chargesRepo->findOneBy(['label' => 'Piscine'])->getAmountKids()
            ],
            'TSAdult' => [
                'label' => 'Taxe de séjour Adultes',
                'q' => ($high_season_nights + $low_season_nights),
                'pu' => $this->chargesRepo->findOneBy(['label' => 'Taxe de séjour'])->getAmountAdults()
            ],
            'TSKid' => [
                'label' => 'Taxe de séjour Enfants',
                'q' => ($high_season_nights + $low_season_nights),
                'pu' => $this->chargesRepo->findOneBy(['label' => 'Taxe de séjour'])->getAmountKids()
            ],
        ];

        return $billLines;
    }

    public function fillBookingProperties(Bookings $booking, $bookform): Bookings
    {
        $booking->setNbAdults($bookform->get('nb_adults')->getNormData())
            ->setNbKids($bookform->get('nb_kids')->getNormData())
            ->setCheckIn($bookform->get('check_in')->getNormData())
            ->setCheckOut($bookform->get('check_out')->getNormData())
            ->setPoolAccessAdults($bookform->get('pool_access_adults')->getNormData())
            ->setPoolAccessKids($bookform->get('pool_access_kids')->getNormData())
            ->setDiscount(0)

            ->setProductId($bookform->get('product_id')->getNormData());

        return $booking;
    }
}