<?php

namespace AL\GsbBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class SaisirFraisController extends Controller {

    public function indexAction() {

        if (!isset($_SESSION["visiteur"])) {
            return $this->redirectToRoute('al_gsb_connexion');
        }
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository("ALGsbBundle:Utilisateur")->find($_SESSION['visiteur']->getId());
        $ficheFrais = new \AL\GsbBundle\Entity\FicheFrais();
        $ficheFrais = $this->getDoctrine()->getManager()->getRepository("ALGsbBundle:FicheFrais")->byUserAndDate(date("Y") . "-" . date("m"), $user);

        if ($ficheFrais != null) {

            $form = $this->buildForm($ficheFrais);

            $form->handleRequest($this->get('request'));
            if ($form->isValid()) {
                $formData = $form->getData();

                $lignesFraisForfait = $em->getRepository("ALGsbBundle:LigneFraisForfait")->findBy(array('ficheFrais' => $ficheFrais));

                if ($lignesFraisForfait != null) {

                    $ligneFraisForfait = new \AL\GsbBundle\Entity\LigneFraisForfait();

                    foreach ($lignesFraisForfait as $ligneFraisForfait) {

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
                        $em->flush();
                    }
                } else {
                    for ($i = 1; $i <= 4; $i++) {
                        $ligneFraisForfait = new \AL\GsbBundle\Entity\LigneFraisForfait();
                        $ligneFraisForfait->setFicheFrais($ficheFrais);

                        $ligneFraisForfait->setFraisForfait($this->findFraisForfait($i));
                        if ($i == 1) {
                            $ligneFraisForfait->setQuantite($formData['etape']);
                        } elseif ($i == 2) {
                            $ligneFraisForfait->setQuantite($formData['km']);
                        } elseif ($i == 3) {
                            $ligneFraisForfait->setQuantite($formData['nuitees']);
                        } elseif ($i == 4) {
                            $ligneFraisForfait->setQuantite($formData['repasMidi']);
                        }
                        $em->persist($ligneFraisForfait);
                        $em->flush();
                        $ligneFraisForfait = null;
                    }
                }

                $ficheFrais->setNbJustificatifs($formData['nbJustificatifs']);
                $ficheFrais->setDateModif(new \DateTime(date('Y') . "-" . date('m') . "-" . date('d')));
                $em->persist($ficheFrais);
                $em->flush();
                return $this->redirectToRoute('al_gsb_saisir_frais');
            }

            $fichesFraisHorsForfait = $em->getRepository('ALGsbBundle:LigneFraisHorsForfait')->findBy(array('ficheFrais'=>$ficheFrais));
            return $this->render('ALGsbBundle:Visiteur:saisirFrais.html.twig', array('saisir' => true, 'form' => $form->createView(), 'ficheFrais' => $ficheFrais,'mois' => $this->getMoisByID(date('m')),'lignesFraisHorsForfait'=>$fichesFraisHorsForfait));
        } else {
            //Comme la fiche du mois courant n'existe pas (ficheFrais == null) on clôture la fiche du mois précédent si elle existe
            $ficheFrais = $this->getDoctrine()->getManager()->getRepository("ALGsbBundle:FicheFrais")->byUserAndDate(date("Y") . "-%" . (date("m")-1), $user);
            if($ficheFrais != null){
                $etat = $em->getRepository("ALGsbBundle:Etat")->find(2);
                $ficheFrais->setEtat($etat);
                $em->persist($ficheFrais);
                $em->flush();
            }
            return $this->render('ALGsbBundle:Visiteur:saisirFrais.html.twig', array('creerFiche' => true, 'mois' => $this->getMoisByID(date("m"))));
        }
    }
    
