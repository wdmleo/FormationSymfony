<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DefaultController extends Controller
{
    /**
     * @Route("", name="home")
     */
    public function homeAction(Request $request) {
        return $this->render('default/home.html.twig');
    }

    /**
     * @Route("connexion", name="connexion")
     */
    public function connexionAction(Request $request) {
        $em = $this->getDoctrine()->getManager();

        $login = $request->request->get('login');
        $utilisateur = $em->getRepository('AppBundle:Utilisateur')->findOneByNom($login);

        if ($utilisateur == null) {
            $utilisateur = new \AppBundle\Entity\Utilisateur();
            $utilisateur->setNom($login);

            $em->persist($utilisateur);

            $em->flush();
        }

        return $this->redirectToRoute('list', [
            'id'=>$utilisateur->getId()
        ]);
    }

    /**
     * @Route("/list/{id}", name="list")
     */
    public function listAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();

        $verif = $em->getRepository('AppBundle:Utilisateur')->find($id);
        if ($verif == null) {
            throw new NotFoundHttpException("Fallais pas me chercher", null, 404);
            // return new Response(',404');
        }

        $recus = $em->getRepository('AppBundle:Message')->findByDestinataire($id);

        return $this->render('default/list.html.twig', [
            'recus'=>$recus,
            'id'=>$id
        ]);
    }

    /**
     * @Route("/nouveau", name="nouveau_message")
     */
    public function nouveauMessageAction(Request $request) {
        $em = $this->getDoctrine()->getManager();

        $destinataire = $request->request->get('destinataire');
        $titre = $request->request->get('titre');
        $corps = $request->request->get('corps');
        $auteur = $request->request->get('auteur');

        $destinataireUtilisateur = $em->getRepository("AppBundle:Utilisateur")->findOneByNom($destinataire);
        $auteurUtilisateur = $em->getRepository("AppBundle:Utilisateur")->find($auteur);

        if ($destinataireUtilisateur != null && $auteurUtilisateur != null) {
            $message = new \AppBundle\Entity\Message();
            $message->setTitre($titre)
                    ->setCorps($corps)
                    ->setDestinataire($destinataireUtilisateur)
                    ->setAuteur($auteurUtilisateur)
                    ->setDate(new \DateTime());

            $em->persist($message);

            $em->flush();
        }
        return $this->redirectToRoute('list', ['id'=>$auteur]);
    }
}
