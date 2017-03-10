<?php
/**
 * Created by PhpStorm.
 * User: bor8246
 * Date: 10.3.2017
 * Time: 13:17
 *
 * This service worked with ExcelProduct class
 * List of that class represent products from excel pricelist
 */

namespace Bee\InputExcelBundle\ServiceClasses;

use Bee\InputExcelBundle\HelperClasses\ExcelProduct;

class ProductExcel
{
    protected $excelFileName;

    /**
     * @return mixed
     */
    public function getExcelFileName()
    {
        return $this->excelFileName;
    }

    /**
     * @param mixed $excelFileName
     */
    public function setExcelFileName($excelFileName)
    {
        $this->excelFileName = $excelFileName;
    }

    //load all products from Excel pricelist into array of ExcelProduct objects
    public function getListOfExcelProducts(){
        /** automatically detect the correct reader to load for this file type */
        $excelReader = \PHPExcel_IOFactory::createReaderForFile($this->getExcelFileName());

        //if we dont need any formatting on the data
        //$excelReader->setReadDataOnly();

        //load data
        $excelObj = $excelReader->load($this->getExcelFileName());

        //convert Excel data into one array
        $excelObj->getActiveSheet()->toArray(null, true,true,true);

        //get all sheet names from the file
        $worksheetNames = $excelObj->getSheetNames($this->getExcelFileName());

        //set the current active worksheet by name. In this case it's first sheet
        $excelObj->setActiveSheetIndexByName($worksheetNames[0]);

        //sheet content array
        $sheetContent = $excelObj->getActiveSheet()->toArray(null, true,true,true);

        // convert sheet content array into array of ExcelProduct objects
        // each ExcelProduct object represent one sheet's row
        $arrayOfExcelProducts = $this->convertSheetContentIntoArrayOfExcelProduct($sheetContent);

        return $arrayOfExcelProducts;

    }

    // convert sheet content array into array of ExcelProduct objects
    // each ExcelProduct object represent one sheet's row
    private function convertSheetContentIntoArrayOfExcelProduct($sheetContent){
        $arrayOfExcelProducts = array();
        foreach ($sheetContent as $sheetRow ){
            $excelProduct = new ExcelProduct();

            //get name and price
            $name = $sheetRow['B'];
            $price = $sheetRow['I'];

            //change type of price to float
            settype($price, 'float');

            //name and price cannot have null value
            if(is_null($name) or is_null($price) ){
                // do nothing
            }else{

                //price must be greather than 0
                if($price > 0){
                    $excelProduct -> setPrice($price);
                    $excelProduct -> setProductname($name);
                    $arrayOfExcelProducts[]=$excelProduct;
                }

            }

        }

        return $arrayOfExcelProducts;
    }
}