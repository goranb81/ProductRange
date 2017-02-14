<?php
/**
 * Created by PhpStorm.
 * User: bor8246
 * Date: 10.2.2017
 * Time: 12:19
 */

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

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
        product(externalProductId
                supplierId,
                supplierName,
                productName,
                price,
                inputDate,
                status)
        */
        // list of objects that represent existing products in pricelist DB table
        $listOFExistingProducts = getlistOFExistingProducts($supplierId);

        //list of objects that represent products from pricelist's excel file
        $listOfExcelProducts = getListOfExcelProducts($excelFile);

        //timestamp - start import
        $dateOfStart = time();

        //loop throw excel products
        foreach($productExcel in $listOfExcelProducts){

            // product from excel is not found in DB
            $p = 'not_found';

            //loop throw existing products list and check Does excel product exists in DB
            foreach($productDB in $listOFExistingProducts){
                // compare names becouse product from excel and product from database is linked with name
                if($productDB.getProductName() == $productExcel.getProductName())then
                    if($productDB.getStatus = 'tresh') then break;
                    else{
                        // execute 'UPDATE PRODUCTRANGE SET STATUS="AKTIVAN" WHERE INTERNALPRODUCTID = $productDB.getiNTERNALpRODUCTid'
                        // execute 'UPDATE PRODUCTRANGE SET PRICE=$PRODUCTeXCEL.GETpRICE() WHERE INTERNALPRODUCTID = $productDB.getiNTERNALpRODUCTid'
                // execute 'UPDATE PRODUCTRANGE SET INPUTDATE=TIME() WHERE INTERNALPRODUCTID = $productDB.getiNTERNALpRODUCTid'

                //external product exists in DB
                p = 'found';
            }
            }

        }

        return $this->render('admin/import_pricelist.html.twig');
    }
}
