<?php
/**
 * Created by PhpStorm.
 * User: bor8246
 * Date: 10.2.2017
 * Time: 12:19
 */

namespace AppBundle\Controller;

use AppBundle\HelperClasses\ExcelProduct;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Pricelists;

/**
 * @Route("/admin")
 */
class AdminController extends Controller
{
    /**
     * @Route("/", name="admin_index")
     */
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('admin/admin_base.html.twig');
    }

    /**
     * @Route("/import_pricelist", name="import_pricelist")
     */
    public function importAction(Request $request)
    {
        // provide supplierName and supplierID
        // provide name of excel file

        /*
         product status:
            active
            inactive
            new_product
            tresh
        /*
        Pricelists(externalProductId
                supplierId,
                supplierName,
                productName,
                price,
                inputDate,
                status)
        */

        //provide from Form supplierId and supplierName
        $supplierId = 4;
        $supplierName = 'Kodak';
        // list of objects that represent existing products in pricelist DB table(with status active inactive and new_product exclude status trash )
        $listOFExistingProducts = $this->getListOFExistingProducts($supplierId);

        //list of objects that represent products from pricelist's excel file
        $listOfExcelProducts = $this->getListOfExcelProducts('C:\ALTI 2011-01-10.xls');

        //timestamp - start import
        $dateOfStart = time();

        //get database connection object
        $em = $this->getDoctrine()->getManager();
        $conn = $em->getConnection();


        //loop throw excel products
        foreach($listOfExcelProducts as $productExcel){

            // product from excel is not found in DB
            $p = 'not_found';

            //loop throw existing products list and check does excel product exists in DB
            foreach($listOFExistingProducts as $productDB){
                // compare names becouse product from excel and product from database is linked by name
                // if name is the same then compare product status: new_product, active, inactive
                if(strcmp( $productDB->getProductname(), $productExcel.getProductname()) == 0){
                    //return current Unix timestamp in miliseconds(int)
                    $timestamp = time();

                    //convert timestamp from miliseconds(int) in timestamp format Y-m-d H:i:s
                    $mysqlTimestampFormat = date("Y-m-d H:i:s", $timestamp);

                    // update price and input date if status is new_product
                    if(strcmp($productDB->getStatus(),'new_product') == 0) {
                        $conn->executeUpdate('UPDATE pricelists SET price=?, inputDate=? WHERE externalProductId=?',
                                            array($productExcel.getPrice(),$mysqlTimestampFormat,$productDB.getExternalproductid()));

                        //external product exists in DB
                        $p = 'found';
                    }

                    //update price and input date if status is active
                }elseif(strcmp($productDB->getStatus(), 'active') == 0){
                    $conn->executeUpdate('UPDATE pricelists SET price=?, inputDate=? WHERE externalProductId=?',
                        array($productExcel.getPrice(),$mysqlTimestampFormat,$productDB.getExternalproductid()));

                    //external product exists in DB
                    $p = 'found';

                    //update price, input date and status to active if status is inactive
                }elseif(strcmp($productDB->getStatus(), 'inactive') == 0){
                    $conn->executeUpdate('UPDATE pricelists SET price=?, inputDate=?, status=? WHERE externalProductId=?',
                        array($productExcel.getPrice(),$mysqlTimestampFormat,'active',$productDB.getExternalproductid()));

                    //external product exists in DB
                    $p = 'found';
                }

            }

            //if productExcel doesn't exist in DB then insert productExcel into DB
            if($p=='not_found'){
                //return current Unix timestamp in miliseconds(int)
                $timestamp = time();

                //convert timestamp from miliseconds(int) in timestamp format Y-m-d H:i:s
                $mysqlTimestampFormat = date("Y-m-d H:i:s", $timestamp);

                $conn->executeUpdate('INSERT INTO pricelists(supplierId, supplierName, productName, price, inputDate, status) VALUES 
                (?,?,?,?,?,?,?)',array($supplierId, $supplierName, $productExcel.getProductname(),$productExcel.getPrice(), $mysqlTimestampFormat,'new_product'));
            }

        }

        // list of objects that represent existing products in pricelist DB table(with status active)
        //$listOFExistingProductsActive = getlistOFExistingProductsActive($supplierId);

        // loop throw all products and set to inactive all of them which inputdate < $dateOfStart
//        foreach ($listOFExistingProductsActive as $productDB){
//            if($productDB.getInputDate() < $dateOfStart)
//                $productDB.setStatus('inactive');
//        }

         // list of objects that represent existing products in pricelist DB table(with status new_product)
        //$listOFExistingProductsActive = getlistOFExistingProductsNew($supplierId);

        // loop throw all products and set to inactive all of them which inputdate < $dateOfStart
//       foreach ($listOFExistingProductsNew as $productDB){
//        if($productDB.getInputDate() < $dateOfStart)
//            //execute DELETE PRODUCT FROM TABLE PRICELISTS PRODUCT WHERE PRODUCTID = $productDB.getExternalId()
//        }


        return $this->render('admin/import_pricelist.html.twig');
    }

    // get all products from table Pricelists for particularly supplier
    private function getListOFExistingProducts($supplierId){
        $em = $this->getDoctrine()->getManager();
        $listOfExistingProducts = $em->getRepository('AppBundle\Entity\Pricelists')->findBy(array('supplierid' => $supplierId));
        return $listOfExistingProducts;
    }

    //load all products from Excel pricelist into array of ExcelProduct objects
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
