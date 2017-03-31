<?php

namespace Bee\InputExcelBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class ImportExcelController extends Controller
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

        //predefined directory where are stored pricelists
        $dir = $this->getParameter('excelpricelist_directory');
        //provide from Form supplierId, supplierName and excel pricelist filename
        $supplierId = $request->request->get('asupplierid');
        $supplierName = $request->request->get('asupplier_name');
        $excelFilename = $request->request->get('apricelist_filename');

        // get input excel file service
        // set parameters
        $inputExcel = $this->get('bee_input_excel_inputexcel');
        $inputExcel->setSupplierId($supplierId);
        $inputExcel->setSupplierName($supplierName);
        $inputExcel->setExcelFileName($dir.'/'.$excelFilename);

        // execute function that inpux excel file content
        $inputExcel->inputExcelFile();

        return $this->render('admin/import_pricelist.html.twig');
    }

}
