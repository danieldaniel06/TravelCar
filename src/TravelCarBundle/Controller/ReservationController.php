<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace TravelCarBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use  \TravelCarBundle\Entity\Reservation;
use TravelCarBundle\Entity\Advert;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
/**
 * Description of ReservationController
 *
 * @author danielahmed
 */
class ReservationController extends Controller{
    
    
    /**
     * 
     * @Security("has_role('ROLE_USER')")
     */
    public function addAction(Request $request){
        
        if($request->isMethod('POST')){
            $advertId = $request->get('advertId');
            
            $nbOfReservation = $request->get('nbOfReservation');
            
            $advert = $this->getDoctrine()
                ->getRepository('TravelCarBundle:Advert')
                ->find($advertId);

            $canAdd = TRUE;
            if($nbOfReservation<1){
    
                $request->getSession()->getFlashBag()->add('notice', 'Nombre positif attendu');
                $canAdd = FALSE;
            }
            
            if($nbOfReservation > $advert->getNumberOfPlace()){
                $request->getSession()->getFlashBag()->add('notice', 'Nombre inferieur attendu');
                $canAdd = FALSE;
            }
            
            $reservation = $this->getDoctrine()
                ->getRepository('TravelCarBundle:Reservation')
                ->findOneBy(array('advert'=>$advert, 'user'=>$this->getUser()));

            if($reservation){
                $request->getSession()->getFlashBag()->add('notice', 'Reservation existe');
                $canAdd = FALSE;
            }

            if($canAdd){
                $reservation = new Reservation();
            
                $reservation->setNumberOfPlace($nbOfReservation)->setAdvert($advert)->setUser($this->getUser());

                $em = $this->getDoctrine()->getManager();

                $em->persist($reservation);

                $em->flush();
            }
            
            return $this->redirectToRoute('view_advert', array(
            'id' => $advertId,
            ));
            
        }
        
        return $this->redirectToRoute('home');
    }
    
    /**
     * @ParamConverter("advert", options={"id": "advertId"})
     * @Security("has_role('ROLE_USER')")
     */
    public function removeAction(Advert $advert, Request $request){
        
        $user = $this->getUser();
        
        $reservation = $this->getDoctrine()
                ->getRepository('TravelCarBundle:Reservation')
                ->findOneBy(array('advert'=>$advert,'user'=> $user));

        if($reservation){
            $reservation->deleteReservation();
            $em = $this->getDoctrine()->getManager();
            $em->remove($reservation);
            $em->flush();
        }else{
            throw $this->createNotFoundException('Permission non accordée');
        }

        return $this->redirectToRoute('view_advert', array(
        'id' => $advert->getId()
        ));
    }
    
    /**
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function viewAllAction(){
        $reservations = $this->getDoctrine()
                    ->getRepository('TravelCarBundle:Reservation')
                    ->findAll();
    }
    
    /**
     * 
     * @Security("has_role('ROLE_USER')")
     */
    public function viewAllMyAction(Request $request){
        $reservations = $this->getDoctrine()
                    ->getRepository('TravelCarBundle:Reservation')
                    ->findBy(array('user'=>$this->getUser()));
        
    }
}
