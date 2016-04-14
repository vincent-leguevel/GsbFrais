<?php

namespace AL\GsbBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class SaisirFraisController extends Controller {

    public function indexAction() {
        
        if (!isset($_SESSION["visiteur"])) {
            return $this->redirectToRoute('al_gsb_connexion');
        }
        
        $form = $this->createFormBuilder()
                ->add('mois', 'text')
                ->add('valider', 'submit')
                ->getForm();

        $form->handleRequest($this->get('request'));
        if ($form->isValid()) {
            $formData = $form->getData();
            $mois = $formData['mois'];

            if($mois >= 1 AND $mois <= 12){
                return $this->render('ALGsbBundle:Visiteur:saisirFrais.html.twig',array('formDate'=>$form->createView(),'formFrais'=>$this->getMoisByID($mois)));
            }
            else{
                return $this->render('ALGsbBundle:Visiteur:saisirFrais.html.twig', array('formDate'=>$form->createView(),'hasError'=>'Le mois doit être compris entre 1 à 12'));
            }
        }


        return $this->render('ALGsbBundle:Visiteur:saisirFrais.html.twig',array('formDate'=>$form->createView(),'formFrais'=>$this->getMoisByID(date('m'))));
    }

    public function buildFormFrais(){

        return $this->createFormBuilder()
                    ->add('repasMidi','text')
                    ->add('nuitees','text')
                    ->add('etape','text')
                    ->add('km','text')
                    ->getForm();
    }

    public function getMoisByID($number){
        switch ($number) {
            case 1:
                return "de Janvier";
                break;
            case 2:
                return "de Février";
                break;
            case 3:
                return "de Mars";
                break;
            case 4:
                return "d'Avril";
                break;
            case 5:
                return "de Mai";
                break;
            case 6:
                return "de Juin";
                break;
            case 7:
                return "de Juillet";
                break;
            case 8:
                return "d'Août";
                break;
            case 9:
                return "de Septembre";
                break;
            case 10:
                return "d'Octobre";
                break;
            case 11:
                return "de Novembre";
                break;
            case 12:
                return "de Decembre";
                break;
        }
    }

}
