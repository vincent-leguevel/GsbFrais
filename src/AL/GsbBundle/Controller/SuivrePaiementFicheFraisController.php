<?php

namespace AL\GsbBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class SuivrePaiementFicheFraisController extends Controller {

    /**
     * 
     * @return type
     */
    public function indexAction() {

        if (!isset($_SESSION["comptable"])) {
            return $this->redirectToRoute('al_gsb_connexion');
        }

        $em = $this->getDoctrine()->getManager();

        //$utilisateurs = $em->getRepository('ALGsbBundle:Utilisateur')->findBy(array('fonction' => 'V'));
        
        //Fiches de frais listées en fonction des états ci-dessous 
        $validee = $em->getRepository('ALGsbBundle:Etat')->find(4);
        $miseEnPaiement = $em->getRepository('ALGsbBundle:Etat')->find(5);
        $remboursee = $em->getRepository('ALGsbBundle:Etat')->find(1);

        $form = $this->createFormBuilder()
                ->add('utilisateur', 'entity', array(
			'class' => 'AL\\GsbBundle\Entity\Utilisateur',
			'property' => 'nom'))
//                ->add('utilisateur', 'choice', array(
//                    'choices' => $utilisateurs))
                ->add('Valider', 'submit')
                ->getForm();

        $form->handleRequest($this->get('request'));
        if ($form->isValid()) {
            $formData = $form->getData();
            //$user = $utilisateurs[$formData['utilisateur']];
 
            $fichesFrais = $em->getRepository('ALGsbBundle:FicheFrais')->byEtatValideeAndUser($validee,$miseEnPaiement,$remboursee,$formData['utilisateur']);
            
            if($fichesFrais != null){
                
                return $this->render("ALGsbBundle:Comptable:suivrePaiementFrais.html.twig",array('form'=>$form->createView(),'fichesFrais'=>$fichesFrais));
            }else{
                $fichesFrais = $em->getRepository('ALGsbBundle:FicheFrais')->byEtatValidee($validee,$miseEnPaiement,$remboursee);
                return $this->render("ALGsbBundle:Comptable:suivrePaiementFrais.html.twig",array('form'=>$form->createView(),'fichesFrais'=>$fichesFrais,'notFound'=>true));
            }
        }
        
        
        $fichesFrais = $em->getRepository('ALGsbBundle:FicheFrais')->byEtatValidee($validee,$miseEnPaiement,$remboursee);
        
        return $this->render("ALGsbBundle:Comptable:suivrePaiementFrais.html.twig",array('form'=>$form->createView(),'fichesFrais'=>$fichesFrais));
    }
    
    public function modifierEtatFicheAction($id_ficheFrais){
        if (!isset($_SESSION["comptable"])) {
            return $this->redirectToRoute('al_gsb_connexion');
        }
        
        $em = $this->getDoctrine()->getManager();
        
        $ficheFrais = $em->getRepository('ALGsbBundle:FicheFrais')->find($id_ficheFrais);
        
        if($ficheFrais->getEtat()->getId() == 4){
            $etat = $em->getRepository('ALGsbBundle:Etat')->find(5);
            
            $ficheFrais->setEtat($etat);
            $ficheFrais->setDateModif(new \DateTime(date('Y') . "-" . date('m') . "-" . date('d')));
            $em->persist($ficheFrais);
            $em->flush();
            
        }else if($ficheFrais->getEtat()->getId() == 5){
            $etat = $em->getRepository('ALGsbBundle:Etat')->find(1);
            $ficheFrais->setEtat($etat);
            $ficheFrais->setDateModif(new \DateTime(date('Y') . "-" . date('m') . "-" . date('d')));
            $em->persist($ficheFrais);
            $em->flush();
            
        }
        
        return $this->redirectToRoute('al_gsb_suivre_paiement_frais');
    }
    
    /**
     * 
     * @param type $id_ficheFrais
     * @return type
     */
    public function detailsFicheFraisAction($id_ficheFrais) {
        
        if (!isset($_SESSION["comptable"])) {
            return $this->redirectToRoute('al_gsb_connexion');
        }

        $ficheFrais = $this->getDoctrine()->getManager()->getRepository('ALGsbBundle:FicheFrais')->find($id_ficheFrais);

        
        return $this->render("ALGsbBundle:Comptable:detailsFicheFrais.html.twig", array('ficheFrais' => $ficheFrais));
    }

}
