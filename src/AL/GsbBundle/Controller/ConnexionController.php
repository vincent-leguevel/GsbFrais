<?php

namespace AL\GsbBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ConnexionController extends Controller {

    public function indexAction() {
        if (isset($_SESSION["visiteur"])) {
            return $this->redirectToRoute('al_gsb_accueil');
        }

        $form = $this->buildForm();

        $form->handleRequest($this->get('request'));
        if ($form->isValid()) {
            $formData = $form->getData();
            $login = $formData['nomDeCompte'];
            $pass = $formData['motDePasse'];

            $em = $this->getDoctrine()->getManager();
            $users = $em->getRepository("ALGsbBundle:Utilisateur");
            $visiteur = $users->findOneBy(array('login' => $login, 'pass' => $pass));

            if ($visiteur != null) {
                if($visiteur->getFonction()=="C"){
                    $_SESSION["comptable"] = $visiteur;
                }else{
                    $_SESSION["visiteur"] = $visiteur;
                }

                return $this->redirectToRoute('al_gsb_accueil');
            } else {
                return $this->render("ALGsbBundle:Connexion:connexion.html.twig", array('hasError' => 'Les données que vous avez entrées sont incorrectes', 'form' => $form->createView()));
            }
        }
        return $this->render("ALGsbBundle:Connexion:connexion.html.twig", array('form' => $form->createView()));
    }
    
    public function buildForm(){
        
        return $this->createFormBuilder()
                ->add('nomDeCompte', 'text')
                ->add('motDePasse', 'password')
                ->add('valider', 'submit')
                ->add('reinitialiser', 'reset')
                ->getForm();
    }

}
 