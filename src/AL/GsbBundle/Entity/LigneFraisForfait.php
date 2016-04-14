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
     * @ORM\Column(name="quantite", type="integer")
     */
    private $quantite;

    /*
     * @ORM\ManyToOne(targetEntity="AL\GsbBundle\Entity\FicheFrais")
     * @ORM\JoinColumn(nullable=false)
     */
    
    private $ficheFrais;
    
    /*
     * @ORM\ManyToOne(targetEntity="AL\GsbBundle\Entity\FraisForfait")
     * @JoinColumn(nullable=false) 
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
     * @param integer $quantite
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
     * @return integer 
     */
    public function getQuantite()
    {
        return $this->quantite;
    }
}
