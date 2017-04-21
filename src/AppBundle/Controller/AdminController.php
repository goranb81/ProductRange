<?php
/**
 * Created by PhpStorm.
 * User: bor8246
 * Date: 10.2.2017
 * Time: 12:19
 */

namespace AppBundle\Controller;

use AppBundle\AppBundle;
use AppBundle\Entity\Pricelists;
use AppBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Forms\FormUploadPricelist;
use AppBundle\Entity\Priselistfiles;
use Vich\UploaderBundle\Form\Type\VichFileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

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
     * Show Upload pricelist file page
     *
     * @Route("/upload_pricelist_page", name="upload_pricelist_page")
     */
    public function uploadpricelistAction(Request $request){

//        $formUpload = new FormUploadPricelist();
//        $form = $this->createForm(FormUploadPricelist::class, $formUpload);

//        $pricelistfile->setId(1);
//        $pricelistfile->setExcelName("excel name");
//        $pricelistfile->setSupplierName("Kimtek");
//       $form = $this->createForm(FormUploadPricelist::class, $formUpload);
//        $em = $this->getDoctrine()->getEntityManager();
//        $pricelistfiles = $em->getRepository('AppBundle\Entity\Priselistfiles')->findBy(array('id'=>1));
//        $pricelistfiles = $em->getReference('AppBundle\Entity\Pricelistfiles', 1);
//        var_dump($pricelistfiles);
//        die();
//        $priselistfiles = new Priselistfiles();
//        $priselistfiles->setSupplierName('Kimtec');
//        $priselistfiles->setExcelName('excel.xls');

        $form = $this->createFormBuilder(new Priselistfiles())->add('excelFile', VichFileType::class, [
            'required' => false,
            'allow_delete' => true, // optional, default is true
            'download_link' => true, // optional, default is true
//            'download_uri' => '...', // optional, if not provided - will automatically resolved using storage
        ])
            ->add('supplierName', TextType::class)
            ->add('upload', SubmitType::class, array('label' => 'Upload pricelist'))->getForm();

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
//           return $this->redirectToRoute('task_success');
        }

        return $this->render('admin/upload_pricelist.html.twig', array('form' => $form->createView()));
    }

//    /**
//     * Show Upload pricelist file page
//     *
//     * @Route("/upload_pricelist_page", name="upload_pricelist_page")
//     */
//    public function uploadpricelistAction(Request $request){
//
//        $user = new User();
//        $user->setUsername('Goran');
//        $user->setPassword('12345');
//        $user->setEmail('goranb81@gmail.com');
//
//        $formUpload = new FormUploadPricelist();
//        $form = $this->createForm(FormUploadPricelist::class, $formUpload);
//
//
//        $form->handleRequest($request);
//
//        if($form->isSubmitted() && $form->isValid()){
////           return $this->redirectToRoute('task_success');
//        }
//
//        return $this->render('admin/upload_pricelist.html.twig', array('form' => $form->createView()));
//    }

}
