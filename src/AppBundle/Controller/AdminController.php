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
        // list of objects that represent existing products in pricelist DB table(with status active inactive and new_product exclude status trash )
        $listOFExistingProducts = getlistOFExistingProducts1($supplierId);

        //list of objects that represent products from pricelist's excel file
        $listOfExcelProducts = getListOfExcelProducts($excelFile);

        //timestamp - start import
        $dateOfStart = time();

        //loop throw excel products
        foreach($listOfExcelProducts as $productExcel){

            // product from excel is not found in DB
            $p = 'not_found';

            //loop throw existing products list and check Does excel product exists in DB
            foreach($listOFExistingProducts as $productDB){
                // compare names becouse product from excel and product from database is linked by name
                if($productDB.getProductName() == $productExcel.getProductName())then{
                    if($productDB.getStatus() = 'new_product') then {
                        //execute 'UPDATE PRODUCTRANGE.PRICELISTS SET PRICE= $productExcel.getPrice(), inputdate = time WHERE externalproductid = $productDB.getExternalProductid()
                        //external product exists in DB
                        p = 'found';
                }elseif($productDB.getStatus() = 'active')then{
                    // execute 'UPDATE PRODUCTRANGE.PRICELISTS SET PRICE=$PRODUCTeXCEL.GETpRICE() WHERE externalproductid = $productDB.getExternalProductid()'
                    // execute 'UPDATE PRODUCTRANGE.PRICELISTS SET INPUTDATE=TIME() WHERE externalproductid = $productDB.getExternalProductid()'

                    //external product exists in DB
                    p = 'found';
                }elseif($productDB.getStatus() = 'inactive'){
                    // execute 'UPDATE PRODUCTRANGE.PRICELISTS SET STATUS="AKTIVAN" WHERE externalproductid = $productDB.getExternalProductid()'
                    // execute 'UPDATE PRODUCTRANGE.PRICELISTS SET PRICE=$PRODUCTeXCEL.GETpRICE() WHERE externalproductid = $productDB.getExternalProductid()'
                    // execute 'UPDATE PRODUCTRANGE.PRICELISTS SET INPUTDATE=TIME() WHERE externalproductid = $productDB.getExternalProductid()'

                    //external product exists in DB
                    p = 'found';
                }
                }
            }

            //if productExcel doesn't exist in DB then insert productExcel into DB
            if(p='not_found'){
                //ececute 'UPDATE PRODUCTRANGE.PRICELISTS SET SUPPLIERNAME, SUPPLIERID, PRODUCTNAME, PRICE, STATUS=''new_product'
            }

        }

        // list of objects that represent existing products in pricelist DB table(with status active)
        $listOFExistingProductsActive = getlistOFExistingProductsActive($supplierId);

        // loop throw all products and set to inactive all of them which inputdate < $dateOfStart
        foreach ($listOFExistingProductsActive as $productDB){
            if($productDB.getInputDate() < $dateOfStart)
                $productDB.setStatus('inactive');
        }

         // list of objects that represent existing products in pricelist DB table(with status new_product)
        $listOFExistingProductsActive = getlistOFExistingProductsNew($supplierId);

        // loop throw all products and set to inactive all of them which inputdate < $dateOfStart
        foreach ($listOFExistingProductsNew as $productDB){
        if($productDB.getInputDate() < $dateOfStart)
            //execute DELETE PRODUCT FROM TABLE PRICELISTS PRODUCT WHERE PRODUCTID = $productDB.getExternalId()
        }


        return $this->render('admin/import_pricelist.html.twig');
    }
}
