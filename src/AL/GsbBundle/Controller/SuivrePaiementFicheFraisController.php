<?php

namespace AL\GsbBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class SuivrePaiementFicheFraisController extends Controller {

    public function indexAction() {

        if (!isset($_SESSION["comptable"])) {
            return $this->redirectToRoute('al_gsb_connexion');
        }
        
        return $this->render("ALGsbBundle:Comptable:suivrePaiementFrais.html.twig");
    }
}