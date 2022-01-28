<?php

namespace App\Entity;

use App\Repository\QuestionRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="question", indexes={@ORM\Index(name="idQuestion", columns={"idQuestion"})})
 * @ORM\Entity(repositoryClass=QuestionRepository::class)
 */
class Question
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $libelle;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $rep1;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $rep2;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $rep3;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $reponse_correcte;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $nb_rep1;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $nb_rep2;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $nb_rep3;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): self
    {
        $this->libelle = $libelle;

        return $this;
    }

    public function getRep1(): ?string
    {
        return $this->rep1;
    }

    public function setRep1(string $rep1): self
    {
        $this->rep1 = $rep1;

        return $this;
    }

    public function getRep2(): ?string
    {
        return $this->rep2;
    }

    public function setRep2(string $rep2): self
    {
        $this->rep2 = $rep2;

        return $this;
    }

    public function getRep3(): ?string
    {
        return $this->rep3;
    }

    public function setRep3(string $rep3): self
    {
        $this->rep3 = $rep3;

        return $this;
    }

    public function getReponseCorrecte(): ?string
    {
        return $this->reponse_correcte;
    }

    public function setReponseCorrecte(string $reponse_correcte): self
    {
        $this->reponse_correcte = $reponse_correcte;

        return $this;
    }

    public function getNbRep1(): ?int
    {
        return $this->nb_rep1;
    }








    public function setNbRep1(int $nb_rep1): self
    {
        $this->nb_rep1 = $nb_rep1;

        return $this;
    }







    public function getNbRep2(): ?int
    {
        return $this->nb_rep2;
    }

    public function setNbRep2(int $nb_rep2): self
    {
        $this->nb_rep2 = $nb_rep2;

        return $this;
    }

    public function getNbRep3(): ?int
    {
        return $this->nb_rep3;
    }




    public function setNbRep3(int $nb_rep3): self
    {
        $this->nb_rep3 = $nb_rep3;

        return $this;
    }









}
