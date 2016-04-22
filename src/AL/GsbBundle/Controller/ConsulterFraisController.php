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

        // variable permettant de limiter la recherche des fiches de frais notament les hors forfait eporté par les comptables
        $date = date('Y') . "-" . (date('m') + 1) . "-01";
        $fichesFrais = $em->getRepository('ALGsbBundle:FicheFrais')->consulterByUserAndDate($user, $date);

        $form = $this->createFormBuilder()
                ->add('Mois', 'choice', array(
                    'choices' => array('Mois' => array(range(1, 31), range(1, 31)))))
                ->add('Annee', 'choice', array(
                    'choices' => array('annee' => range(date('Y') - 4, date('Y')))))
                ->add('Valider', 'submit')
                ->getForm();
        $form->handleRequest($this->get('request'));
        if ($form->isValid()) {
            $formData = $form->getData();
            $date = $formData['Annee'] . "-" . $formData['Mois'];

            $ficheFrais = $em->getRepository('ALGsbBundle:FicheFrais')->byUserAndDate($date, $user);
            if ($ficheFrais != null) {
                return $this->redirectToRoute('al_gsb_details_frais', array('id_ficheFrais' => $ficheFrais->getId()));
            } else {
                return $this->render("ALGsbBundle:Visiteur:consulterFrais.html.twig", array('fichesFrais' => $fichesFrais, 'date' => $date, 'form' => $form->createView(), 'notFound' => $date));
            }
        }
        return $this->render("ALGsbBundle:Visiteur:consulterFrais.html.twig", array('fichesFrais' => $fichesFrais, 'date' => $date, 'form' => $form->createView()));
    }

    public function detailsFicheFraisAction($id_ficheFrais) {

        $ficheFrais = $this->getDoctrine()->getManager()->getRepository('ALGsbBundle:FicheFrais')->find($id_ficheFrais);

        return $this->render("ALGsbBundle:Visiteur:detailsFicheFrais.html.twig", array('ficheFrais' => $ficheFrais));
    }

}
