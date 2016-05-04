<?php

namespace AL\GsbBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * LigneFraisForfait
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AL\GsbBundle\Entity\LigneFraisForfaitRepository")
 */
class LigneFraisForfait
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
     * @ORM\Column(name="quantite", type="decimal",precision=10, scale=2)
     */
    private $quantite;

    /**
     * @ORM\ManyToOne(targetEntity="AL\GsbBundle\Entity\FicheFrais", inversedBy="lignesFraisForfait", cascade={"persist","remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    
    private $ficheFrais;
    
    /**
     * @ORM\ManyToOne(targetEntity="AL\GsbBundle\Entity\FraisForfait")
     * @ORM\JoinColumn(nullable=false) 
     */
    private $fraisForfait;


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
     * Set quantite
     *
     * @param string $quantite
     * @return LigneFraisForfait
     */
    public function setQuantite($quantite)
    {
        $this->quantite = $quantite;

        return $this;
    }

    /**
     * Get quantite
     *
     * @return string 
     */
    public function getQuantite()
    {
        return $this->quantite;
    }

    /**
     * Set ficheFrais
     *
     * @param \AL\GsbBundle\Entity\FicheFrais $ficheFrais
     * @return LigneFraisForfait
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

    /**
     * Set fraisForfait
     *
     * @param \AL\GsbBundle\Entity\FraisForfait $fraisForfait
     * @return LigneFraisForfait
     */
    public function setFraisForfait(\AL\GsbBundle\Entity\FraisForfait $fraisForfait)
    {
        $this->fraisForfait = $fraisForfait;

        return $this;
    }

    /**
     * Get fraisForfait
     *
     * @return \AL\GsbBundle\Entity\FraisForfait 
     */
    public function getFraisForfait()
    {
        return $this->fraisForfait;
    }
}
