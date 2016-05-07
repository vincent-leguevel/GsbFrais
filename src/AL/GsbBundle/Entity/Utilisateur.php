<?php

namespace AL\GsbBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Utilisateur
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AL\GsbBundle\Entity\UtilisateurRepository")
 */
class Utilisateur
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
     * @var string
     *
     * @ORM\Column(name="login", type="string", length=30)
     */
    private $login;

    /**
     * @var string
     *
     * @ORM\Column(name="pass", type="string", length=40)
     */
    private $pass;

    /**
     * @var string
     *
     * @ORM\Column(name="fonction", type="string", length=1)
     */
    private $fonction;

    /**
     * @var string
     *
     * @ORM\Column(name="prenom", type="string", length=25)
     */
    private $prenom;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=25)
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="adresse", type="string", length=100)
     */
    private $adresse;

    /**
     * @var string
     *
     * @ORM\Column(name="codePostal", type="string", length=8)
     */
    private $codePostal;

    /**
     * @var string
     *
     * @ORM\Column(name="ville", type="string", length=35)
     */
    private $ville;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateEmbauche", type="date")
     */
    private $dateEmbauche;

    /**
     * @ORM\OneToMany(targetEntity="AL\GsbBundle\Entity\FicheFrais", mappedBy="utilisateur",cascade={"persist","remove"})
     */
    
    private $fichesFrais;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->fichesFrais = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set login
     *
     * @param string $login
     * @return Utilisateur
     */
    public function setLogin($login)
    {
        $this->login = $login;

        return $this;
    }

    /**
     * Get login
     *
     * @return string 
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * Set pass
     *
     * @param string $pass
     * @return Utilisateur
     */
    public function setPass($pass)
    {
        $this->pass = $pass;

        return $this;
    }

    /**
     * Get pass
     *
     * @return string 
     */
    public function getPass()
    {
        return $this->pass;
    }

    /**
     * Set fonction
     *
     * @param string $fonction
     * @return Utilisateur
     */
    public function setFonction($fonction)
    {
        $this->fonction = $fonction;

        return $this;
    }

    /**
     * Get fonction
     *
     * @return string 
     */
    public function getFonction()
    {
        return $this->fonction;
    }

    /**
     * Set prenom
     *
     * @param string $prenom
     * @return Utilisateur
     */
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;

        return $this;
    }

    /**
     * Get prenom
     *
     * @return string 
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * Set nom
     *
     * @param string $nom
     * @return Utilisateur
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string 
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set adresse
     *
     * @param string $adresse
     * @return Utilisateur
     */
    public function setAdresse($adresse)
    {
        $this->adresse = $adresse;

        return $this;
    }

    /**
     * Get adresse
     *
     * @return string 
     */
    public function getAdresse()
    {
        return $this->adresse;
    }

    /**
     * Set codePostal
     *
     * @param string $codePostal
     * @return Utilisateur
     */
    public function setCodePostal($codePostal)
    {
        $this->codePostal = $codePostal;

        return $this;
    }

    /**
     * Get codePostal
     *
     * @return string 
     */
    public function getCodePostal()
    {
        return $this->codePostal;
    }

    /**
     * Set ville
     *
     * @param string $ville
     * @return Utilisateur
     */
    public function setVille($ville)
    {
        $this->ville = $ville;

        return $this;
    }

    /**
     * Get ville
     *
     * @return string 
     */
    public function getVille()
    {
        return $this->ville;
    }

    /**
     * Set dateEmbauche
     *
     * @param \DateTime $dateEmbauche
     * @return Utilisateur
     */
    public function setDateEmbauche($dateEmbauche)
    {
        $this->dateEmbauche = $dateEmbauche;

        return $this;
    }

    /**
     * Get dateEmbauche
     *
     * @return \DateTime 
     */
    public function getDateEmbauche()
    {
        return $this->dateEmbauche;
    }

    /**
     * Add fichesFrais
     *
     * @param \AL\GsbBundle\Entity\FicheFrais $fichesFrais
     * @return Utilisateur
     */
    public function addFichesFrai(\AL\GsbBundle\Entity\FicheFrais $fichesFrais)
    {
        $this->fichesFrais[] = $fichesFrais;

        return $this;
    }

    /**
     * Remove fichesFrais
     *
     * @param \AL\GsbBundle\Entity\FicheFrais $fichesFrais
     */
    public function removeFichesFrai(\AL\GsbBundle\Entity\FicheFrais $fichesFrais)
    {
        $this->fichesFrais->removeElement($fichesFrais);
    }

    /**
     * Get fichesFrais
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getFichesFrais()
    {
        return $this->fichesFrais;
    }
    
    public function __toString() {
        return "lol";
    }
}
