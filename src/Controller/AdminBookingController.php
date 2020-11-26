<?php

namespace App\Controller;

use App\Entity\Booking;
use App\Form\AdminBookingType;
use App\Repository\BookingRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class AdminBookingController extends AbstractController
{
    /**
     * @Route("/admin/bookings", name="admin_booking_index")
     * 
     * @return Response
     */
    public function index(BookingRepository $repo)
    {
        return $this->render('admin/booking/index.html.twig', [
            'bookings' => $repo->findAll()
        ]);
    }
    /**
     * Permet d'afficher le formulaire d'édition d'une reservation 
     * 
     * @Route("/admin/bookings/{id}/edit", name="admin_booking_edit")
     * 
     * @return Response
     */
    public function edit(Booking $booking, Request $request, EntityManagerInterface $manager)
    {
        $form = $this->createForm(AdminBookingType::class, $booking);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $booking->setAmount(0);

            $manager->persist($booking);
            $manager->flush();

            $this->addFlash(
                'success',
                "La reservation n&deg;{$booking->getId()} a bien été modifié"
            );
        }

        return $this->render('admin/booking/edit.html.twig', [
            'form' => $form->createView(),
            'booking' => $booking
        ]);
    }
    /**
     * Permet de supprimer une reservation
     * 
     * @Route("/admin/bookings/{id}/delete", name="admin_booking_delete")
     * 
     * @param EntityManagerInterface $manager
     * @param Booking $booking
     * @return Response
     */
    public function delete(Booking $booking, EntityManagerInterface $manager)
    {
        $manager->remove($booking);
        $manager->flush();

        $this->addFlash(
            'success',
            "La reservation n&deg;{$booking->getId()} a bien été supprimer"
        );

        return $this->redirectToRoute('admin_booking_index');
    }
}
