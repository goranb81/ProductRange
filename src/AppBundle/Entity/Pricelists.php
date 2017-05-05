<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Pricelists
 *
 * @ORM\Table(name="pricelists")
 * @ORM\Entity
 */
class Pricelists implements \JsonSerializable
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



    /**
     * Set supplierid
     *
     * @param integer $supplierid
     *
     * @return Pricelists
     */
    public function setSupplierid($supplierid)
    {
        $this->supplierid = $supplierid;

        return $this;
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

    /**
     * Set suppliername
     *
     * @param string $suppliername
     *
     * @return Pricelists
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
     * Set productname
     *
     * @param string $productname
     *
     * @return Pricelists
     */
    public function setProductname($productname)
    {
        $this->productname = $productname;

        return $this;
    }

    /**
     * Get productname
     *
     * @return string
     */
    public function getProductname()
    {
        return $this->productname;
    }

    /**
     * Set price
     *
     * @param float $price
     *
     * @return Pricelists
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return float
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set inputdate
     *
     * @param \DateTime $inputdate
     *
     * @return Pricelists
     */
    public function setInputdate($inputdate)
    {
        $this->inputdate = $inputdate;

        return $this;
    }

    /**
     * Get inputdate
     *
     * @return \DateTime
     */
    public function getInputdate()
    {
        return $this->inputdate;
    }

    /**
     * Set status
     *
     * @param string $status
     *
     * @return Pricelists
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Get externalproductid
     *
     * @return integer
     */
    public function getExternalproductid()
    {
        return $this->externalproductid;
    }

    /**
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    function jsonSerialize()
    {
        // TODO: Implement jsonSerialize() method.
        return (object) get_object_vars($this);
    }
}
