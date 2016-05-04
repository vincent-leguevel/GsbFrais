<?php

namespace AL\GsbBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ValiderFraisController extends Controller {

    public function indexAction() {

        if (!isset($_SESSION["comptable"])) {
            return $this->redirectToRoute('al_gsb_connexion');
        }

        $em = $this->getDoctrine()->getManager();

        $utilisateurs = $em->getRepository('ALGsbBundle:Utilisateur')->findBy(array('fonction' => 'V'));

        $mois = range(1, 31);
        $annee = range(date('Y') - 4, date('Y'));
        $form = $this->createFormBuilder()
                ->add('utilisateur', 'choice', array(
                    'choices' => $utilisateurs))
                ->add('mois', 'choice', array(
                    'choices' => $mois))
                ->add('annee', 'choice', array(
                    'choices' => $annee))
                ->add('Valider', 'submit')
                ->getForm();

        $form->handleRequest($this->get('request'));
        if ($form->isValid()) {
            $formData = $form->getData();
            $user = $utilisateurs[$formData['utilisateur']];

            if ($mois[$formData['mois']] < 10) {
                $date = $annee[$formData['annee']] . "-" . "0" . $mois[$formData['mois']];
            } else {
                $date = $annee[$formData['annee']] . "-" . $mois[$formData['mois']];
            }
            $etat = $em->getRepository('ALGsbBundle:Etat')->find(2);
            $ficheFrais = $em->getRepository('ALGsbBundle:FicheFrais')->byUserDateEtat($user, $date, $etat);
            if ($ficheFrais != null) {

                return $this->redirectToRoute('al_gsb_valider_frais_details', array('id_ficheFrais' => $ficheFrais->getId()));
            } else {
                return $this->render("ALGsbBundle:Comptable:validerFrais.html.twig", array('form' => $form->createView(), 'fichesFrais' => $this->getAllValidateFiches(), 'notFound' => true));
            }
        }
        $etat = $em->getRepository('ALGsbBundle:Etat')->find(2);
        $fichesFrais = $em->getRepository('ALGsbBundle:FicheFrais')->byEtat($etat);
        return $this->render("ALGsbBundle:Comptable:validerFrais.html.twig", array('form' => $form->createView(), 'fichesFrais' => $this->getAllValidateFiches()));
    }

    public function detailsAction($id_ficheFrais) {

        if (!isset($_SESSION["comptable"])) {
            return $this->redirectToRoute('al_gsb_connexion');
        }

        $em = $this->getDoctrine()->getManager();
        $ficheFrais = $em->getRepository('ALGsbBundle:FicheFrais')->find($id_ficheFrais);

        $form = $this->buildForm($ficheFrais);
        $form->handleRequest($this->get('request'));
        if ($form->isValid()) {
            $formData = $form->getData();
            foreach ($ficheFrais->getLignesFraisForfait() as $ligneFraisForfait) {

                $fraisForfait = $ligneFraisForfait->getFraisForfait();

                if ($fraisForfait->getId() == 1) {
                    $ligneFraisForfait->setQuantite($formData['etape']);
                } elseif ($fraisForfait->getId() == 2) {
                    $ligneFraisForfait->setQuantite($formData['km']);
                } elseif ($fraisForfait->getId() == 3) {
                    $ligneFraisForfait->setQuantite($formData['nuitees']);
                } elseif ($fraisForfait->getId() == 4) {
                    $ligneFraisForfait->setQuantite($formData['repasMidi']);
                }
                $em->persist($ligneFraisForfait);
                $ficheFrais->setNbJustificatifs($formData['nbJustificatifs']);
                $ficheFrais->setDateModif($this->buildDate());
                $ficheFrais->setMontantValide($formData['montantValide']);
                $em->persist($ficheFrais);
                $em->flush();
            }
            return $this->redirectToRoute('al_gsb_valider_frais_details', array('id_ficheFrais' => $ficheFrais->getId()));
        }
        return $this->render("ALGsbBundle:Comptable:detailsValiderFrais.html.twig", array('form' => $form->createView(), 'ficheFrais' => $ficheFrais));
    }

    public function etatValiderAction($id_ficheFrais) {

        if (!isset($_SESSION["comptable"])) {
            return $this->redirectToRoute('al_gsb_connexion');
        }

        $em = $this->getDoctrine()->getManager();
        $etat = $em->getRepository('ALGsbBundle:Etat')->find(4);
        $ficheFrais = $em->getRepository('ALGsbBundle:FicheFrais')->find($id_ficheFrais);
        $ficheFrais->setEtat($etat);
        $ficheFrais->setDateModif($this->buildDate());
        $em->persist($ficheFrais);
        $em->flush();

        return $this->redirectToRoute('al_gsb_valider_frais');
    }

