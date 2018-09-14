<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use FOS\RestBundle\Controller\Annotations as Rest;

/**
 * @Rest\Route("/client")
 */
class ClientController extends Controller
{
    /**
     * @Rest\Get("")
     */
     public function getClientAllAction(Request $request) {
         $em = $this->getDoctrine()->getManager();

         $clients = $em->getRepository("AppBundle:Client")->findAll();

         $data = [];
         foreach ($clients as $item) {
             $data[] = $item->toArray();
         }

         return new JsonResponse($data);
     }

    /**
     * @Rest\Post("")
     */
    public function postClientAction(Request $request) {
        $em = $this->getDoctrine()->getManager();

        $data = (object)json_decode($request->getContent(), true);

        $client = new \AppBundle\Entity\Client();
        $client->setFirstname($data->firstname);
        $client->setLastname($data->lastname);
        $client->setAddressNumber($data->address_number);
        $client->setAddress($data->address);
        $client->setPostalCode($data->postal_code);
        $client->setEmail($data->email);
        $client->setCity($data->city);
        $client->setPhone($data->phone);
        $client->setBirthday(\DateTime::createFromFormat('d/m/Y', $data->birthday));

        $em->persist($client);
        $em->flush();

        return new JsonResponse($client->toArray(), 201);
    }

    /**
     * @Rest\Put("")
     */
    public function modifyClientAction(Request $request) {

    }

    /**
     * @Rest\Delete("/{id}")
     */
    public function deleteClientAction(Request $request, $id) {
        
    }
}
