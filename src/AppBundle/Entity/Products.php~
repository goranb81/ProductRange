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



    /**
     * Set groups
     *
     * @param integer $groups
     *
     * @return Products
     */
    public function setGroups($groups)
    {
        $this->groups = $groups;

        return $this;
    }

    /**
     * Get groups
     *
     * @return integer
     */
    public function getGroups()
    {
        return $this->groups;
    }

    /**
     * Set productname
     *
     * @param string $productname
     *
     * @return Products
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
     * Set manufacturerid
     *
     * @param integer $manufacturerid
     *
     * @return Products
     */
    public function setManufacturerid($manufacturerid)
    {
        $this->manufacturerid = $manufacturerid;

        return $this;
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

    /**
     * Set manufacturername
     *
     * @param string $manufacturername
     *
     * @return Products
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
     * Set inputdate
     *
     * @param \DateTime $inputdate
     *
     * @return Products
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
     * Set price
     *
     * @param integer $price
     *
     * @return Products
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return integer
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Get internalproductid
     *
     * @return integer
     */
    public function getInternalproductid()
    {
        return $this->internalproductid;
    }
}
