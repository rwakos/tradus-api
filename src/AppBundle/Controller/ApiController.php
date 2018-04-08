<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\View\View;
use AppBundle\Entity\Offer;

class ApiController extends FOSRestController {
    private $len_title = 80;
    private $len_description = 255;
    private $len_email = 80;
    private $len_image = 255;

    /**
     * @Rest\Get("/offers")
     * @return JsonResponse
     */
    public function getOffers()
    {
        $em = $this->getDoctrine()->getManager();
        $offers = $em->getRepository('AppBundle:Offer')
            ->findAll();

        if (($offers === null)||(empty($offers))) {
            return new View("THERE ARE NO OFFERS", Response::HTTP_NOT_FOUND);
        }
        return $offers;
    }

    /**
     * @Rest\Get("/offers/{id}")
     * @param $id int
     * @return JsonResponse
     */
    public function getOffer($id)
    {
        $singleresult = $this->getDoctrine()->getRepository('AppBundle:Offer')->find($id);
        if (($singleresult === null)||(empty($singleresult))) {
            return new View("OFFER NOT FOUND", Response::HTTP_NOT_FOUND);
        }
        return $singleresult;
    }

    /**
     * @Rest\Get("/search/offers/{search}")
     * @param $search string
     * @return JsonResponse
     */
    public function searchOffers($search)
    {
        $s = '%'.str_replace(" ","%",$search).'%';
        $repository = $this->getDoctrine()
            ->getRepository('AppBundle:Offer');

        $query = $repository->createQueryBuilder('o')
            ->where('o.title LIKE :search')
            ->orWhere('o.description LIKE :search')
            ->orWhere('o.email LIKE :search')
            ->setParameter('search', $s)
            ->orderBy('o.id', 'DESC')
            ->getQuery();

        $offers = $query->getResult();

        if (($offers === null)||(empty($offers))) {
            return new View("THERE ARE NO OFFERS MATCHING THAT SEARCH", Response::HTTP_NOT_FOUND);
        }
        return $offers;
    }

    /**
     * @Rest\Post("/offer")
     * @param $request Request
     * @return JsonResponse
     */
    public function storeOffer(Request $request)
    {
        //Validations
        $title = substr($request->get('title'),0,$this->len_title);
        $description = substr($request->get('description'),0,$this->len_description);
        $email = substr($request->get('email'),0,$this->len_email);
        $image = substr($request->get('image'),0,$this->len_image);

        if(empty($title) || empty($description) || empty($email) || empty($image))
        {
            return new View("NULL VALUES ARE NOT ALLOWED", Response::HTTP_NOT_ACCEPTABLE);
        }
        if (!$this->isEmail($email)) {
            return new View("THE EMAIL ADDRESS FORMAT IS INCORRECT", Response::HTTP_NOT_ACCEPTABLE);
        }

        if (!$this->isUrl($image)) {
            return new View("THE IMAGE URL FORMAT IS INCORRECT", Response::HTTP_NOT_ACCEPTABLE);
        }

        $em = $this->getDoctrine()->getManager();
        $duplicate_email = $em->getRepository('AppBundle:Offer')
            ->findOneBy(['email' => $email]);
        if ($duplicate_email) {
            return new View("THE EMAIL ACCOUNT ALREADY EXISTS", Response::HTTP_NOT_ACCEPTABLE);
        }

        //Store in Database
        $offer = new Offer();
        $offer->setTitle($title);
        $offer->setDescription($description);
        $offer->setEmail($email);
        $offer->setImage($image);
        $offer->setCreatedAt(new \DateTime("now"));

        $em = $this->getDoctrine()->getManager();
        $em->persist($offer);
        $em->flush();

        $id = $offer->getId();

        $last_inserted = $em->getRepository('AppBundle:Offer')
            ->find($id);

        return new View($last_inserted, Response::HTTP_OK);
    }

    /**
     * @Rest\Put("/offers/{id}")
     * @param $id int
     * @param $request Request
     * @return Response
     */

    public function updateOffer($id, Request $request)
    {
        $title = substr($request->get('title'),0,$this->len_title);
        $description = substr($request->get('description'),0,$this->len_description);
        $email = substr($request->get('email'),0,$this->len_email);
        $image = substr($request->get('image'),0,$this->len_image);

        $offer_db = $this->getDoctrine()->getRepository('AppBundle:Offer')->find($id);
        if (empty($offer_db)){
            return new View("OFFER NOT FOUND", Response::HTTP_NOT_FOUND);
        }

        if(empty($title)){
            return new View("DESC".$title, Response::HTTP_NOT_ACCEPTABLE);
        }


        if(empty($title) || empty($description) || empty($email) || empty($image))
        {
            return new View("NULL VALUES ARE NOT ALLOWED", Response::HTTP_NOT_ACCEPTABLE);
        }

        if (!$this->isEmail($email)) {
            return new View("THE EMAIL ADDRESS FORMAT IS INCORRECT", Response::HTTP_NOT_ACCEPTABLE);
        }

        if (!$this->isUrl($image)) {
            return new View("THE IMAGE URL FORMAT IS INCORRECT", Response::HTTP_NOT_ACCEPTABLE);
        }

        if ($email != $offer_db->getEmail()){
            $repository = $this->getDoctrine()
                ->getRepository('AppBundle:Offer');

            $query = $repository->createQueryBuilder('o')
                ->where('o.id <> :id')
                ->AndWhere('o.email = :email')
                ->setParameter('id', $id)
                ->setParameter('email', $email)
                ->getQuery();

            $duplicate_email = $query->getResult();
            if (!empty($duplicate_email)){
                return new View("THE EMAIL ACCOUNT ALREADY EXISTS", Response::HTTP_NOT_ACCEPTABLE);
            }
        }

        $offer_db->setTitle($title);
        $offer_db->setDescription($description);
        $offer_db->setEmail($email);
        $offer_db->setImage($image);
        $em = $this->getDoctrine()->getManager();
        $em->persist($offer_db);
        $em->flush();
        return new View("OFFER UPDATED SUCCESFULLY", Response::HTTP_OK);

    }

    /**
     * @Rest\Delete("/offers")
     * @param $request Request
     * @return Response
     */
    public function destroyOffers(Request $request)
    {
        $deleted = false;
        $rq = $request->get('offers');

        if (empty($rq)){
            return new View("NULL VALUES ARE NOT ALLOWED", Response::HTTP_NOT_ACCEPTABLE);
        }

        $obj = json_decode($rq, true);
        $em = $this->getDoctrine()->getManager();

        foreach ($obj as $of){
            if (is_numeric($of["id"])){
                $id = intval($of["id"]);
                $found = $this->getDoctrine()->getRepository('AppBundle:Offer')->find($id);

                if (!empty($found)){
                    $deleted = true;
                    $em->remove($found);
                    $em->flush();
                }
            }
        }

        if ($deleted){
            return new View("OFFERS DELETED", Response::HTTP_OK);
        } else {
            return new View("OFFER NOT FOUND", Response::HTTP_NOT_FOUND);
        }

    }

    private function isEmail($val){
        if (!filter_var($val, FILTER_VALIDATE_EMAIL)) {
            return false;
        } else {
            return true;
        }
    }

    private function isUrl($val){
        if (!filter_var($val, FILTER_VALIDATE_URL)) {
            return false;
        } else {
            return true;
        }
    }

}