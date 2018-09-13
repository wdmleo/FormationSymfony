<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends Controller
{
    /**
     * @Route("", name="home")
     */
    public function homeAction(Request $request) {
        $get = $request->query; // $_GET
        $post = $request->request; // $_POST
        return new Response($get->get('toto'));
    }

    /**
     * @Route("/twig", name="twig_home")
     */
    public function twigHomeAction(Request $request) {
        return $this->render('default/twig_home.html.twig', [
            'toto'=>'test'
        ]);
    }

    /**
     * @Route("/twig/{toto}", name="twig_home_arg")
     */
    public function twigHomeWithArgAction(Request $request, $toto) {
        $argTwig = [
            'toto'=>$toto
        ];
        return $this->render('default/twig_home.html.twig', $argTwig);
    }

    /**
     * @Route("/twig/2/{toto}", name="twig_home_arg_2", requirements={"toto":"\d+"})
     */
    public function twigHomeWithArg2Action(Request $request, $toto) {
        $argTwig = [
            'toto'=>$toto
        ];
        return $this->render('default/twig_home.html.twig', $argTwig);
    }
}
