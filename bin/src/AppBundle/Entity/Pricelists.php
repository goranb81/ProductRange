<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Pricelists
 *
 * @ORM\Table(name="pricelists")
 * @ORM\Entity
 */
class Pricelists
{
    /**
     * @var integer
     *
     * @ORM\Column(name="supplierId", type="integer", nullable=false)
     */
    private $supplierid;

    /**
     * @var string
     *
     * @ORM\Column(name="supplierName", type="string", length=100, nullable=false)
     */
    private $suppliername;

    /**
     * @var string
     *
     * @ORM\Column(name="productName", type="string", length=500, nullable=true)
     */
    private $productname;

    /**
     * @var float
     *
     * @ORM\Column(name="price", type="float", precision=10, scale=0, nullable=true)
     */
    private $price;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="inputDate", type="datetime", nullable=false)
     */
    private $inputdate = 'CURRENT_TIMESTAMP';

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=20, nullable=false)
     */
    private $status;

    /**
     * @var integer
     *
     * @ORM\Column(name="externalProductId", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $externalproductid;


}

