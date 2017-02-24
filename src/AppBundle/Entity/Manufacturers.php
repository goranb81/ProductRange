<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Manufacturers
 *
 * @ORM\Table(name="manufacturers", uniqueConstraints={@ORM\UniqueConstraint(name="manufacturerName", columns={"manufacturerName"})})
 * @ORM\Entity
 */
class Manufacturers
{
    /**
     * @var string
     *
     * @ORM\Column(name="manufacturerName", type="string", length=50, nullable=false)
     */
    private $manufacturername;

    /**
     * @var integer
     *
     * @ORM\Column(name="manufacturerId", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $manufacturerid;



    /**
     * Set manufacturername
     *
     * @param string $manufacturername
     *
     * @return Manufacturers
     */
    public function setManufacturername($manufacturername)
    {
        $this->manufacturername = $manufacturername;

        return $this;
    }

    /**
     * Get manufacturername
     *
     * @return string
     */
    public function getManufacturername()
    {
        return $this->manufacturername;
    }

    /**
     * Get manufacturerid
     *
     * @return integer
     */
    public function getManufacturerid()
    {
        return $this->manufacturerid;
    }
}
