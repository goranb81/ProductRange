<?php
/**
 * Created by PhpStorm.
 * User: bor8246
 * Date: 10.2.2017
 * Time: 12:19
 */

namespace AppBundle\Controller;

use AppBundle\AppBundle;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Products;
use AppBundle\Entity\Pricelistfiles;
use Vich\UploaderBundle\Form\Type\VichFileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use AppBundle\Entity\Suppliers;
use Symfony\Component\HttpFoundation\JsonResponse;
use AppBundle\Entity\Internalexternal;
use AppBundle\Entity\Groups;
use AppBundle\Entity\Manufacturers;

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
    public function importpricelistAction(Request $request)
    {
        $listOfSupplier = $this->getAllSuppliers();

        // get list of filenames from excelpricelists directory
        $arrayOfExcelFilenames = $this->getFilesFromPricelistDirectoryByEntity($listOfSupplier[0]->getSuppliername());

        return $this->render('admin/import_pricelist.html.twig', array('suppliers' => $listOfSupplier, 'arrayFilenames' => $arrayOfExcelFilenames));
    }


//    get all filenames from pricelist's directory
    public function getFilesFromPricelistDirectoryByEntity($name)
    {
        $em = $this->getDoctrine()->getEntityManager();

//        array of pricelists entity
//        this array have only firts supplier entities
        $pricelists = $em->getRepository('AppBundle\Entity\Pricelistfiles')->findBy(array('supplierName' => $name));

        $arrayOfExcelFilenames = array();
        foreach ($pricelists as $pricelist) {
            $arrayOfExcelFilenames[] = $pricelist->getExcelName();
        }
        return $arrayOfExcelFilenames;
    }

