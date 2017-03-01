<?php
/**
 * Created by PhpStorm.
 * User: bor8246
 * Date: 28.2.2017
 * Time: 21:48
 */

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

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
        $timestamp = time();

        //convert timestamp from miliseconds(int) in timestamp format Y-m-d H:i:s
        $mysqlTimestampFormat = date("Y-m-d H:i:s", $timestamp);
        //var_dump($mysqlTimestampFormat);

        //
        //get database connection object
        $em = $this->getDoctrine()->getManager();
        $conn = $em->getConnection();
        //update pricelist product in DB
        $conn->executeUpdate('UPDATE pricelists SET price=?, inputDate=? WHERE externalProductId=?', array(100,$mysqlTimestampFormat,1));

        return $this->render('default/index.html.twig');
    }
}