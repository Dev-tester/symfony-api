<?php

namespace App\Entity;

use App\Repository\DNSRecordRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DNSRecordRepository::class)
 */
class DNSRecord
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

	/**
	 * @ORM\Column(type="integer")
	 */
	private $aid;

	/**
     * @ORM\Column(type="string", length=32)
     */
    private $type;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=32)
     */
    private $content;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $proxiable;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $proxied;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $ttl;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $locked;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $meta;

	/**
	 * @ORM\Column(type="integer", nullable=true)
	 */
	private $price;

	/**
	 * @ORM\Column(type="integer", nullable=true)
	 */
	private $diler_price;

	/**
	 * @ORM\Column(type="datetime")
	 */
	private $updated_at;

	public function getId(): ?int
    {
        return $this->id;
    }

	public function getAid()
	{
		return $this->aid;
	}

	public function setAid($aid): void
	{
		$this->aid = $aid;
	}

	public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getProxiable(): ?bool
    {
        return $this->proxiable;
    }

    public function setProxiable(?bool $proxiable): self
    {
        $this->proxiable = $proxiable;

        return $this;
    }

    public function getProxied(): ?bool
    {
        return $this->proxied;
    }

    public function setProxied(?bool $proxied): self
    {
        $this->proxied = $proxied;

        return $this;
    }

    public function getTtl(): ?int
    {
        return $this->ttl;
    }

    public function setTtl(?int $ttl): self
    {
        $this->ttl = $ttl;

        return $this;
    }

    public function getLocked(): ?bool
    {
        return $this->locked;
    }

    public function setLocked(?bool $locked): self
    {
        $this->locked = $locked;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt($created_at): self
    {
        $this->created_at = new \DateTime();

        return $this;
    }

    public function getMeta(): ?string
    {
        return $this->meta;
    }

    public function setMeta(?string $meta): self
    {
        $this->meta = $meta;

        return $this;
    }

	public function getPrice()
	{
		return $this->price;
	}

	public function setPrice($price): self
	{
		$this->price = $price;

		return $this;
	}

	public function getDilerPrice()
	{
		return $this->diler_price;
	}

	public function setDilerPrice($diler_price): self
	{
		$this->diler_price = $diler_price;

		return $this;
	}

	public function getUpdatedAt(): ?\DateTimeInterface
	{
		return $this->updated_at;
	}

	public function setUpdatedAt($updated_at): self
	{
		$this->updated_at = new \DateTime();

		return $this;
	}
}
