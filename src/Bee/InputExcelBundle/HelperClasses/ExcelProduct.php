<?php
/**
 * Created by PhpStorm.
 * User: bor8246
 * Date: 27.2.2017
 * Time: 11:25
 */

namespace Bee\InputExcelBundle\HelperClasses;


class ExcelProduct
{
    private $productname;

    private $price;

    /**
     * @return mixed
     */
    public function getProductname()
    {
        return $this->productname;
    }

    /**
     * @param mixed $productname
     */
    public function setProductname($productname)
    {
        $this->productname = $productname;
    }

    /**
     * @return mixed
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param mixed $price
     */
    public function setPrice($price)
    {
        $this->price = $price;
    }




}