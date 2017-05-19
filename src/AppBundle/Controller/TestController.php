<?php
/**
 * Created by PhpStorm.
 * User: bor8246
 * Date: 28.2.2017
 * Time: 21:48
 */

namespace AppBundle\Controller;

use Symfony\Bridge\Doctrine;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Bee\InputExcelBundle\HelperClasses\ExcelProduct;
use AppBundle\Entity\Pricelists;

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
//        $timestamp = time();

        //convert timestamp from miliseconds(int) in DateTime format Y-m-d H:i:s
        //we use DateTime to insert timestamp into MySql
//        $mysqlTimestampFormat = date("Y-m-d H:i:s", $timestamp);
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

//        var_dump(date("Y-m-d H:i:s", 1488534204));
//        var_dump(date("Y-m-d H:i:s", 1488533808));

        //################################################
        //get entity using criteria using doctrine
        // that is similar like write statement with WHERE clause


//        $em = $this->getDoctrine()->getManager();
//        $qb = $em->createQueryBuilder();


        //version 1 query builder
//        $qb -> select('p')
//              ->from('AppBundle:Pricelists', 'p')
//              ->where('p.suppliername = :suppliername')
//              ->andWhere('p.status <> :status')
//              ->setParameters(array('suppliername' => 'Kodak', 'status' => 'trash'));
//        $result = $qb->getQuery()->getArrayResult();
//
//        $pricelistsEntitisArray = $this->convertArrayResultIntoEntitysArray($result);
//
//        $this->refreshAllEntityObjects($pricelistsEntitisArray);
//
//        var_dump($pricelistsEntitisArray);


        //version 2 by Entity manager becouse it returns directly array of entity's objects
//        $status = "'trash'";
//        $supplierid = 4;
//        $statement = "select p from AppBundle\Entity\Pricelists p where p.status <> " . $status . " and p.supplierid = " . $supplierid;
//        $q = $em->createQuery($statement);
//        $arrayOfProducts = $q->getResult();
//        $this->refreshAllEntityObjects($arrayOfProducts);
//        var_dump($arrayOfProducts);
//        var_dump($statement);

        // test bee_input_excel_productdb service
//        $em = $this->container->get('doctrine');
//        var_dump($em);
//        $productDB = $this->get('bee_input_excel_productdb');
//        var_dump($productDB->getListOFExistingProducts(4));

        // ###################################################
        // test pricelist directory

//        $dir = $this->getParameter('excelpricelist_directory');
//
//        return $this->render('default/test.html.twig', array('dir'=>$dir));

        // ###################################################
        // test Pricelistfiles entity

//        $em = $this->getDoctrine()->getManager();
//        $pricelistfile = $em->getRepository('AppBundle\Entity\Pricelistfiles');
//        var_dump($pricelistfile);
//        die();

        // ###################################################
        // test non Symfony Form - use /admin/test_form.html.twig
//        $post = Request::createFromGlobals();
//
//        if ($post->request->has('submit')) {
//            $name = $post->request->get('name');
//            var_dump($post->request->get('name'));
//        } else {
//            $name = 'Not submitted yet';
//            var_dump($post->request->get('name'));
//        }
//
//        return $this->render('test_form.html.twig', array('name' => $name));

        // ###################################################
        // Linking products page
//        $em = $this->getDoctrine()->getEntityManager();
//        $products = $em->getRepository('AppBundle\Entity\Products')->findAll();
//        $suppliers = $em->getRepository('AppBundle\Entity\Suppliers')->findAll();
//        $externalProducts =  $em->getRepository('AppBundle\Entity\Pricelists')->findBy(array('supplierid' => $suppliers[0]->getSupplierid(), 'status' => 'new_product'));
//        return $this->render('admin/linking_products.html.twig', array('products' => $products, 'suppliers' => $suppliers, 'exproducts' => $externalProducts));

        // ###################################################
        // Get all Pricelists Entity's
//        $em = $this->getDoctrine()->getEntityManager();
//        $pricelistfiles = $em->getRepository('AppBundle\Entity\Pricelistfiles')->findAll();
//        var_dump($pricelistfiles);

        // ###################################################
        // Get global variables $x, $y
//        var_dump($GLOBALS['x']);
//        var_dump($GLOBALS['y']);

        return $this->render('test_skladiste.html.twig');
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

    private function refreshAllEntityObjects($ArrayOfObject){
        $em = $this->getDoctrine()->getManager();
        foreach ($ArrayOfObject as $object){
            $em->refresh($object);
        }
    }

    // transform ArrayResult (we get when execute $qb->getQuery()->getArrayResult();)
    // into array of Pricelists entity's objects
    private function convertArrayResultIntoEntitysArray($arrayResult){
        $entitiesArray = array();
        foreach ($arrayResult as $element){
            $p = new Pricelists();
            $p->setSupplierid($element['supplierid']);
            $p->setSuppliername($element['suppliername']);
            $p->setProductname($element['productname']);
            $p->setPrice($element['price']);
            $p->setInputdate($element['inputdate']);
            $p->setStatus($element['status']);
            $p->getExternalproductid($element['externalproductid']);
            $entitiesArray[] = $p;
        }

        return $entitiesArray;


        //example of element
//        array (size=7)
//        'supplierid' => int 4
//        'suppliername' => string 'Kodak' (length=5)
//        'productname' => string 'PPPPPP' (length=6)
//        'price' => float 144.75
//        'inputdate' =>
//        object(DateTime)[441]
//        public 'date' => string '2017-03-06 14:59:36.000000' (length=26)
//        public 'timezone_type' => int 3
//        public 'timezone' => string 'Europe/Berlin' (length=13)
//        'status' => string 'inactive' (length=8)
//        'externalproductid' => int 68

    }




}