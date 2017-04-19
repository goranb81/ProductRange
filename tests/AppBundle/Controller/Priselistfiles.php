<?php
/**
 * Created by PhpStorm.
 * User: bor8246
 * Date: 12.4.2017
 * Time: 13:07
 */

namespace AppBundle\Entity;

    use Doctrine\ORM\Mapping as ORM;
    use Symfony\Component\HttpFoundation\File\File;
    use Vich\UploaderBundle\Mapping\Annotation as Vich;

    /**
     * @ORM\Table(name="priselistfiles")
     * @ORM\Entity
     * @Vich\Uploadable
     */
    class Priselistfiles
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
         * @var string
         *
         * @ORM\Column(type="string", length=255)
         */
        
        private $supplierName;

        /**
         * @return string
         */
        public function getSupplierName()
        {
            return $this->supplierName;
        }

        /**
         * @param string $supplierName
         */
        public function setSupplierName($supplierName)
        {
            $this->supplierName = $supplierName;
        }

        /**
         * NOTE: This is not a mapped field of entity metadata, just a simple property.
         *
         * @Vich\UploadableField(mapping="excel_file", fileNameProperty="excelName", size="excelSize")
         *
         * @var File
         */
        private $excelFile;

        /**
         * @ORM\Column(type="string", length=255)
         *
         * @var string
         */
        private $excelName;

        /**
         * @ORM\Column(type="integer")
         *
         * @var integer
         */
        private $excelSize;

        /**
         * @ORM\Column(type="datetime")
         *
         * @var \DateTime
         */
        private $updatedAt;

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
         * @param string $excelName
         *
         * @return Priselistfiles
         */
        public function setExcelName($excelName)
        {
            $this->excelName = $excelName;

            return $this;
        }

        /**
         * @return string|null
         */
        public function getExcelName()
        {
            return $this->excelName;
        }

        /**
         * @param integer $excelSize
         *
         * @return Priselistfiles
         */
        public function setExcelSize($excelSize)
        {
            $this->excelSize = $excelSize;

            return $this;
        }

        /**
         * @return integer|null
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
     * @return Priselistfiles
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
