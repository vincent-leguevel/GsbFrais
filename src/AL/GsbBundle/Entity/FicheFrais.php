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
     * @ORM\Column(name="montantValide", type="decimal",precision=10, scale=2)
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
     * @ORM\JoinColumn(nullable=false)
     */
    
    private $etat;
    
    /*
     * @ORM\ManyToOne(targetEntity="AL\GsbBundle\Entity\Utilisateur")
     * @ORM\JoinColumn(nullable=false)
     */
    
    private $utilisateur;
   

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set mois
     *
     * @param integer $mois
     * @return FicheFrais
     */
    public function setMois($mois)
    {
        $this->mois = $mois;

        return $this;
    }

    /**
     * Get mois
     *
     * @return integer 
     */
    public function getMois()
    {
        return $this->mois;
    }

    /**
     * Set nbJustificatifs
     *
     * @param integer $nbJustificatifs
     * @return FicheFrais
     */
    public function setNbJustificatifs($nbJustificatifs)
    {
        $this->nbJustificatifs = $nbJustificatifs;

        return $this;
    }

    /**
     * Get nbJustificatifs
     *
     * @return integer 
     */
    public function getNbJustificatifs()
    {
        return $this->nbJustificatifs;
    }

    /**
     * Set montantValide
     *
     * @param string $montantValide
     * @return FicheFrais
     */
    public function setMontantValide($montantValide)
    {
        $this->montantValide = $montantValide;

        return $this;
    }

    /**
     * Get montantValide
     *
     * @return string 
     */
    public function getMontantValide()
    {
        return $this->montantValide;
    }

    /**
     * Set dateModif
     *
     * @param \DateTime $dateModif
     * @return FicheFrais
     */
    public function setDateModif($dateModif)
    {
        $this->dateModif = $dateModif;

        return $this;
    }

    /**
     * Get dateModif
     *
     * @return \DateTime 
     */
    public function getDateModif()
    {
        return $this->dateModif;
    }
}