//    !!!!! We don't use this function. Instead this we use getFilesFromPricelistDirectoryByEntity()
//    get all filenames from pricelists directory
    public function getFilesFromPricelistDirectory()
    {
        $dir = $this->getParameter('excelpricelist_directory');
        $arrayOfExcelFilenames = array();
        if ($handle = opendir($dir)) {

            while (false !== ($entry = readdir($handle))) {
                if ($entry != "." && $entry != ".." && is_file($dir . '/' . $entry)) {
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
    public function getSupplierPricelistFilenamesAction(Request $request)
    {
        //get name from Ajax
        $name = $request->request->get('name');

        // get list of filenames from excelpricelists directory
        $arrayOfExcelFilenames = $this->getFilesFromPricelistDirectoryByEntity($name);

        $response = new JsonResponse();
        $response->setData(array('pricelist_filenames' => $arrayOfExcelFilenames));
        return $response;
    }

//    /**
//     * Show Upload pricelist file page. This action is not in use. This action is refer to Symfony form
//     *
//     * @Route("/upload_pricelist_page", name="upload_pricelist_page")
//     */
//    public function uploadpricelistAction(Request $request)
//    {
////        get list of objects that represent all suppliers
//        $listOfSupplier = $this->getAllSuppliers();
//
////        array of supplier name. we use that array like choices
//        $suppliers = array();
//        foreach ($listOfSupplier as $supplier) {
//            $suppliers[$supplier->getSuppliername()] = $supplier->getSuppliername();
//        }
//
//        $pricelistfile = new Pricelistfiles();
//
//        //create form
//        $form = $this->createFormBuilder($pricelistfile)->add('excelFile', VichFileType::class, [
//            'required' => false,
//            'allow_delete' => true, // optional, default is true
//            'download_link' => true, // optional, default is true
////            'download_uri' => '...', // optional, if not provided - will automatically resolved using storage
//        ], array('label' => 'PPP'))
//            ->add('supplierName', ChoiceType::class, [
//                'choices' => $suppliers
//            ], array('label' => 'Supplier name'))
//            ->add('upload', SubmitType::class, array('label' => 'Upload pricelist'))->getForm();
//
//        $form->handleRequest($request);
//
//        if ($form->isSubmitted()) {
//            if ($form->isValid()) {
//                $em = $this->getDoctrine()->getManager();
//
//                //we set excel size to constant value becouse without that value
//                //app throw error excel_size value don't be null
//                $pricelistfile->setExcelSize('12');
//
//                $em->persist($pricelistfile);
//                $em->flush();
//
//                return $this->render('admin/upload_pricelist_task_success.html.twig');
//
//            } else {
//                //get validator
//                $validator = $this->get('validator');
//
////                validate Pricelistfiles Entity class
////                we do that becouse we use annotation
////                in Entity to validate form fields
//                $errors = $validator->validate($pricelistfile);
//
//                return $this->render('admin/upload_pricelist_task_error.html.twig', array('error' => $errors));
//            }
//        }
//        return $this->render('admin/upload_pricelist.html.twig', array('form' => $form->createView()));
//    }

    /**
     * Show Upload pricelist file page. This action is in use. This action is refer to ordinary form (not Symfony)
     *
     * @Route("/upload_pricelist_page", name="upload_pricelist_page")
     */
    public function uploadpricelistAction(Request $request){
            $firstSupplier = new Suppliers();
            $firstSupplier->setSuppliername('');

            $arraySuppliers = $this->getAllSuppliers();

//            add firstElement on the top of array
            array_unshift($arraySuppliers, $firstSupplier);

        return $this->render('admin/upload_pricelist.html.twig', array('suppliers' => $arraySuppliers));
    }

    /**
     * This method colled by Form(upload_pricelist.html.twig)
     *
     * @Route("/upload_pricelist", name="upload_pricelist")
     */
    public function uploadpricelistFormAction(Request $request){
//        FORM VARIABLES
        //get Symfony\Component\HttpFoundation\File\UploadedFile from $_FILES($request->files)
//        parameters:
//        test
//        originalName
//        mimeType
//        size
//        error
//        $uploadedFile = new UploadedFile();
        $uploadedFile = $request->files->get('excel_file');

        $fileName = $uploadedFile->getClientOriginalExtension();
        $fileSize = $uploadedFile->getClientSize();
        $fileType = $uploadedFile->getClientMimeType();
        $fileError = $uploadedFile->getError();

//        get SupplierID
        $supplierID = $request->request->get('supplier');
        $em = $this->getDoctrine()->getEntityManager();
        $supplierName = $em->getRepository("AppBundle\Entity\Suppliers")->find($supplierID)->getSuppliername();

        $pricelistfile = new Pricelistfiles();
        $pricelistfile->setSupplierid($supplierID);
        $pricelistfile->setSupplierName($supplierName);
        $pricelistfile->setExcelName($fileName);
        $pricelistfile->setExcelFile($uploadedFile);
        $pricelistfile->setExcelSize($fileSize);
        $pricelistfile->setUpdatedAt($this->getCurrentDateTime());

//        when we persist $pricelistfile into DB
//        automatically we save file in directory web/uploads/excelpricelists
//        notice: we must set Uploadable file whom we retrive from Request!!!
        $em->persist($pricelistfile);
        $em->flush();

        return $this->render('admin/upload_pricelist_task_success.html.twig');
    }

    /**
     * This controller action shows Delete pricelist file page
     *
     * @Route("/delete_pricelist_page", name="delete_pricelist_page")
     */
    public function deletefilesAction(Request $request)
    {

        $listOfSuppliers = $this->getAllSuppliers();

        // Get all Pricelists Entity's
        $em = $this->getDoctrine()->getEntityManager();
        $pricelistfiles = $em->getRepository('AppBundle\Entity\Pricelistfiles')->findBy(array('supplierName' => $listOfSuppliers[0]->getSuppliername()));
        return $this->render('admin/delete_pricelist.html.twig', array('suppliers' => $listOfSuppliers, 'files' => $pricelistfiles));
    }

    /**
     *This controller action delete pricelist files of particularly supplier or
     *delete all files from upload directory /web/uploads/excelpricelists
     *
     * @Route("/deletepricelist", name="deletepricelist")
     */
    public function deletepricelistfilesAction(Request $request)
    {
//        get supplier name
        $supplierID = $request->request->get('asupplierid');
        $em = $this->getDoctrine()->getEntityManager();
        $pricelistfiles = $em->getRepository("AppBundle\Entity\Pricelistfiles")->findBy(array('supplierid' => $supplierID));

//        when we delete from table productrange.pricelists
//        all pricelists rows automatically we delete files from
//        /web/uploads/excelpricelists
//        This is ability of vich bundle

//        delete all entity from array of suppliers
        foreach ($pricelistfiles as $pricelist) {
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
    public function deletepricelistfilesSelectedAction(Request $request)
    {
//        get supplier name
        $arrayIDs = $request->request->get('parrayIDs');

        $em = $this->getDoctrine()->getEntityManager();

        $priselistfilesArray = array();

        foreach ($arrayIDs as $id) {
            $pricelistfile = $em->getRepository("AppBundle\Entity\Pricelistfiles")->find($id);
            $priselistfilesArray[] = $pricelistfile;
        }


//        when we delete from table productrange.pricelists
//        all pricelists rows automatically we delete files from
//        /web/uploads/excelpricelists
//        This is ability of vich bundle

//        delete all entity from array of suppliers
        foreach ($priselistfilesArray as $pricelist) {
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
    public function linkProductsPageAction(Request $request)
    {
        // Linking products page
        $em = $this->getDoctrine()->getEntityManager();
        $products = $em->getRepository('AppBundle\Entity\Products')->findAll();
        $suppliers = $em->getRepository('AppBundle\Entity\Suppliers')->findAll();
        $externalProducts = $em->getRepository('AppBundle\Entity\Pricelists')->findBy(array('supplierid' => $suppliers[0]->getSupplierid(), 'status' => 'new_product'));
        return $this->render('admin/linking_products.html.twig', array('products' => $products, 'suppliers' => $suppliers, 'exproducts' => $externalProducts));
    }


    /**
     * @Route("/get_all_products", name="get_all_products")
     */
    public function getAllProductsAction(Request $request)
    {
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
        $response->setData(array('products' => $pricelistsProductsJson));
        return $response;

    }

    /**
     * @Route("/get_all_files", name="get_all_files")
     */
    public function getAllFilesAction(Request $request)
    {
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
        $response->setData(array('files' => $pricelistsProductsJson));
        return $response;

    }

    /**
     * Link external with internal product
     * @Route("/link_products", name="link_products")
     */
    public function linkProductsAction(Request $request)
    {
//        get external product info and internal product info from AJAX
//        linking_products_pricelists.js
        $internalProduct = $request->request->get('ainternal_product');
        $externalProduct = $request->request->get('aexternal_product');

        $internalProductID=$internalProduct['id'];
        $externalProductID=$externalProduct['id'];
        
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
        $response->setData(array('message' => $message));
        return $response;
    }

    /**
     * Put external products in trash state
     * @Route("/trash_product", name="trash_product")
     */
    public function trashProductAction(Request $request)
    {
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
        $response->setData(array('message' => $message));
        return $response;
    }

    /**
     * Get all pricelist's files
     * @Route("/all_files", name="all_files")
     */
    public function allFilesAction(Request $request)
    {
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
        $response->setData(array('message' => $message));
        return $response;
    }


    public function getAllSuppliers()
    {
        $em = $this->getDoctrine()->getEntityManager();
        $listOfSupplier = $em->getRepository('AppBundle\Entity\Suppliers')->findAll();
        return $listOfSupplier;
    }


    /**
     * Get all pricelist's files
     * @Route("/create_internal_products_page", name="create_internal_products_page")
     */
    public function createInternalProductsPageAction(Request $request)
    {

        //get Entity Manager
        $em = $this->getDoctrine()->getEntityManager();

//    array - all groups
        $groups = $em->getRepository("AppBundle\Entity\Groups")->findAll();

//        set first Groups object with id = 0 and name = ''
        $firstElement = new Groups();
        $firstElement->setName('');

//        add firstElement on the top of array
        array_unshift($groups, $firstElement);

//        array - all manufacturers
        $manufacturers = $em->getRepository("AppBundle\Entity\Manufacturers")->findAll();

//        set first Groups object with id = 0 and name = ''
        $firstElementManufacturer = new Manufacturers();
        $firstElementManufacturer->setManufacturername('');

//        add firstElement on the top of array
        array_unshift($manufacturers, $firstElementManufacturer);

//        array - all products
        $products = $em->getRepository("AppBundle\Entity\Products")->findAll();

        return $this->render('admin/create_internal_product.html.twig', array('groups'=>$groups, 'manufacturers'=>$manufacturers, 'products'=>$products));
    }

    /**
     * Create internal product action called by Ajax call
     * @Route("/create_internal_products", name="create_internal_products")
     */
    public function createInternalProductsAction(Request $request)
    {
//        get parametar from Ajax
        $groupid = $request->request->get('agroupid');
        $groupname = $request->request->get('agroupname');
        $manufacturerid = $request->request->get('amanufacturerid');
        $manufacturername = $request->request->get('amanufacturername');;
        $productname = $request->request->get('aproductname');
        $productprice = $request->request->get('aproductprice');

//        Is this product already exists?
        $em = $this->getDoctrine()->getEntityManager();
        $internalProduct = $em->getRepository('AppBundle\Entity\Products')->findBy(array('productname'=>$productname));

//        internal product id of new product
        $id = null;

//        internal product input date
        $date = null;

        $response = new JsonResponse();
        //        Is this product already exists?
        if($internalProduct == null){
//            insert product into DB
//            $queryBuilder = $em->createQueryBuilder();
//            $query = $queryBuilder
//                ->insert('products')
//                ->values(
//                    array(
//                        'groups' => '?',
//                        'groupname' => '?',
//                        'productName' => '?',
//                        'manufacturerId' => '?',
//                        'manufacturerName' => '?',
//                        'price' => '?'
//                    ))->setParameter(1, $groupid)
//                     ->setParameter(2, $groupname)
//                     ->setParameter(3, $productname)
//                     ->setParameter(4, $manufacturerid)
//                     ->setParameter(5, $manufacturername)
//                     ->setParameter(7, $productprice)
//                     ->getQuery();
//            $query->execute();
//            $em->flush();

            $newInternalProduct = $this->createProductEntity($groupid, $groupname, $manufacturerid, $manufacturername, $productname, $productprice);
            $em->persist($newInternalProduct);
            $em->flush();
            $id = $newInternalProduct->getInternalproductid();
//            from DateTime object create string in format 'Y-m-d'
            $format_date = $newInternalProduct->getInputdate()->format('Y-m-d');
            $message = 'New internal product successfully created.';
//            send message and internal product id (provide id is neccessery when we add new row into table)
            $response->setData(array('message'=>$message, 'id'=>$id, 'date'=> $format_date));
            return $response;
        }else{
//            return message that product already exist in DB
            $message = 'Internal product already exist in DB! Check in product table!';
            $response->setData(array('message'=>$message));
            return $response;
        }
    }

    private function createProductEntity($groupid, $groupname, $manufacturerid, $manufacturername, $productname, $productprice){
        $product = new Products();
        $product->setGroups($groupid);
        $product->setGroupname($groupname);
        $product->setManufacturerid($manufacturerid);
        $product->setManufacturername($manufacturername);
        $product->setInputdate($this->getCurrentDateTime());
        $product->setProductname($productname);
        $product->setPrice($productprice);

        return $product;
    }

    private function getCurrentDateTime(){
        //return current Unix timestamp in miliseconds(int)
        $timestamp = time();

        //convert timestamp from miliseconds(int) in timestamp format Y-m-d H:i:s (this is string)
        $mysqlTimestampFormat = date("Y-m-d H:i:s", $timestamp);

        return new \DateTime($mysqlTimestampFormat);
    }

}