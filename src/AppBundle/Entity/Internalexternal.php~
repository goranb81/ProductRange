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


}

