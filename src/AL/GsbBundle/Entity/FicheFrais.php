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
     * @var \DateTime
     *
     * @ORM\Column(name="dateRedac", type="date")
     */
    private $dateRedac;

    /**
     * @var integer
     *
     * @ORM\Column(name="nbJustificatifs", type="integer")
     */
    private $nbJustificatifs;

    /**
     * @var decimal
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


    /**
     * @ORM\ManyToOne(targetEntity="AL\GsbBundle\Entity\Etat")
     * @ORM\JoinColumn(nullable=false)
     */
    
    private $etat;
    
    /**
     * @ORM\ManyToOne(targetEntity="AL\GsbBundle\Entity\Utilisateur", inversedBy="fichesFrais", cascade={"persist","remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    
    private $utilisateur;
   
    /**
     * @ORM\OneToMany(targetEntity="AL\GsbBundle\Entity\LigneFraisForfait", mappedBy="ficheFrais",cascade={"persist","remove"})
     */
    private $lignesFraisForfait;
    
    /**
     * @ORM\OneToMany(targetEntity="AL\GsbBundle\Entity\LigneFraisHorsForfait", mappedBy="ficheFrais",cascade={"persist","remove"})
     * 
     */
    private $lignesFraisHorsForfait;
  
    
 
   
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->lignesFraisForfait = new \Doctrine\Common\Collections\ArrayCollection();
        $this->lignesFraisHorsForfait = new \Doctrine\Common\Collections\ArrayCollection();
    }

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
     * Set dateRedac
     *
     * @param \DateTime $dateRedac
     * @return FicheFrais
     */
    public function setDateRedac($dateRedac)
    {
        $this->dateRedac = $dateRedac;

        return $this;
    }

    /**
     * Get dateRedac
     *
     * @return \DateTime 
     */
    public function getDateRedac()
    {
        return $this->dateRedac;
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

    /**
     * Set etat
     *
     * @param \AL\GsbBundle\Entity\Etat $etat
     * @return FicheFrais
     */
    public function setEtat(\AL\GsbBundle\Entity\Etat $etat)
    {
        $this->etat = $etat;

        return $this;
    }

    /**
     * Get etat
     *
     * @return \AL\GsbBundle\Entity\Etat 
     */
    public function getEtat()
    {
        return $this->etat;
    }

    /**
     * Set utilisateur
     *
     * @param \AL\GsbBundle\Entity\Utilisateur $utilisateur
     * @return FicheFrais
     */
    public function setUtilisateur(\AL\GsbBundle\Entity\Utilisateur $utilisateur)
    {
        $this->utilisateur = $utilisateur;

        return $this;
    }

    /**
     * Get utilisateur
     *
     * @return \AL\GsbBundle\Entity\Utilisateur 
     */
    public function getUtilisateur()
    {
        return $this->utilisateur;
    }

    /**
     * Add lignesFraisForfait
     *
     * @param \AL\GsbBundle\Entity\LigneFraisForfait $lignesFraisForfait
     * @return FicheFrais
     */
    public function addLignesFraisForfait(\AL\GsbBundle\Entity\LigneFraisForfait $lignesFraisForfait)
    {
        $this->lignesFraisForfait[] = $lignesFraisForfait;

        return $this;
    }

    /**
     * Remove lignesFraisForfait
     *
     * @param \AL\GsbBundle\Entity\LigneFraisForfait $lignesFraisForfait
     */
    public function removeLignesFraisForfait(\AL\GsbBundle\Entity\LigneFraisForfait $lignesFraisForfait)
    {
        $this->lignesFraisForfait->removeElement($lignesFraisForfait);
    }

    /**
     * Get lignesFraisForfait
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getLignesFraisForfait()
    {
        return $this->lignesFraisForfait;
    }

    /**
     * Add lignesFraisHorsForfait
     *
     * @param \AL\GsbBundle\Entity\LigneFraisHorsForfait $lignesFraisHorsForfait
     * @return FicheFrais
     */
    public function addLignesFraisHorsForfait(\AL\GsbBundle\Entity\LigneFraisHorsForfait $lignesFraisHorsForfait)
    {
        $this->lignesFraisHorsForfait[] = $lignesFraisHorsForfait;

        return $this;
    }

    /**
     * Remove lignesFraisHorsForfait
     *
     * @param \AL\GsbBundle\Entity\LigneFraisHorsForfait $lignesFraisHorsForfait
     */
    public function removeLignesFraisHorsForfait(\AL\GsbBundle\Entity\LigneFraisHorsForfait $lignesFraisHorsForfait)
    {
        $this->lignesFraisHorsForfait->removeElement($lignesFraisHorsForfait);
    }

    /**
     * Get lignesFraisHorsForfait
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getLignesFraisHorsForfait()
    {
        return $this->lignesFraisHorsForfait;
    }
}
