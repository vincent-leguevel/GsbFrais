<?php

namespace AL\GsbBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ConsulterFraisController extends Controller {

    public function indexAction() {

        if (!isset($_SESSION["visiteur"])) {
            return $this->redirectToRoute('al_gsb_connexion');
        }

        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('ALGsbBundle:Utilisateur')->find($_SESSION['visiteur']->getId());

        // variable permettant de limiter la recherche des fiches de frais notament les hors forfait reporté par les comptables
        $date = date('Y') . "-" . (date('m')) . "-31";
        $fichesFrais = $em->getRepository('ALGsbBundle:FicheFrais')->consulterByUserAndDate($user, $date);

        $mois = range(1, 31);
        $annee = range(date('Y') - 4, date('Y'));
        $form = $this->createFormBuilder()
                ->add('Mois', 'choice', array(
                    'choices' => $mois))
                ->add('Annee', 'choice', array(
                    'choices' => $annee))
                ->add('Valider', 'submit')
                ->getForm();
        $form->handleRequest($this->get('request'));
        if ($form->isValid()) {
            $formData = $form->getData();

            if ($mois[$formData['Mois']] < 10) {
                $moisDate = "0" . $mois[$formData['Mois']];
            }
            $date = $annee[$formData['Annee']] . "-" . $moisDate;
            $dateActuelle = date('Y') . "-" . date('m');

            $ficheFrais = null;
            if ($date <= $dateActuelle) {
                $ficheFrais = $em->getRepository('ALGsbBundle:FicheFrais')->byUserAndDate($date, $user);
            }
            
            if ($ficheFrais != null) {
                return $this->redirectToRoute('al_gsb_details_frais', array('id_ficheFrais' => $ficheFrais->getId()));
            } else {
                return $this->render("ALGsbBundle:Visiteur:consulterFrais.html.twig", array('fichesFrais' => $fichesFrais, 'date' => $date, 'form' => $form->createView(), 'notFound' => $date));
            }
        }
        return $this->render("ALGsbBundle:Visiteur:consulterFrais.html.twig", array('fichesFrais' => $fichesFrais, 'date' => $date, 'form' => $form->createView()));
    }
    /**
     * 
     * @param type $id_ficheFrais
     * @return type
     */
    public function detailsFicheFraisAction($id_ficheFrais) {
        
        if (!isset($_SESSION["visiteur"])) {
            return $this->redirectToRoute('al_gsb_connexion');
        }

        $ficheFrais = $this->getDoctrine()->getManager()->getRepository('ALGsbBundle:FicheFrais')->find($id_ficheFrais);

        
        return $this->render("ALGsbBundle:Visiteur:detailsFicheFrais.html.twig", array('ficheFrais' => $ficheFrais));
    }

}
