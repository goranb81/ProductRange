<?php
/**
 * Created by PhpStorm.
 * User: bor8246
 * Date: 10.3.2017
 * Time: 14:18
 */

namespace Bee\InputExcelBundle\ServiceClasses;

use Bee\InputExcelBundle\ServiceClasses\ProductDB;
use Bee\InputExcelBundle\ServiceClasses\ProductExcel;
use Doctrine\ORM\EntityManager;
use Doctrine\Bundle\DoctrineBundle\Registry;


class InputExcel
{
    protected $supplierId;

    protected $supplierName;

    protected $excelFileName;

    protected $nameColumn;

    /**
     * @return mixed
     */
    public function getNameColumn()
    {
        return $this->nameColumn;
    }

    /**
     * @param mixed $nameColumn
     */
    public function setNameColumn($nameColumn)
    {
        $this->nameColumn = $nameColumn;
    }

    protected $priceColumn;

    /**
     * @return mixed
     */
    public function getPriceColumn()
    {
        return $this->priceColumn;
    }

    /**
     * @param mixed $priceColumn
     */
    public function setPriceColumn($priceColumn)
    {
        $this->priceColumn = $priceColumn;
    }

    protected $em;

    public $doctrine;

    public $excelProductUtility;

    public $pricelistEntityUtility;

    /**
     * InputExcel constructor.
     * @param $excelProductUtility
     * @param $pricelistEntityUtility
     *
     */
    public function __construct(ProductExcel $excelProductUtility, ProductDB $pricelistEntityUtility, Registry $doctrine)
    {
        $this->excelProductUtility = $excelProductUtility;
        $this->pricelistEntityUtility = $pricelistEntityUtility;
        $this->doctrine = $doctrine;
        $this->em = $this->doctrine->getManager();
    }

    /**
     * @return \Doctrine\Common\Persistence\ObjectManager|object
     */
    public function getEm()
    {
        return $this->em;
    }

    /**
     * @param \Doctrine\Common\Persistence\ObjectManager|object $em
     */
    public function setEm($em)
    {
        $this->em = $em;
    }

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

    /**
     * @return mixed
     */
    public function getSupplierId()
    {
        return $this->supplierId;
    }

    /**
     * @param mixed $supplierId
     */
    public function setSupplierId($supplierId)
    {
        $this->supplierId = $supplierId;
    }

    /**
     * @return mixed
     */
    public function getSupplierName()
    {
        return $this->supplierName;
    }

    /**
     * @param mixed $supplierName
     */
    public function setSupplierName($supplierName)
    {
        $this->supplierName = $supplierName;
    }

