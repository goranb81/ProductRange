<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Pricelistfiles
 *
 * @ORM\Table(name="pricelistfiles")
 * @ORM\Entity
 */
class Pricelistfiles
{
    /**
     * @var string
     *
     * @ORM\Column(name="supplier_name", type="string", length=255, nullable=false)
     */
    private $supplierName;

    /**
     * @var string
     *
     * @ORM\Column(name="excel_name", type="string", length=255, nullable=false)
     */
    private $excelName;

    /**
     * @var integer
     *
     * @ORM\Column(name="excel_size", type="integer", nullable=false)
     */
    private $excelSize;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=false)
     */
    private $updatedAt;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;



    /**
     * Set supplierName
     *
     * @param string $supplierName
     *
     * @return Pricelistfiles
     */
    public function setSupplierName($supplierName)
    {
        $this->supplierName = $supplierName;

        return $this;
    }

    /**
     * Get supplierName
     *
     * @return string
     */
    public function getSupplierName()
    {
        return $this->supplierName;
    }

    /**
     * Set excelName
     *
     * @param string $excelName
     *
     * @return Pricelistfiles
     */
    public function setExcelName($excelName)
    {
        $this->excelName = $excelName;

        return $this;
    }

    /**
     * Get excelName
     *
     * @return string
     */
    public function getExcelName()
    {
        return $this->excelName;
    }

    /**
     * Set excelSize
     *
     * @param integer $excelSize
     *
     * @return Pricelistfiles
     */
    public function setExcelSize($excelSize)
    {
        $this->excelSize = $excelSize;

        return $this;
    }

    /**
     * Get excelSize
     *
     * @return integer
     */
    public function getExcelSize()
    {
        return $this->excelSize;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return Pricelistfiles
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }
}
