<?php

namespace AL\GsbBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller
;

class DeconnexionController extends Controller
{
    public function indexAction()
    {
        
        unset($_SESSION["visiteur"]) ;
        session_destroy() ;
        
        return $this->redirectToRoute('al_gsb_connexion');
    }
}
