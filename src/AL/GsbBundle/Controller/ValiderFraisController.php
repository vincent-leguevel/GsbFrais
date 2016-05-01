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
                return $this->render("ALGsbBundle:Comptable:validerFrais.html.twig", array('utilisateurs' => $utilisateurs, 'form' => $form->createView(), 'choix' => true, 'notFound' => true, 'user' => $user, 'date' => $date, 'etat' => $etat));
            }
        }
        return $this->render("ALGsbBundle:Comptable:validerFrais.html.twig", array('utilisateurs' => $utilisateurs, 'form' => $form->createView(), 'choix' => true));
    }

    public function detailsAction($id_ficheFrais) {
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
                $ficheFrais->setDateModif(new \DateTime(date('Y') . "-" . date('m') . "-" . date('d')));
                $ficheFrais->setMontantValide($formData['montantValide']);
                $em->persist($ficheFrais);
                $em->flush();
            }
            return $this->redirectToRoute('al_gsb_valider_frais_details', array('id_ficheFrais' => $ficheFrais->getId()));
        }
        return $this->render("ALGsbBundle:Comptable:validerFrais.html.twig", array('details' => true, 'form' => $form->createView(), 'ficheFrais' => $ficheFrais, 'lignesFraisHorsForfait' => $ficheFrais->getLignesFraisHorsForfait()));
    }

    public function supprimerHorsForfaitAction($id_horsForfait,$id_ficheFrais) {
        $em = $this->getDoctrine()->getManager();
        
        $ficheFraisHorsForfait = $em->getRepository('ALGsbBundle:LigneFraisHorsForfait')->find($id_horsForfait);
        $ficheFraisHorsForfait->setLibelle('REFUSE : ' . $ficheFraisHorsForfait->getLibelle());
        $em->persist($ficheFraisHorsForfait);
        
        
        $ficheFrais = $em->getRepository('ALGsbBundle:FicheFrais')->find($id_ficheFrais);
        $ficheFrais->setDateModif(new \DateTime(date('Y') . "-" . date('m') . "-" . date('d')));
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
                $form->add('km', 'integer', array('data' => $ligneFraisForfait->getQuantite()));
            } elseif ($fraisForfait->getId() == 3) {
                $form->add('nuitees', 'integer', array('data' => $ligneFraisForfait->getQuantite()));
            } elseif ($fraisForfait->getId() == 4) {
                $form->add('repasMidi', 'integer', array('data' => $ligneFraisForfait->getQuantite()));
            }
        }
        $form->add('nbJustificatifs', 'integer', array('data' => $ficheFrais->getNbJustificatifs()));
        $form->add('montantValide', 'integer', array('data' => $ficheFrais->getMontantValide()));
        $form->add('Valider', 'submit');
        return $form->getForm();
    }

}
