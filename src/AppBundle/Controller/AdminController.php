<?php
/**
 * Created by PhpStorm.
 * User: bor8246
 * Date: 10.2.2017
 * Time: 12:19
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Pricelists;
use AppBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Forms\FormUploadPricelist;
use AppBundle\Entity\Priselistfiles;

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

        $formUpload = new FormUploadPricelist();
        $form = $this->createForm(FormUploadPricelist::class, $formUpload);

//        $pricelistfile->setId(1);
//        $pricelistfile->setExcelName("excel name");
//        $pricelistfile->setSupplierName("Kimtek");
       $form = $this->createForm(FormUploadPricelist::class, $formUpload);

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
