<?php

namespace AL\GsbBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AccueilController extends Controller
{
    public function indexAction()
    {
        if(isset($_SESSION["visiteur"])) {         
            return $this->render("ALGsbBundle:Accueil:accueil.html.twig", array('visiteur'=>$_SESSION['visiteur']));
        }else if(isset($_SESSION["comptable"])){
            return $this->render("ALGsbBundle:Accueil:accueil.html.twig", array('comptable'=>$_SESSION['comptable']));
        }else{
            return $this->redirectToRoute('al_gsb_connexion'); 
        }  
                       
          
    }
}
