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
use AppBundle\Entity\Pricelists;
use AppBundle\Entity\Pricelistfiles;
use Vich\UploaderBundle\Form\Type\VichFileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use AppBundle\Entity\Suppliers;
use Symfony\Component\HttpFoundation\JsonResponse;
use AppBundle\Entity\Internalexternal;

/**
 * @Route("/admin")
 */
class AdminController extends Controller
{
    /**
     * Show admin index page
     *
     * @Route("/", name="admin_index")
     */
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('admin/admin_index.html.twig');
    }

    /**
     * Show Import pricelist page
     *
     * @Route("/import_pricelist_page", name="import_pricelist_page")
     */
    public function importpricelistAction(Request $request){
        $listOfSupplier = $this->getAllSuppliers();

        // get list of filenames from excelpricelists directory
        $arrayOfExcelFilenames = $this->getFilesFromPricelistDirectoryByEntity($listOfSupplier[0]->getSuppliername());

        return $this->render('admin/import_pricelist.html.twig', array('suppliers' => $listOfSupplier, 'arrayFilenames' => $arrayOfExcelFilenames));
    }


//    get all filenames from pricelist's directory
    public function getFilesFromPricelistDirectoryByEntity($name){
        $em = $this->getDoctrine()->getEntityManager();

//        array of pricelists entity
//        this array have only firts supplier entities
        $pricelists = $em->getRepository('AppBundle\Entity\Pricelistfiles')->findBy(array('supplierName' => $name));

        $arrayOfExcelFilenames = array();
        foreach ($pricelists as $pricelist){
            $arrayOfExcelFilenames[] = $pricelist->getExcelName();
        }
        return $arrayOfExcelFilenames;
    }

//    !!!!! We don't use this function. Instead this we use getFilesFromPricelistDirectoryByEntity()
//    get all filenames from pricelists directory
    public function getFilesFromPricelistDirectory(){
        $dir = $this->getParameter('excelpricelist_directory');
        $arrayOfExcelFilenames = array();
        if ($handle = opendir($dir)) {

            while (false !== ($entry = readdir($handle))) {
                if ($entry != "." && $entry != ".." && is_file($dir.'/'.$entry)) {
                    $arrayOfExcelFilenames[] = $entry;
                }
            }
            closedir($handle);
        }

        return $arrayOfExcelFilenames;
    }

    /**
     * Show Import pricelist page
     *
     * @Route("/get_supplier_pricelist_filenames", name="get_supplier_pricelist_filenames")
     */
    public function getSupplierPricelistFilenamesAction(Request $request){
        //get name from Ajax
        $name = $request->request->get('name');

        // get list of filenames from excelpricelists directory
        $arrayOfExcelFilenames = $this->getFilesFromPricelistDirectoryByEntity($name);

        $response = new JsonResponse();
        $response->setData(array('pricelist_filenames' => $arrayOfExcelFilenames));
        return $response;
    }

    /**
     * Show Upload pricelist file page. This action is in use. This action is refer to Symfony form
     *
     * @Route("/upload_pricelist_page", name="upload_pricelist_page")
     */
    public function uploadpricelistAction(Request $request){
//        get list of objects that represent all suppliers
        $listOfSupplier = $this->getAllSuppliers();

//        array of supplier name. we use that array like choices
        $suppliers = array();
        foreach ($listOfSupplier as $supplier){
            $suppliers[$supplier->getSuppliername()] = $supplier->getSuppliername();
        }

        $pricelistfile = new Pricelistfiles();

        //create form
        $form = $this->createFormBuilder($pricelistfile)->add('excelFile', VichFileType::class, [
            'required' => false,
            'allow_delete' => true, // optional, default is true
            'download_link' => true, // optional, default is true
//            'download_uri' => '...', // optional, if not provided - will automatically resolved using storage
        ], array('label' => 'PPP'))
            ->add('supplierName', ChoiceType::class, [
                'choices' => $suppliers
            ], array('label' => 'Supplier name'))
            ->add('upload', SubmitType::class, array('label' => 'Upload pricelist'))->getForm();

        $form->handleRequest($request);

        if($form->isSubmitted()){
            if($form->isValid()){
                $em = $this->getDoctrine()->getManager();

                //we set excel size to constant value becouse without that value
                //app throw error excel_size value don't be null
                $pricelistfile->setExcelSize('12');

                $em->persist($pricelistfile);
                $em->flush();

                return $this->render('admin/upload_pricelist_task_success.html.twig');

            }else{
                //get validator
                $validator = $this->get('validator');

//                validate Pricelistfiles Entity class
//                we do that becouse we use annotation
//                in Entity to validate form fields
                $errors = $validator->validate($pricelistfile);

                return $this->render('admin/upload_pricelist_task_error.html.twig', array('error'=> $errors));
            }
        }
        return $this->render('admin/upload_pricelist.html.twig', array('form' => $form->createView()));
    }

    /**
     * Show Upload pricelist file page. This action is not in use. This action is refer to ordinary form (not Symfony)
     *
     * @Route("/upload_pricelist_page1", name="upload_pricelist_page1")
     */
