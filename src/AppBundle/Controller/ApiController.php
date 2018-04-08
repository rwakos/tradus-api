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
    /**
     * @Rest\Get("/")
     */
    public function displayDocumentation()
    {
        /*
         *  GET       api_url_offer_get_all: /offers
            GET       api_url_offer_get_one: /offers/{id}
            GET       api_url_offer_search:  /search/offers/{search}
            POST      api_url_offer_create:  /offer
            PATCH     api_url_offer_update:  /offers/{id}
            DELETE    api_url_offer_delete:  /offers
         * */
    }

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
            return new View("there are no offers", Response::HTTP_NOT_FOUND);
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
            return new View("Offer not found", Response::HTTP_NOT_FOUND);
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
            return new View("there are no offers", Response::HTTP_NOT_FOUND);
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
        $title = $request->get('title');
        $description = $request->get('description');
        $email = $request->get('email');
        $image = $request->get('description');

        if(empty($title) || empty($description) || empty($email) || empty($image))
        {
            return new View("NULL VALUES ARE NOT ALLOWED", Response::HTTP_NOT_ACCEPTABLE);
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
        $offer->setCreatedAt(date('Y-m-d'));

        $em = $this->getDoctrine()->getManager();
        $em->persist($offer);
        $em->flush();

        return new View("Offer Added Successfully", Response::HTTP_OK);
    }

    /**
     * @Rest\Patch("/offers/{id}")
     * @param $id int
     * @param $request Request
     * @return Response
     */

    public function updateOffer(Request $request, $id)
    {

        $data = ["data"=> $id];
        $response = new Response();
        $response->setContent(json_encode($data));
        $response->headers->set('Content-Type', 'application/json');
        $response->headers->set('Access-Control-Allow-Origin', '*');
        $response->headers->set('Access-Control-Allow-Methods', 'PATCH, OPTIONS');

        return $response;
    }

    /**
     * @Rest\Delete("/offers")
     * @param $request Request
     * @return Response
     */
    public function destroyOffers(Request $request)
    {
        $response = new Response();
        $response->headers->set('Content-Type', 'application/json');
        $response->headers->set('Access-Control-Allow-Origin', '*');
        $response->headers->set('Access-Control-Allow-Methods', 'DELETE, OPTIONS');

        return $response;
    }

}