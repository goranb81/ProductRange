<?php
/**
 * Created by PhpStorm.
 * User: bor8246
 * Date: 10.3.2017
 * Time: 10:07
 *
 * This service is worked with existing products in DB
 * In this case it works with AppBundle/Entity/Pricelist
 */

namespace Bee\InputExcelBundle\ServiceClasses;


use Doctrine\ORM\EntityManager;
use Doctrine\Bundle\DoctrineBundle\Registry;

class ProductDB
{
    /**
     * @var EntityManager
     **/
    protected $doctrine;
    protected $em;

    public function __construct(Registry $doctrine){
        $this->doctrine = $doctrine;
        $this->em = $this->doctrine->getManager();
    }

    // list of objects that represent existing products in pricelist DB table(with status active inactive and new_product exclude status trash )
    public function getListOFExistingProducts($supplierId){
        $status = "'trash'";
        $statement = "select p from AppBundle\Entity\Pricelists p where p.status <> " . $status . " and p.supplierid = " . $supplierId;
        $q = $this->em->createQuery($statement);
        $listOfExistingProducts = $q->getResult();

        //before send data use refresh method to be sure
        //that entities is fetched from DB not from cache
        $this->refreshAllEntityObjects($listOfExistingProducts);
        return $listOfExistingProducts;
    }

    //list of objects that represent existing products in pricelist DB table(with status active)
    public function getlistOFExistingProductsActive($supplierId){
        $listOfExistingProductsActive = $this->em->getRepository('AppBundle\Entity\Pricelists')->findBy(array('supplierid' => $supplierId, 'status'=>'active'));
        //before send data use refresh method to be sure
        //that entities is fetched from DB not from cache
        $this->refreshAllEntityObjects($listOfExistingProductsActive);

        return $listOfExistingProductsActive;
    }

    // list of objects that represent existing products in pricelist DB table(with status new_product)
    public function getlistOFExistingProductsNew($supplierId){
        $listOfExistingProductsNew = $this->em->getRepository('AppBundle\Entity\Pricelists')->findBy(array('supplierid' => $supplierId, 'status'=>'new_product'));
        //before send data use refresh method to be sure
        //that entities is fetched from DB not from cache
        $this->refreshAllEntityObjects($listOfExistingProductsNew);
        return $listOfExistingProductsNew;
    }

    public function refreshAllEntityObjects($ArrayOfObject){
        foreach ($ArrayOfObject as $object){
            $this->em->refresh($object);
        }
    }
}