//    public function uploadpricelist1Action(Request $request){
//        $uploadedFile = new UploadedFile();
//        $listOfSupplier = $this->getAllSuppliers();

//        print_r($_POST);
//        print_r($_FILES);
//        var_dump($_FILES);
//        var_dump($request->files);
//          var_dump($_FILES['pricelist_filename']['name']);
//        var_dump($request->request->get('name'));
//        var_dump($request->files->get('pricelist_filename'));
//        var_dump($request->files->all());

//        $post = Request::createFromGlobals();
//        var_dump($post->files->get('pricelist_filename'));
//        if($post->request->has('submit')){
//            $name = $post->request->get('name');
//            $supplier = $post->request->get('supplier_id');
//            var_dump($post->request->get('name'));
//        }else{
//            $name = "Not submitted yet";
//            var_dump($post->request->get('name'));
//        }
//        $pricelistfile = new Pricelistfiles();
//
//        //create form
//        $form = $this->createFormBuilder($pricelistfile)->add('excelFile', VichFileType::class, [
//            'required' => false,
//            'allow_delete' => true, // optional, default is true
//            'download_link' => true, // optional, default is true
////            'download_uri' => '...', // optional, if not provided - will automatically resolved using storage
//        ])
//            ->add('supplierName', TextType::class)
//            ->add('upload', SubmitType::class, array('label' => 'Upload pricelist'))->getForm();
//
//        $form->handleRequest($request);
//
//        if($form->isSubmitted() && $form->isValid()){
//            $em = $this->getDoctrine()->getManager();
//
//            //we set excel size to constant value becouse without that value
//            //app throw error excel_size value don't be null
//            $pricelistfile->setExcelSize('12');
//
//            $em->persist($pricelistfile);
//            $em->flush();
////           return $this->redirectToRoute('task_success');


//        return $this->render('admin/upload_pricelist1.html.twig');
//        , array('suppliers' => $listOfSupplier, 'name' => $name, 'supplier' => $supplier)

