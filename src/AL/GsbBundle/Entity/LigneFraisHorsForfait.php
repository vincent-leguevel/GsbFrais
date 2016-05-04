<?php

namespace AL\GsbBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * LigneFraisHorsForfait
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AL\GsbBundle\Entity\LigneFraisHorsForfaitRepository")
 */
class LigneFraisHorsForfait
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
     * @var decimal
     *
     * @ORM\Column(name="libelle", type="string", length=25)
     */
    private $libelle;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateFrais", type="date")
     */
    private $dateFrais;

    /**
     * @var string
     *
     * @ORM\Column(name="montant", type="decimal",precision=10, scale=2)
     */
    private $montant;


    /**
     * @ORM\ManyToOne(targetEntity="AL\GsbBundle\Entity\FicheFrais", inversedBy="lignesFraisHorsForfait")
     * @ORM\JoinColumn(nullable=false)
     * 
     */
    
    private $ficheFrais;
    

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
     * Set libelle
     *
     * @param string $libelle
     * @return LigneFraisHorsForfait
     */
    public function setLibelle($libelle)
    {
        $this->libelle = $libelle;

        return $this;
    }

    /**
     * Get libelle
     *
     * @return string 
     */
    public function getLibelle()
    {
        return $this->libelle;
    }

    /**
     * Set dateFrais
     *
     * @param \DateTime $dateFrais
     * @return LigneFraisHorsForfait
     */
    public function setDateFrais($dateFrais)
    {
        $this->dateFrais = $dateFrais;

        return $this;
    }

    /**
     * Get dateFrais
     *
     * @return \DateTime 
     */
    public function getDateFrais()
    {
        return $this->dateFrais;
    }

    /**
     * Set montant
     *
     * @param string $montant
     * @return LigneFraisHorsForfait
     */
    public function setMontant($montant)
    {
        $this->montant = $montant;

        return $this;
    }

    /**
     * Get montant
     *
     * @return string 
     */
    public function getMontant()
    {
        return $this->montant;
    }

    /**
     * Set ficheFrais
     *
     * @param \AL\GsbBundle\Entity\FicheFrais $ficheFrais
     * @return LigneFraisHorsForfait
     */
    public function setFicheFrais(\AL\GsbBundle\Entity\FicheFrais $ficheFrais)
    {
        $this->ficheFrais = $ficheFrais;

        return $this;
    }

    /**
     * Get ficheFrais
     *
     * @return \AL\GsbBundle\Entity\FicheFrais 
     */
    public function getFicheFrais()
    {
        return $this->ficheFrais;
    }
}
