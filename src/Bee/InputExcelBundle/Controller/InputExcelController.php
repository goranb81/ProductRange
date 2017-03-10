<?php

namespace Bee\InputExcelBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class InputExcelController extends Controller
{
//    /**
//     * @Route("/")
//     */
//    public function indexAction()
//    {
//        return $this->render('BeeInputExcelBundle:Default:index.html.twig');
//    }

    /**
     * @Route("/importpricelist", name="import_pricelist")
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
         *
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
        $excelFilename = 'C:\ALTI 2011-01-10.xls';

        // get input excel file service
        // set parameters
        $inputExcel = $this->get('bee_input_excel_inputexcel');
        $inputExcel->setSupplierId($supplierId);
        $inputExcel->setSupplierName($supplierName);
        $inputExcel->setExcelFileName($excelFilename);

        // execute function that inpux excel file content
        $inputExcel->inputExcelFile();

        return $this->render('admin/import_pricelist.html.twig');
    }

}
