<?php

namespace AppBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @MongoDB\Document
 */
class Asteroid
{
    /**
     * @var string
     * @MongoDB\Id
     */
    protected $id;

    /**
     * @var \DateTime
     *
     * @MongoDB\Date()
     */
    protected $date;

    /**
     * @var int
     *
     * @MongoDB\Field(type="integer")
     */
    protected $reference;

    /**
     * @var string
     *
     * @MongoDB\Field(type="string")
     */
    protected $name;

    /**
     * @var float
     *
     * @MongoDB\Field(type="float")
     */
    protected $speed;

    /**
     * @var bool
     *
     * @MongoDB\Field(type="boolean")
     */
    protected $hazardous;

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return \DateTime
     */
    public function getDate(): \DateTime
    {
        return $this->date;
    }

    /**
     * @param \DateTime $date
     */
    public function setDate(\DateTime $date)
    {
        $this->date = $date;
    }

    /**
     * @return int
     */
    public function getReference(): int
    {
        return $this->reference;
    }

    /**
     * @param int $reference
     */
    public function setReference(int $reference)
    {
        $this->reference = $reference;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }

    /**
     * @return float
     */
    public function getSpeed(): float
    {
        return $this->speed;
    }

    /**
     * @param float $speed
     */
    public function setSpeed(float $speed)
    {
        $this->speed = $speed;
    }

    /**
     * @return bool
     */
    public function isHazardous(): bool
    {
        return $this->hazardous;
    }

    /**
     * @param bool $hazardous
     */
    public function setHazardous(bool $hazardous)
    {
        $this->hazardous = $hazardous;
    }

}
