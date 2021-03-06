<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Pricelistfiles
 *
 * @ORM\Table(name="pricelistfiles")
 * @ORM\Entity
 * @Vich\Uploadable
 */
class Pricelistfiles
{
    /**
     * @var integer
     *
     * @ORM\Column(name="supplierid", type="integer", nullable=true)
     */
    private $supplierid;

    /**
     * @var string
     *
     * @Assert\NotBlank(message = "The supplier name is not defined")
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
     * Set supplierid
     *
     * @param integer $supplierid
     *
     * @return Pricelistfiles
     */
    public function setSupplierid($supplierid)
    {
        $this->supplierid = $supplierid;

        return $this;
    }


    /**
     * NOTE: This is not a mapped field of entity metadata, just a simple property.
     *
     * @Assert\NotBlank(message = "The excel name is not defined")
     *
     * @Vich\UploadableField(mapping="excel_file", fileNameProperty="excelName", size="excelSize")
     *
     * @var File
     */
    private $excelFile;

    /**
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the  update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $excel
     *
     * @return Priselistfiles
     */
    public function setExcelFile(File $excel = null)
    {
        $this->excelFile = $excel;

        if ($excel) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTimeImmutable();
        }

        return $this;
    }

    /**
     * @return File|null
     */
    public function getExcelFile()
    {
        return $this->excelFile;
    }


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
