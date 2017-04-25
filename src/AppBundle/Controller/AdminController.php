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
        $em = $this->getDoctrine()->getEntityManager();
        $listOfSupplier = $em->getRepository('AppBundle\Entity\Suppliers')->findAll();

        // get list of filenames from excelpricelists directory
        $arrayOfExcelFilenames = $this->getFilesFromPricelistDirectory();

        return $this->render('admin/import_pricelist.html.twig', array('suppliers' => $listOfSupplier, 'arrayFilenames' => $arrayOfExcelFilenames));
    }



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
     * Show Upload pricelist file page. This action is in use. This action is refer to Symfony form
     *
     * @Route("/upload_pricelist_page", name="upload_pricelist_page")
     */
    public function uploadpricelistAction(Request $request){
//        get list of objects that represent all suppliers
        $em = $this->getDoctrine()->getEntityManager();
        $listOfSupplier = $em->getRepository('AppBundle\Entity\Suppliers')->findAll();

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
//                'choice_label' => function($category, $key, $index) {
//                    /** @var Suppliers $supplier */
//                    return $category->getSuppliername();
//                },
//                'choice_attr' => function($supplier, $key, $index) {
//                    /** @var Suppliers $supplier */
//                    return ['class' => $supplier->getSuppliername()];
//                }
            ], array('label' => 'Supplier name'))
            ->add('upload', SubmitType::class, array('label' => 'Upload pricelist'))->getForm();

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();

            //we set excel size to constant value becouse without that value
            //app throw error excel_size value don't be null
            $pricelistfile->setExcelSize('12');

            $em->persist($pricelistfile);
            $em->flush();
           return $this->redirectToRoute('task_success_page');
        }

        return $this->render('admin/upload_pricelist.html.twig', array('form' => $form->createView()));
    }



    /**
     * Show upload pricelist task success page
     *
     * @Route("/task_success_page", name="task_success_page")
     */
    public function uploadpricelistsuccessAction(Request $request){
        return $this->render('admin/upload_pricelist_task_success.html.twig');
        }


    /**
     * Show Upload pricelist file page. This action is not in use. This action is refer to ordinary form (not Symfony)
     *
     * @Route("/upload_pricelist_page1", name="upload_pricelist_page1")
     */
    public function uploadpricelist1Action(Request $request){
//        $uploadedFile = new UploadedFile();
        $em = $this->getDoctrine()->getEntityManager();
        $listOfSupplier = $em->getRepository('AppBundle\Entity\Suppliers')->findAll();

//        print_r($_POST);
//        print_r($_FILES);
//        var_dump($_FILES);
        var_dump($request->files);
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


        return $this->render('admin/upload_pricelist1.html.twig');
//        , array('suppliers' => $listOfSupplier, 'name' => $name, 'supplier' => $supplier)

    }

}
