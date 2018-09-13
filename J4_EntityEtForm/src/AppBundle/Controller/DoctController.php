<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

// use AppBundle\Entity\Message;

/**
 * @Route("/doct")
 */
class DoctController extends Controller
{
    /**
     * @Route("/all", name="doct_all")
     */
    public function allAction(Request $request) {
        $em = $this->getDoctrine()->getManager();

        $messages = $em->getRepository("AppBundle:Message")->findAll();
        return $this->render('default/twig_home.html.twig', [
            'messages'=>$messages
        ]);
    }

    /**
     * @Route("/init", name="doct_init")
     */
    public function initAction(Request $request) {
        $em = $this->getDoctrine()->getManager();

        // $message = new Message();
        $message = new \AppBundle\Entity\Message();
        $message->setTitre('Mon super message');
        $message->setMessage('fjekfj kwajf wajod jwad wao djwadw hdwajdhwa djwad iawdjwadi;wa dwad iw fiwaifwaifowa fwa fowa fuow fupow ufpwa');
        $message->setDate(new \DateTime());
        $message->setDestinataire("johnsmith");

        $em->persist($message);

        // $em->clear();

        $em->flush();
        return $this->redirectToRoute('doct_home');
    }

    /**
     * @Route("/supp/{id}", name="doct_supprimer", requirements={"id"="\d+"})
     */
    public function supprimerAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();

        $message = $em->getRepository("AppBundle:Message")->find($id);

        $em->remove($message);

        $em->flush();

        return $this->redirectToRoute('doct_home');
    }

    /**
     * @Route("/{destinataire}", name="doct_home")
     */
    public function homeAction(Request $request, $destinataire = '') {
        $em = $this->getDoctrine()->getManager();

        if ($destinataire == '') {
            return $this->redirectToRoute('doct_all');
        }

        $repository = $em->getRepository('AppBundle:Message');
        $msgId1 = $repository->find(1);

        $msgId1 = $repository->findOneByDestinataire($destinataire);
        // SELECT * FROM message WHERE destinataire = $destinataire

        return $this->render('default/twig_home.html.twig', [
            'message'=>$msgId1
        ]);
    }
}
