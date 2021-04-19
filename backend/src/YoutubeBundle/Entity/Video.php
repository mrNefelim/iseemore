<?php

namespace App\YoutubeBundle\Entity;

use App\YoutubeBundle\Repository\VideoRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=VideoRepository::class)
 * @ORM\Table("videos")
 */
class Video
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
    private $url;

    /**
     * @ORM\Column(type="boolean")
     */
    private $status;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getStatus(): bool
    {
        return $this->status;
    }

    /**
     * @param boolean $status
     * @return Video
     */
    public function setStatus(bool $status): self
    {
        $this->status = $status;

        return $this;
    }
}
