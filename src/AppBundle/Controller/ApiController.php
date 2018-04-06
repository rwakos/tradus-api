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

    }

    /**
     * @Route("/offers")
     * @Method("GET")
     */
    public function getOffers()
    {

        $offers = [
            ["id"=>1, "title"=>"Outlander mini", "description"=>"In perfect condition", "email"=>"demo@tradus.com","image"=>"https://apollo-ireland.akamaized.net/v1/files/irvcqv1wko8d1-HVYM/image", "created_at" => "2016-10-20"],
            ["id"=>2, "title"=>"Motor XLMN98", "description"=>"Real Bargain", "email"=>"dummy@tradus.com","image"=>"https://apollo-ireland.akamaized.net/v1/files/cqzybfy58cge3-HVYM/image", "created_at" => "2017-04-21"],
            ["id"=>3, "title"=>"Fendt 936", "description"=>"Lorem ipsum dolor sit amet, sonet nusquam interpretaris et duo, ius soleat consequat vulputate ut, ei eos diam viris partem. Nihil platonem per cu. Eam cu wisi regione vocibus, ad vel sonet causae.", "email"=>"info@tradus.com","image"=>"https://apollo-ireland.akamaized.net/v1/files/4ryz9mg3ze6e2-HVYM/image", "created_at" => "2017-07-01"]
        ];

        $data = ["data"=> $offers];
        return new JsonResponse($data);
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
        return new JsonResponse($data);
    }

    /**
     * @Route("/offers/{id}")
     * @Method("POST")
     */
    public function storeOffer()
    {

    }

    /**
     * @Route("/offers/{id}")
     * @Method("PATCH")
     * @param $id int
     */
    public function updateOffer($id)
    {

    }

    /**
     * @Route("/offers/{id}")
     * @Method("DELETE")
     * @param $id int
     */
    public function destroyOffer($id)
    {

    }

}