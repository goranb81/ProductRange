<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Suppliers
 *
 * @ORM\Table(name="suppliers")
 * @ORM\Entity
 */
class Suppliers
{
    /**
     * @var string
     *
     * @ORM\Column(name="supplierName", type="string", length=50, nullable=false)
     */
    private $suppliername;

    /**
     * @var integer
     *
     * @ORM\Column(name="supplierId", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $supplierid;



    /**
     * Set suppliername
     *
     * @param string $suppliername
     *
     * @return Suppliers
     */
    public function setSuppliername($suppliername)
    {
        $this->suppliername = $suppliername;

        return $this;
    }

    /**
     * Get suppliername
     *
     * @return string
     */
    public function getSuppliername()
    {
        return $this->suppliername;
    }

    /**
     * Get supplierid
     *
     * @return integer
     */
    public function getSupplierid()
    {
        return $this->supplierid;
    }
}
