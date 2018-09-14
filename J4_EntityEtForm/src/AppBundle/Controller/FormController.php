<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

// use AppBundle\Entity\Message;

/**
 * @Route("/form")
 */
class FormController extends Controller
{
    /**
     * @Route("/ajout", name="form_ajout")
     */
    public function formAjoutAction(Request $request) {
        $em = $this->getDoctrine()->getManager();

        $message = new \AppBundle\Entity\Message();
        $message->setTitre('Test');

        $form = $this->createFormBuilder($message)
                    ->add('titre', TextType::class)
                    ->add('message', TextareaType::class)
                    ->add('destinataire', TextType::class)
                    ->add('validation', SubmitType::class, ['label'=>'Valider'])
                    ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $message = $form->getData();
            $message->setDate(new \DateTime());
            $em->persist($message);
            $em->flush();
        }

        return $this->render('default/ajout.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