    public function reporterHorsForfaitAction($id_horsForfait, $id_ficheFrais) {

        if (!isset($_SESSION["comptable"])) {
            return $this->redirectToRoute('al_gsb_connexion');
        }
        
        $em = $this->getDoctrine()->getManager();
        $fraisHorsForfait = $em->getRepository('ALGsbBundle:LigneFraisHorsForfait')->find($id_horsForfait);
        $ficheFrais = $em->getRepository('ALGsbBundle:FicheFrais')->find($id_ficheFrais);

        //date de redaction de la fiche actuelle + 1 mois 
        $date = new \DateTime();
        $date = $ficheFrais->getDateRedac();
        $date->add(new \DateInterval('P1M'));
        
        $ficheFraisSuivant = $em->getRepository('ALGsbBundle:FicheFrais')->byUserAndDate(date_format($date, 'Y-m'),$ficheFrais->getUtilisateur());

        if ($ficheFraisSuivant != null) {
            $fraisHorsForfait->setFicheFrais($ficheFraisSuivant);
            $em->persist($fraisHorsForfait);
            
            $ficheFrais->setDateModif($this->buildDate());
            $em->persist($ficheFrais);
            
            $ficheFraisSuivant->setDateModif($this->buildDate());
            $em->persist($ficheFraisSuivant);
            
            $em->flush();
            return $this->redirectToRoute('al_gsb_valider_frais_details', array('id_ficheFrais' => $ficheFrais->getId()));
        } else {
            
            // à completer
            return $this->redirectToRoute('al_gsb_valider_frais');
        }
    }

    public function supprimerHorsForfaitAction($id_horsForfait, $id_ficheFrais) {

        if (!isset($_SESSION["comptable"])) {
            return $this->redirectToRoute('al_gsb_connexion');
        }

        $em = $this->getDoctrine()->getManager();

        $ficheFraisHorsForfait = $em->getRepository('ALGsbBundle:LigneFraisHorsForfait')->find($id_horsForfait);
        $ficheFraisHorsForfait->setLibelle('REFUSE : ' . $ficheFraisHorsForfait->getLibelle());
        $em->persist($ficheFraisHorsForfait);


        $ficheFrais = $em->getRepository('ALGsbBundle:FicheFrais')->find($id_ficheFrais);
        $ficheFrais->setDateModif($this->buildDate());
        $em->persist($ficheFrais);
        $em->flush();
        return $this->redirectToRoute('al_gsb_valider_frais_details', array('id_ficheFrais' => $ficheFrais->getId()));
    }

    private function buildForm($ficheFrais) {
        $em = $this->getDoctrine()->getManager();
        $lignesFraisForfait = $em->getRepository('ALGsbBundle:LigneFraisForfait')->findBy(array('ficheFrais' => $ficheFrais));
        $form = $this->createFormBuilder();
        foreach ($lignesFraisForfait as $ligneFraisForfait) {

            $fraisForfait = $ligneFraisForfait->getFraisForfait();
            if ($fraisForfait->getId() == 1) {
                $form->add('etape', 'integer', array('data' => $ligneFraisForfait->getQuantite()));
            } elseif ($fraisForfait->getId() == 2) {
                $form->add('km', 'number', array('data' => $ligneFraisForfait->getQuantite(), 'precision' => '2'));
            } elseif ($fraisForfait->getId() == 3) {
                $form->add('nuitees', 'integer', array('data' => $ligneFraisForfait->getQuantite()));
            } elseif ($fraisForfait->getId() == 4) {
                $form->add('repasMidi', 'integer', array('data' => $ligneFraisForfait->getQuantite()));
            }
        }
        $form->add('nbJustificatifs', 'integer', array('data' => $ficheFrais->getNbJustificatifs()));
        $form->add('montantValide', 'number', array('data' => $ficheFrais->getMontantValide(), 'precision' => '2'));
        $form->add('Valider', 'submit');
        return $form->getForm();
    }

    private function buildDate() {
        
            return new \DateTime(date('Y') . "-" . date('m') . "-" . date('d'));
    }

    private function getAllValidateFiches() {

        $em = $this->getDoctrine()->getManager();
        $etat = $em->getRepository('ALGsbBundle:Etat')->find(2);
        $fichesFrais = $em->getRepository('ALGsbBundle:FicheFrais')->byEtat($etat);
        return $fichesFrais;
    }

}
