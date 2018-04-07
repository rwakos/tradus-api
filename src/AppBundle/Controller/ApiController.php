<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class ApiController extends Controller {
    /**
     * @Route("/")
     * @Method("GET")
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
     * @Route("/offers")
     * @Method("GET")
     * @return Response
     */
    public function getOffers()
    {


        $offers = [
            ["id"=>1, "title"=>"Outlander mini", "description"=>"In perfect condition", "email"=>"demo@tradus.com","image"=>"https://apollo-ireland.akamaized.net/v1/files/irvcqv1wko8d1-HVYM/image", "created_at" => "2016-10-20"],
            ["id"=>2, "title"=>"Motor XLMN98", "description"=>"Real Bargain", "email"=>"dummy@tradus.com","image"=>"https://apollo-ireland.akamaized.net/v1/files/cqzybfy58cge3-HVYM/image", "created_at" => "2017-04-21"],
            ["id"=>3, "title"=>"Fendt 936", "description"=>"Lorem ipsum dolor sit amet, sonet nusquam interpretaris et duo, ius soleat consequat vulputate ut, ei eos diam viris partem. Nihil platonem per cu. Eam cu wisi regione vocibus, ad vel sonet causae.", "email"=>"info@tradus.com","image"=>"https://apollo-ireland.akamaized.net/v1/files/4ryz9mg3ze6e2-HVYM/image", "created_at" => "2017-07-01"]
        ];

        $data = ["data"=> $offers];
        $response = new Response();
        $response->setContent(json_encode($data));
        $response->headers->set('Content-Type', 'application/json');
        $response->headers->set('Access-Control-Allow-Origin', '*');
        $response->headers->set('Access-Control-Allow-Methods', 'GET, OPTIONS');
        return $response;
    }

    /**
     * @Route("/offers/{id}")
     * @Method("GET")
     * @param $id int
     * @return JsonResponse
     */
    public function getOffer($id)
    {
        $offers = [
            ["id"=>1, "title"=>"Outlander mini", "description"=>"In perfect condition", "email"=>"demo@tradus.com","image"=>"https://apollo-ireland.akamaized.net/v1/files/irvcqv1wko8d1-HVYM/image", "created_at" => "2016-10-20"],
            ["id"=>2, "title"=>"Motor XLMN98", "description"=>"Real Bargain", "email"=>"dummy@tradus.com","image"=>"https://apollo-ireland.akamaized.net/v1/files/cqzybfy58cge3-HVYM/image", "created_at" => "2017-04-21"],
            ["id"=>3, "title"=>"Fendt 936", "description"=>"Lorem ipsum dolor sit amet, sonet nusquam interpretaris et duo, ius soleat consequat vulputate ut, ei eos diam viris partem. Nihil platonem per cu. Eam cu wisi regione vocibus, ad vel sonet causae.", "email"=>"info@tradus.com","image"=>"https://apollo-ireland.akamaized.net/v1/files/4ryz9mg3ze6e2-HVYM/image", "created_at" => "2017-07-01"]
        ];

        $data = ["data"=> $offers[$id-1]];
        $response = new Response();
        $response->setContent(json_encode($data));
        $response->headers->set('Content-Type', 'application/json');
        $response->headers->set('Access-Control-Allow-Origin', '*');
        $response->headers->set('Access-Control-Allow-Methods', 'GET, OPTIONS');

        return $response;
    }

    /**
     * @Route("/search/offers/{search}")
     * @param $search string
     * @Method("GET")
     * @return JsonResponse
     */
    public function searchOffers($search)
    {
        $offers = [
            ["id"=>1, "title"=>"Outlander mini", "description"=>"In perfect condition", "email"=>"demo@tradus.com","image"=>"https://apollo-ireland.akamaized.net/v1/files/irvcqv1wko8d1-HVYM/image", "created_at" => "2016-10-20"],
            ["id"=>2, "title"=>"Motor XLMN98", "description"=>"Real Bargain", "email"=>"dummy@tradus.com","image"=>"https://apollo-ireland.akamaized.net/v1/files/cqzybfy58cge3-HVYM/image", "created_at" => "2017-04-21"],
            ["id"=>3, "title"=>"Fendt 936", "description"=>"Lorem ipsum dolor sit amet, sonet nusquam interpretaris et duo, ius soleat consequat vulputate ut, ei eos diam viris partem. Nihil platonem per cu. Eam cu wisi regione vocibus, ad vel sonet causae.", "email"=>"info@tradus.com","image"=>"https://apollo-ireland.akamaized.net/v1/files/4ryz9mg3ze6e2-HVYM/image", "created_at" => "2017-07-01"]
        ];

        $data = ["data"=> $offers[mt_rand(0,2)]];
        $response = new Response();
        $response->setContent(json_encode($data));
        $response->headers->set('Content-Type', 'application/json');
        $response->headers->set('Access-Control-Allow-Origin', '*');
        $response->headers->set('Access-Control-Allow-Methods', 'GET, OPTIONS');

        return $response;
    }

    /**
     * @Route("/offer")
     * @Method("POST")
     * @param $request Request
     * @return Response
     */
    public function storeOffer(Request $request)
    {

        $id = 4;
        $data = ["data"=> $id];
        $response = new Response();
        $response->setContent(json_encode($data));
        $response->headers->set('Content-Type', 'application/json');
        $response->headers->set('Access-Control-Allow-Origin', '*');
        $response->headers->set('Access-Control-Allow-Methods', 'POST, OPTIONS');

        return $response;
    }

    /**
     * @Route("/offers/{id}")
     * @Method("PATCH")
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
     * @Route("/offers")
     * @Method("DELETE")
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