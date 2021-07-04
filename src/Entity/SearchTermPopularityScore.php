<?php

namespace App\Entity;

use App\Repository\SearchTermPopularityScoreRepository;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * @ORM\Entity(repositoryClass=SearchTermPopularityScoreRepository::class)
 * @Serializer\ExclusionPolicy("all")
 */
class SearchTermPopularityScore
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     * @Serializer\Until("1")
     * @Serializer\Expose
     */
    private $term;

    /**
     * @ORM\Column(type="float", scale=2, nullable=false)
     * @Serializer\Expose()
     * @Serializer\Until("1")
     */
    private $score;

    /**
     * @Serializer\VirtualProperty("data")
     * @Serializer\Since("2")
     */
    public function data()
    {
        return [
            'term' => $this->term,
            'score' => $this->score,
        ];
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTerm(): ?string
    {
        return $this->term;
    }

    public function setTerm(string $term): self
    {
        $this->term = $term;

        return $this;
    }

    public function getScore(): ?float
    {
        return $this->score;
    }

    public function setScore(float $score): self
    {
        $this->score = $score;

        return $this;
    }
}
