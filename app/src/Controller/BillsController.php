<?php

namespace App\Controller;

use App\Entity\Bills;
use App\Repository\AddressesRepository;
use App\Repository\BillLinesRepository;
use App\Repository\BillsRepository;
use App\Repository\BookingsRepository;
use Cassandra\Date;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;
use Knp\Snappy\Pdf;

#[Route('/admin')]
class BillsController extends AbstractController
{
    private BillLinesRepository $linesRepo;
    private BookingsRepository $bookingRepo;
    private BillsRepository $billRepo;
    private AddressesRepository $adressRepo;

    public function __construct(BillLinesRepository $linesRepo,
                                BookingsRepository $bookingRepo,
                                BillsRepository $billRepo,
                                AddressesRepository $adressRepo)
    {
        $this->linesRepo   = $linesRepo;
        $this->bookingRepo = $bookingRepo;
        $this->billRepo    = $billRepo;
        $this->adressRepo  = $adressRepo;
    }

    /**
     * Liste de toutes les factures
     */
    #[Route('/billList', name: 'app_bills')]
    public function index(): Response
    {

        $bills = $this->billRepo->findAll();

        return $this->render('admin/bills_list.html.twig', [
            'bills' => $bills,
        ]);
    }

    /**
     * Donne le details de la facture
     */
    #[Route('/billDetails/{id}', name: 'bill_details', requirements: ['id' => '\d+'])]
    public function details($id): Response
    {
        $bill = $this->billRepo->find($id);
        $address = $this->adressRepo->find($bill->getAddressId());
        $nb_days = ($bill->getCheckOut()->getTimestamp() - $bill->getCheckIn()->getTimestamp()) / (60*60*24);

        return $this->render('bills/content.html.twig', [
            'bill' => $bill,
            'address' => $address,
            'days' => $nb_days,
        ]);
    }

    /**
     * G??nere la facture
     */
    #[Route('/billGenerate/{id}', name: 'bill_generate', requirements: ['id' => '\d+'])]
    public function generate(ManagerRegistry $manager, Pdf $pdf, $id = 0): Response
    {
        //date
        $date = date(DATE_ATOM,mktime(time()));

        // si l'id de la r??servation existe
        if($id > 0) {
            // On r??cup??re la r??servation
            $booking = $this->bookingRepo->find($id);

            //On r??cup??re le produit (mobile-home, emplecement ou caravane)
            $product = $booking->getProductId();

            //On r??cup??re le client
            $client = $booking->getClientId();

            //On r??cup??re son adresse
            $address = $client->getAddressId();

            //On r??cup??re les lignes de facturation
            $lines = $this->linesRepo->findAllLines($id);

            //On r??cup??re la facture d??j?? g??n??r??e ou on en cr??e une nouvelle
            $bill = $this->billRepo
                    ->findOneBy([
                        'product_id' => $product->getId(),
                        'check_in' => $booking->getCheckIn()
                    ]) ?? new Bills() ;

            $rental     = $lines[0];
            $poolAdult  = $lines[1];
            $poolKid    = $lines[2];
            $tsAdult    = $lines[3];
            $tsKid      = $lines[4];
            //cr??ation de la facture

                //identification du client

            //Enregistrement de la facture
            $em = $manager->getManager();
            $em->persist($bill);
            $em->flush();

            $pdf->generateFromHtml(
                $this->renderView(
                    'bills/pdf_template.html.twig',
                    [
                        'rental' => $rental,
                        'poolAdult' => $poolAdult,
                        'poolKid' => $poolKid,
                        'tsAdult' => $tsAdult,
                        'tskid' => $tsKid,
                    ]),
                    'bills/bill_'.$id.'.pdf'
            );
        }
        //return $this->redirectToRoute('bookingsList');
        dump($rental);
        return $this->render('bills/pdf_template.html.twig',
            [
                'rental' => $rental,
                'poolAdult' => $poolAdult,
                'poolKid' => $poolKid,
                'tsAdult' => $tsAdult,
                'tskid' => $tsKid,
            ]
        );
    }

}
