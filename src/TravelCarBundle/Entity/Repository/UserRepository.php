<?php

namespace TravelCarBundle\Entity\Repository;

use TravelCarBundle\Entity\User;

/**
 * UserRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class UserRepository extends \Doctrine\ORM\EntityRepository
{

    public function remove(User $user)
    {
        $em = $this->getEntityManager();
        $adverts = $em->getRepository('TravelCarBundle:Advert')
            ->findBy(array('user'=>$user->getId()));
        $reservations = $em->getRepository('TravelCarBundle:Reservation')
            ->findBy(array('user'=>$user->getId()));
        $posts = $em->getRepository('TravelCarBundle:Post')
            ->findBy(array('user'=>$user->getId()));

        foreach ($posts as $post) {
            $em->remove($post);
        }
        foreach ($adverts as $advert) {
            $em->remove($advert);
        }
        foreach ($reservations as $reservation) {
            $em->remove($reservation);
        }
        $em->flush();
        $em->remove($user);
        $em->flush();
    }
}
