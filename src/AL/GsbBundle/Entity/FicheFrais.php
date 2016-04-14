<?php

namespace AL\GsbBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * FicheFrais
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AL\GsbBundle\Entity\FicheFraisRepository")
 */
class FicheFrais
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="mois", type="integer")
     */
    private $mois;

    /**
     * @var integer
     *
     * @ORM\Column(name="nbJustificatifs", type="integer")
     */
    private $nbJustificatifs;

    /**
     * @var string
     *
     * @ORM\Column(name="montantValide", type="decimal")
     */
    private $montantValide;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateModif", type="date")
     */
    private $dateModif;


    /*
     * @ORM\ManyToOne(targuetEntity="AL\GsbBundle\Entity\Etat")
     */
    
    private $etat;
    
    /*
     * @ORM\ManyToOne(targetEntity="AL\GsbBundle\Entity\Utilisateur")
     * @ORM\JoinColumn(nullable=false)
     */
    
    private $utilisateur;
   
}
