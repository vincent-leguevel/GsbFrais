<?php

namespace AL\GsbBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AccueilController extends Controller
{
    public function indexAction()
    {
        if(! isset($_SESSION["visiteur"])) {
            return $this->redirectToRoute('al_gsb_connexion'); 
        }       
            return $this->render("ALGsbBundle:Accueil:accueil.html.twig", array('visiteur'=>$_SESSION['visiteur']));           
          
    }
}
