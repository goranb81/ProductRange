<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Internalexternal
 *
 * @ORM\Table(name="internalexternal", indexes={@ORM\Index(name="internalProductId", columns={"internalProductId"}), @ORM\Index(name="externalProductId", columns={"externalProductId"})})
 * @ORM\Entity
 */
class Internalexternal
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \AppBundle\Entity\Pricelists
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Pricelists")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="externalProductId", referencedColumnName="externalProductId")
     * })
     */
    private $externalproductid;

    /**
     * @var \AppBundle\Entity\Products
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Products")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="internalProductId", referencedColumnName="internalProductId")
     * })
     */
    private $internalproductid;



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
     * Set externalproductid
     *
     * @param \AppBundle\Entity\Pricelists $externalproductid
     *
     * @return Internalexternal
     */
    public function setExternalproductid(\AppBundle\Entity\Pricelists $externalproductid = null)
    {
        $this->externalproductid = $externalproductid;

        return $this;
    }

    /**
     * Get externalproductid
     *
     * @return \AppBundle\Entity\Pricelists
     */
    public function getExternalproductid()
    {
        return $this->externalproductid;
    }

    /**
     * Set internalproductid
     *
     * @param \AppBundle\Entity\Products $internalproductid
     *
     * @return Internalexternal
     */
    public function setInternalproductid(\AppBundle\Entity\Products $internalproductid = null)
    {
        $this->internalproductid = $internalproductid;

        return $this;
    }

    /**
     * Get internalproductid
     *
     * @return \AppBundle\Entity\Products
     */
    public function getInternalproductid()
    {
        return $this->internalproductid;
    }
}