//    }

    /**
     * This controller action shows Delete pricelist file page
     *
     * @Route("/delete_pricelist_page", name="delete_pricelist_page")
     */
    public function deletefilesAction(Request $request){

        $listOfSuppliers = $this->getAllSuppliers();

        // Get all Pricelists Entity's
        $em = $this->getDoctrine()->getEntityManager();
        $pricelistfiles = $em->getRepository('AppBundle\Entity\Pricelistfiles')->findBy(array('supplierName'=>$listOfSuppliers[0]->getSuppliername()));
        return $this->render('admin/delete_pricelist.html.twig', array('suppliers' => $listOfSuppliers, 'files'=>$pricelistfiles));
    }

    /**
     *This controller action delete pricelist files of particularly supplier or
     *delete all files from upload directory /web/uploads/excelpricelists
     *
     * @Route("/deletepricelist", name="deletepricelist")
     */
    public function deletepricelistfilesAction(Request $request){
//        get supplier name
        $supplierName = $request->request->get('asupplier_name');
        $em = $this->getDoctrine()->getEntityManager();
        $pricelistfiles = $em->getRepository("AppBundle\Entity\Pricelistfiles")->findBy(array('supplierName' => $supplierName));

//        when we delete from table productrange.pricelists
//        all pricelists rows automatically we delete files from
//        /web/uploads/excelpricelists
//        This is ability of vich bundle

//        delete all entity from array of suppliers
        foreach ($pricelistfiles as $pricelist){
            $em->remove($pricelist);
            $em->flush();
        }

       $response = new JsonResponse();
       $response->setData(array('result' => 'OK'));
       return $response;
    }

    /**
     *delete selected pricelist files of particularly supplier
     *
     * @Route("/deletepricelist_selected", name="deletepricelist_selected")
     */
    public function deletepricelistfilesSelectedAction(Request $request){
//        get supplier name
        $arrayIDs = $request->request->get('parrayIDs');

        $em = $this->getDoctrine()->getEntityManager();

        $priselistfilesArray = array();

        foreach ($arrayIDs as $id){
            $pricelistfile = $em->getRepository("AppBundle\Entity\Pricelistfiles")->find($id);
            $priselistfilesArray[] = $pricelistfile;
        }


//        when we delete from table productrange.pricelists
//        all pricelists rows automatically we delete files from
//        /web/uploads/excelpricelists
//        This is ability of vich bundle

//        delete all entity from array of suppliers
        foreach ($priselistfilesArray as $pricelist){
            $em->remove($pricelist);
            $em->flush();
        }

        $response = new JsonResponse();
        $response->setData(array('message' => 'Selected files was successfuly deleted.'));
        return $response;
    }


    /**
     * @Route("/linking_products_page", name="linking_products_page")
     */
    public function linkProductsPageAction(Request $request){
        // Linking products page
        $em = $this->getDoctrine()->getEntityManager();
        $products = $em->getRepository('AppBundle\Entity\Products')->findAll();
        $suppliers = $em->getRepository('AppBundle\Entity\Suppliers')->findAll();
        $externalProducts =  $em->getRepository('AppBundle\Entity\Pricelists')->findBy(array('supplierid' => $suppliers[0]->getSupplierid(), 'status' => 'new_product'));
        return $this->render('admin/linking_products.html.twig', array('products' => $products, 'suppliers' => $suppliers, 'exproducts' => $externalProducts));
    }


    /**
     * @Route("/get_all_products", name="get_all_products")
     */
    public function getAllProductsAction(Request $request){
//        get ID from AJAX
//        linking_products.html.twig -> id="supplier_choose" select element
//        linking_products_pricelists.js
        $id = $request->request->get('id');

//        get all products from DB with particularly ID and status new_product
        $em = $this->getDoctrine()->getEntityManager();
        $pricelistsProducts = $em->getRepository("AppBundle\Entity\Pricelists")->findBy(array('supplierid' => $id, 'status' => 'new_product'));

//        serialize product array of object
        $pricelistsProductsJson = $this->get('serializer')->serialize($pricelistsProducts, 'json');

        $response = new JsonResponse();
        $response->setData(array('products'=>$pricelistsProductsJson));
        return $response;

    }

    /**
     * @Route("/get_all_files", name="get_all_files")
     */
    public function getAllFilesAction(Request $request){
//        get name from AJAX
//        delete_pricelist.html.twig -> id="supplier" select element
//        delete_pricelist.js
        $name = $request->request->get('name');

//        get all files from DB with particularly supplier name
        $em = $this->getDoctrine()->getEntityManager();
        $files = $em->getRepository("AppBundle\Entity\Pricelistfiles")->findBy(array('supplierName' => $name));

//        serialize files array of object
        $pricelistsProductsJson = $this->get('serializer')->serialize($files, 'json');

        $response = new JsonResponse();
        $response->setData(array('files'=>$pricelistsProductsJson));
        return $response;

    }

    /**
     * Link external with internal product
     * @Route("/link_products", name="link_products")
     */
    public function linkProductsAction(Request $request){
//        get external product info and internal product info from AJAX
//        linking_products_pricelists.js
        $internalProductID = $request->request->get('ainternal_product');
        $externalProductID = $request->request->get('aexternal_product');

        //get Entity Manager
        $em = $this->getDoctrine()->getEntityManager();

//        create Internalexternal object
        $internalexternal = new Internalexternal();

        //notice findBy return array of Objects
        $externalProducts = $em->getRepository("AppBundle\Entity\Pricelists")->findBy(array('externalproductid' => $externalProductID));
        $internalProducts = $em->getRepository("AppBundle\Entity\Products")->findBy(array('internalproductid' => $internalProductID));

//        status of external(pricelists) products became active
//        reson: external product was linking on internal products
//        and change status from new_product to active
        $externalProducts[0]->setStatus('active');
        $em->flush();

//        set property
        $internalexternal->setExternalproductid($externalProducts[0]);
        $internalexternal->setInternalproductid($internalProducts[0]);

        $em->persist($internalexternal);
        $em->flush();

        $message = "Linking products is finished successfuly.";
        $response = new JsonResponse();
        $response->setData(array('message'=>$message));
        return $response;
    }

    /**
     * Put external products in trash state
     * @Route("/trash_product", name="trash_product")
     */
    public function trashProductAction(Request $request){
//        get external product info from AJAX
//        linking_products_pricelists.js
        $externalProductID = $request->request->get('aexternal_product');

        //get Entity Manager
        $em = $this->getDoctrine()->getEntityManager();

        //notice findBy return array of Objects
        $externalProducts = $em->getRepository("AppBundle\Entity\Pricelists")->findBy(array('externalproductid' => $externalProductID));

//        status of external(pricelists) products became trash
//        we don't have to link external product
//        reson: we don't have that product in our internal product list
        $externalProducts[0]->setStatus('trash');
        $em->flush();

        $message = "Product was put in trash.";
        $response = new JsonResponse();
        $response->setData(array('message'=>$message));
        return $response;
    }

    /**
     * Get all pricelist's files
     * @Route("/all_files", name="all_files")
     */
    public function allFilesAction(Request $request){
//        get external product info from AJAX
//        linking_products_pricelists.js
        $externalProductID = $request->request->get('aexternal_product');

        //get Entity Manager
        $em = $this->getDoctrine()->getEntityManager();

        //notice findBy return array of Objects
        $externalProducts = $em->getRepository("AppBundle\Entity\Pricelists")->findBy(array('externalproductid' => $externalProductID));

//        status of external(pricelists) products became trash
//        we don't have to link external product
//        reson: we don't have that product in our internal product list
        $externalProducts[0]->setStatus('trash');
        $em->flush();

        $message = "Product was put in trash.";
        $response = new JsonResponse();
        $response->setData(array('message'=>$message));
        return $response;
    }



    public function getAllSuppliers()
    {
        $em = $this->getDoctrine()->getEntityManager();
        $listOfSupplier = $em->getRepository('AppBundle\Entity\Suppliers')->findAll();
        return $listOfSupplier;
    }

}
