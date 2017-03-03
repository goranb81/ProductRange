<?php
/**
 * Created by PhpStorm.
 * User: bor8246
 * Date: 28.2.2017
 * Time: 21:48
 */

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\HelperClasses\ExcelProduct;

class TestController extends Controller
{
    /**
     * @Route("/test", name="test")
     */
    public function testAction(Request $request)
    {
        //################################################
        // get list of existing products from DB find out by supplierid
//        $em = $this->getDoctrine()->getManager();
//        $listOfExistingProducts = $em->getRepository('AppBundle\Entity\Pricelists')->findBy(array('supplierid' => 4));
//        var_dump($listOfExistingProducts);




     //################################################
        //Update pricelist product in DB

        //return current Unix timestamp in miliseconds(int)
        $timestamp = time();

        //convert timestamp from miliseconds(int) in DateTime format Y-m-d H:i:s
        //we use DateTime to insert timestamp into MySql
        $mysqlTimestampFormat = date("Y-m-d H:i:s", $timestamp);
        //var_dump($mysqlTimestampFormat);

        //
        //get database connection object
//        $em = $this->getDoctrine()->getManager();
//        $conn = $em->getConnection();
//        //update pricelist product in DB
//        $conn->executeUpdate('UPDATE pricelists SET price=?, inputDate=? WHERE externalProductId=?', array(100,$mysqlTimestampFormat,1));

        //################################################
        //list of objects that represent existing products in pricelist DB table(with status active)

//        $em = $this->getDoctrine()->getManager();
//        $listOfExistingProductsActive = $em->getRepository('AppBundle\Entity\Pricelists')->findBy(array('supplierid' => 4, 'status'=>'active'));
////        var_dump($listOfExistingProductsActive);
//
//        // convert DateTime() from MySql DB into PHP timestamp variable
//        $timestamp = $listOfExistingProductsActive[0]->getInputdate()->getTimestamp();
//        var_dump($timestamp);

        //################################################
        //get all excel products

//        var_dump($this->getListOfExcelProducts('C:\TEST\ALTI 2011-01-10.xls'));



        //################################################
        //test comparing of timestamp variables
//        $timestamp = time();
//
//        $timestamp1 = time();
//
//        var_dump($timestamp);
//        var_dump($timestamp1);

        var_dump(date("Y-m-d H:i:s", 1488534204));
        var_dump(date("Y-m-d H:i:s", 1488533808));

        return $this->render('default/index.html.twig');
    }


    private function getListOfExcelProducts($excelFileName){
        /** automatically detect the correct reader to load for this file type */
        $excelReader = \PHPExcel_IOFactory::createReaderForFile($excelFileName);

        //if we dont need any formatting on the data
        //$excelReader->setReadDataOnly();

        //load data
        $excelObj = $excelReader->load($excelFileName);

        //convert Excel data into one array
        $excelObj->getActiveSheet()->toArray(null, true,true,true);

        //get all sheet names from the file
        $worksheetNames = $excelObj->getSheetNames($excelFileName);

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