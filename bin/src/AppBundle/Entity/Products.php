<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Products
 *
 * @ORM\Table(name="products")
 * @ORM\Entity
 */
class Products
{
    /**
     * @var integer
     *
     * @ORM\Column(name="groups", type="smallint", nullable=false)
     */
    private $groups;

    /**
     * @var string
     *
     * @ORM\Column(name="productName", type="string", length=500, nullable=false)
     */
    private $productname;

    /**
     * @var integer
     *
     * @ORM\Column(name="manufacturerId", type="integer", nullable=false)
     */
    private $manufacturerid;

    /**
     * @var string
     *
     * @ORM\Column(name="manufacturerName", type="string", length=100, nullable=false)
     */
    private $manufacturername;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="inputDate", type="datetime", nullable=false)
     */
    private $inputdate = 'CURRENT_TIMESTAMP';

    /**
     * @var integer
     *
     * @ORM\Column(name="price", type="integer", nullable=true)
     */
    private $price;

    /**
     * @var integer
     *
     * @ORM\Column(name="internalProductId", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $internalproductid;


}

