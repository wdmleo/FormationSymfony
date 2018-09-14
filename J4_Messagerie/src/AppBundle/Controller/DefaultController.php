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

        $utilisateur = new \AppBundle\Entity\Utilisateur();

        $form = $this->createFormBuilder($utilisateur)
                     ->add('nom', TextType::class)
                     ->add('validation', SubmitType::class, ['label'=>'Se connecter'])
                     ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $utilisateur = $form->getData();

            $existingUser = $em->getRepository("AppBundle:Utilisateur")->findOneByNom($utilisateur->getNom());
            if ($existingUser) {
                $utilisateur = $existingUser;
            }
            else {
                $em->persist($utilisateur);
                $em->flush();
            }

            return $this->redirectToRoute('list', ['id'=>$utilisateur->getId()]);
        }

        return $this->render('default/home.html.twig', [
            'form'=>$form->createView();
        ]);
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

        $recus = $em->getRepository('AppBundle:Message')->findBy([
            'destinataire'=>$id,
            'parent'=>null
        ]);
        $envoye = $em->getRepository("AppBundle:Message")->findBy([
            'auteur'=>$id,
            'parent'=>null
        ]);

        $message = new \AppBundle\Entity\Message();

        $form = $this->createFormBuilder($message)
                     ->add('destinataire_nom', TextType::class)
                     ->add('titre', TextType::class)
                     ->add('corps', TextareaType::class)
                     ->add('validation', SubmitType::class, ['label'=>'Envoyer !'])
                     ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $message = $form->getData();
            $destinataire = $form->getData()['destinataire_nom'];

            //
            $destinataireUtilisateur = $em->getRepository("AppBundle:Utilisateur")->findOneByNom($destinataire);
            $message->setDestinataire($destinataireUtilisateur);

            $em->persist($message);
            $em->flush();
        }

        return $this->render('default/list.html.twig', [
            'recus'=>array_merge($recus, $envoye),
            'id'=>$id,
            'form'=>$form
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
        $parentId = $request->request->get('parent_id');

        $parent = null;
        $destinataireUtilisateur = null;

        if ($parentId != null) {
            $parent = $em->getRepository("AppBundle:Message")->find($parentId);
            if ($parent != null) {
                $titre = $parent->getTitre();
            }
            $destinataireUtilisateur = $em->getRepository("AppBundle:Utilisateur")->find($destinataire);
        }
        else {
            $destinataireUtilisateur = $em->getRepository("AppBundle:Utilisateur")->findOneByNom($destinataire);
        }

        $auteurUtilisateur = $em->getRepository("AppBundle:Utilisateur")->find($auteur);

        if ($destinataireUtilisateur != null && $auteurUtilisateur != null) {
            $message = new \AppBundle\Entity\Message();
            $message->setTitre($titre)
                    ->setCorps($corps)
                    ->setDestinataire($destinataireUtilisateur)
                    ->setAuteur($auteurUtilisateur)
                    ->setDate(new \DateTime())
                    ->setParent($parent);

            $em->persist($message);

            $em->flush();
        }
        return $this->redirectToRoute('list', ['id'=>$auteur]);
    }
}