    public function inputExcelFile(){
        // get service that works with Pricelist Entity
//        $pricelistEntityUtility = $this->get('bee_input_excel_productdb');

        // get service that works with helper ExcelProduct class
//        $excelProductUtility = $this->get('bee_input_excel_productexcel');
        $this->excelProductUtility->setExcelFileName($this->getExcelFileName());

        // list of objects that represent existing products in pricelist DB table(with status active inactive and new_product exclude status trash )
        $listOFExistingProducts = $this->pricelistEntityUtility->getListOFExistingProducts($this->getSupplierId());

        //set excel pricelist filename
        //set name column
        //select price column
        $this->excelProductUtility->setExcelFileName($this->getExcelFileName());
        $this->excelProductUtility->setPriceColumn($this->getPriceColumn());
        $this->excelProductUtility->setNameColumn($this->getNameColumn());
        //list of objects that represent products from pricelist's excel file
        $listOfExcelProducts = $this->excelProductUtility->getListOfExcelProducts();

        //timestamp - start import
        $dateOfStart = time();

//        ########################################################
//        LOG

//        var_dump('Date of start input');
//        var_dump(date("Y-m-d H:i:s", $dateOfStart));

//        LOG
//        ########################################################

        //time delay becouse without delay
        //dateOfStart(timestamp) is equal to product imput date(timestamp)
        //why the timestamps are equal? script set that timestams in the same second!!!!!
        sleep(1);

        //get database connection object
        $conn = $this->getEm()->getConnection();

        //loop throw excel products
        foreach($listOfExcelProducts as $productExcel){

            // product from excel is not found in DB
            $p = 'not_found';

            //loop throw existing products list and check does excel product exists in DB
            foreach($listOFExistingProducts as $productDB) {

                // compare names becouse product from excel and product from database is linked by name
                // name of $productDB->getProductname() and $productExcel->getProductname() must be the same
                // and $productDB->getStatus() must be different than trash which means we ignore product which have status trash

                // if name is the same and status isn't trash then compare product status: new_product, active, inactive

                if (strcmp($productDB->getProductname(), $productExcel->getProductname()) == 0) {

                    // update price and input date if status is new_product
                    if (strcmp($productDB->getStatus(), 'new_product') == 0) {

                        //get current DateTime. That format is compatibile to MySql timestamp type
                        $mysqlTimestampFormat = $this->getCurrentDateTime();

                        $conn->executeUpdate('UPDATE pricelists SET price=?, inputDate=? WHERE externalProductId=?',
                            array($productExcel->getPrice(), $mysqlTimestampFormat, $productDB->getExternalproductid()));

                        //external product exists in DB
                        $p = 'found';

//                        ########################################################
//                        LOG
//
//                        var_dump('change existing new product');
//                        var_dump($mysqlTimestampFormat);
//
//                        LOG
//                        ########################################################


                        //update price and input date if status is active
                    } elseif (strcmp($productDB->getStatus(), 'active') == 0) {
                        var_dump('active');

                        //get current DateTime. That format is compatibile to MySql timestamp type
                        $mysqlTimestampFormat = $this->getCurrentDateTime();

                        $conn->executeUpdate('UPDATE pricelists SET price=?, inputDate=? WHERE externalProductId=?',
                            array($productExcel->getPrice(), $mysqlTimestampFormat, $productDB->getExternalproductid()));

                        //external product exists in DB
                        $p = 'found';

//                        ########################################################
//                        LOG
//
//                        var_dump('change existing active product');
//                        var_dump($mysqlTimestampFormat);
//
//                        LOG
//                        ########################################################

                        //update price, input date and status to active if status is inactive
                    } elseif (strcmp($productDB->getStatus(), 'inactive') == 0) {
                        var_dump('inactive');

                        //get current DateTime. That format is compatibile to MySql timestamp type
                        $mysqlTimestampFormat = $this->getCurrentDateTime();

                        $conn->executeUpdate('UPDATE pricelists SET price=?, inputDate=?, status=? WHERE externalProductId=?',
                            array($productExcel->getPrice(), $mysqlTimestampFormat, 'active', $productDB->getExternalproductid()));


                        //external product exists in DB
                        $p = 'found';

//                        ########################################################
//                        LOG
//
//                        var_dump('change existing inactive product');
//                        var_dump($mysqlTimestampFormat);
//
//                        LOG
//                        ########################################################
                    }
                }

            }


            //if productExcel doesn't exist in DB then insert productExcel into DB
            if($p == 'not_found'){

                //get current DateTime. That format is compatibile to MySql timestamp type
                $mysqlTimestampFormat = $this->getCurrentDateTime();

                $conn->executeUpdate('INSERT INTO pricelists(supplierId, supplierName, productName, price, inputDate, status) VALUES 
                (?,?,?,?,?,?)',array($this->getSupplierId(), $this->getSupplierName(), $productExcel->getProductname(),$productExcel->getPrice(), $mysqlTimestampFormat,'new_product'));

//                        ########################################################
//                        LOG
//
//                        var_dump('input new product');
//                        var_dump($mysqlTimestampFormat);
//
//                        LOG
//                        ########################################################
            }

        }

        // list of objects that represent existing products in pricelist DB table(with status active)
        $listOFExistingProductsActive = $this->pricelistEntityUtility->getlistOFExistingProductsActive($this->getSupplierId());

        // loop throw all products and set to inactive all of them which inputdate(timestamp) is less
        // than timestamp which represent time when we start import pricelists into DB
        // Products from DB which input date is less than $dateOfStart must be inactive becouse they
        // don't exist anymore in pricelists
        foreach ($listOFExistingProductsActive as $productDB){
            if($productDB->getInputDate()->getTimestamp() < $dateOfStart) {

                $conn->executeUpdate('UPDATE pricelists SET status=? WHERE externalProductId=?',
                    array('inactive', $productDB->getExternalproductid()));

            }
        }

        // list of objects that represent existing products in pricelist DB table(with status new_product)
        $listOFExistingProductsNew = $this->pricelistEntityUtility->getlistOFExistingProductsNew($this->getSupplierId());

        // loop throw all products and delete all of them which inputdate < $dateOfStart
        // It's means all product from pricelists(have status new_product)  which isn't connect to product
        // from products table and which dateInput is older than time when we start import must be deleted
        // That products don't exists anymore in pricelist excel file and we cannot consider to connect them to internal products
        foreach ($listOFExistingProductsNew as $productDB){
            if($productDB->getInputDate()-> getTimestamp() < $dateOfStart){

                $conn->executeUpdate('DELETE FROM pricelists  WHERE externalProductId=?',
                    array($productDB->getExternalproductid()));

            }
        }

        return 'Import pricelist process finished!';
    }

    private function getCurrentDateTime(){
        //return current Unix timestamp in miliseconds(int)
        $timestamp = time();

        //convert timestamp from miliseconds(int) in timestamp format Y-m-d H:i:s
        $mysqlTimestampFormat = date("Y-m-d H:i:s", $timestamp);

        return $mysqlTimestampFormat;
    }




}