    public function saisirHorsFraisAction($id_ficheFrais){
        if (!isset($_SESSION["visiteur"])) {
            return $this->redirectToRoute('al_gsb_connexion');
        }
        
        $ligneFraisHorsForfait = new \AL\GsbBundle\Entity\LigneFraisHorsForfait();
        
        $form = $this->createForm(new \AL\GsbBundle\Form\LigneFraisHorsForfaitType(), $ligneFraisHorsForfait)->add('Valider','submit');
        
        $form->handleRequest($this->get('request'));
        if($form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $ficheFrais = $em->getRepository('ALGsbBundle:FicheFrais')->find($id_ficheFrais);
            $ficheFrais->setDateModif(new \DateTime(date('Y') . "-" . date('m') . "-" . date('d')));
            $ligneFraisHorsForfait->setFicheFrais($ficheFrais);
            $em->persist($ligneFraisHorsForfait);
            $em->flush();
            return $this->redirectToRoute('al_gsb_saisir_frais');
        }
        return $this->render('ALGsbBundle:Visiteur:saisirHorsForfait.html.twig',array('form'=>$form->createView()));
    }
    
    public function supprimerHorsFraisAction($id_ligneFraisHorsForfait){
        
        if (!isset($_SESSION["visiteur"])) {
            return $this->redirectToRoute('al_gsb_connexion');
        }
        
        $em = $this->getDoctrine()->getManager();
        $ligneFraisHorsForfait = $em->getRepository('ALGsbBundle:LigneFraisHorsForfait')->find($id_ligneFraisHorsForfait);
        
        $em->remove($ligneFraisHorsForfait);
        $em->flush();
        
        return $this->redirectToRoute('al_gsb_saisir_frais');
    }

    public function creerFraisAction() {

        if (!isset($_SESSION["visiteur"])) {
            return $this->redirectToRoute('al_gsb_connexion');
        }
        
        $em = $this->getDoctrine()->getManager();

        $user = $em->getRepository("ALGsbBundle:Utilisateur")->find($_SESSION['visiteur']->getId());
        $date = date('Y') . "-" . date('m') . "-" . date('d');

        $ficheFrais = new \AL\GsbBundle\Entity\FicheFrais();

        $ficheFrais->setDateRedac(new \DateTime($date));
        $ficheFrais->setDateModif(new \DateTime($date));
        $ficheFrais->setNbJustificatifs(0);
        $ficheFrais->setMontantValide(0);

        $etat = $em->getRepository('ALGsbBundle:Etat')->find(3);
        $ficheFrais->setEtat($etat);
        $ficheFrais->setUtilisateur($user);

        $em->persist($ficheFrais);
        $em->flush();

        return $this->redirectToRoute('al_gsb_saisir_frais');
    }

    private function buildForm($ficheFrais) {
        $em = $this->getDoctrine()->getManager();
        $ligneFraisForfait = new \AL\GsbBundle\Entity\LigneFraisForfait();
        $lignesFraisForfait = $em->getRepository('ALGsbBundle:LigneFraisForfait')->findBy(array('ficheFrais' => $ficheFrais->getId()));

        $form = $this->createFormBuilder();
        if ($lignesFraisForfait == null) {
            $form->add('etape', 'integer', array('data' => 0));
            $form->add('km', 'integer', array('data' => 0));
            $form->add('nuitees', 'integer', array('data' => 0));
            $form->add('repasMidi', 'integer', array('data' => 0));
            $form->add('nbJustificatifs', 'integer', array('data' => 0));
        } else {
            foreach ($lignesFraisForfait as $ligneFraisForfait) {

                $fraisForfait = $ligneFraisForfait->getFraisForfait();
                if ($fraisForfait->getId() == 1) {
                    $form->add('etape', 'integer', array('data' => $ligneFraisForfait->getQuantite()));
                } elseif ($fraisForfait->getId() == 2) {
                    $form->add('km', 'number', array('data' => $ligneFraisForfait->getQuantite()));
                } elseif ($fraisForfait->getId() == 3) {
                    $form->add('nuitees', 'integer', array('data' => $ligneFraisForfait->getQuantite()));
                } elseif ($fraisForfait->getId() == 4) {
                    $form->add('repasMidi', 'integer', array('data' => $ligneFraisForfait->getQuantite()));
                }
            }
            $form->add('nbJustificatifs', 'integer', array('data' => $ficheFrais->getNbJustificatifs()));
        }

        $form->add('Valider', 'submit');
        return $form->getForm();
    }

    private function findFraisForfait($id) {
        $em = $this->getDoctrine()->getManager();
        $fraisForfait = $em->getRepository('ALGsbBundle:FraisForfait')->find($id);

        return $fraisForfait;
    }
    
    private function getMoisByID($number) {
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
