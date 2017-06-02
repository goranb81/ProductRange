<?php
/**
 * Created by PhpStorm.
 * User: bor8246
 * Date: 10.2.2017
 * Time: 12:20
 */
namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/user")
 */
class UserController extends Controller
{
    /**
     * @Route("/", name="user_index")
     */
    public function indexAction(Request $request)
    {

        $em = $this->getDoctrine()->getEntityManager();
        $groupsEntity = $em->getRepository('AppBundle\Entity\Groups')->findAll();

        /*generate tree HTML from array of node's array of entity*/
        $treeHTML = $this->generatePageTree($groupsEntity);

        return $this->render('user/user_base.html.twig', array('tree'=>$treeHTML));
    }

    private function generatePageTree($datas, $parent = 0, $depth=0){
        if($depth > 1000) return ''; // Make sure not to have an endless recursion
        $tree = '<ul>';
        for($i=0, $ni=count($datas); $i < $ni; $i++){
            if($datas[$i]->getParentid() == $parent){
                $tree .= '<li ' . 'id="' . $datas[$i]->getId() . '"' . '>';
                $tree .= $datas[$i]->getName();
                $tree .= $this->generatePageTree($datas, $datas[$i]->getId(), $depth+1);
                $tree .= '</li>';
            }
        }
        $tree .= '</ul>';
        return $tree;
    }

    /**
     * @Route("/get_all_descendants", name="get_all_descendants")
     */
    public function getAllDescandantsAction(Request $request){
        $em = $this->getDoctrine()->getEntityManager();

        /*retrive id of node to find out all descendants which don't have children*/
        /*NOTICE: $parentid is ARRAY!!!!!*/
        $parent = $request->request->get('nodeid');
        $parent_id = (int) $parent[0];

        /*get entity Groups by $parentid*/
        $parentEntity = $em->getRepository('AppBundle\Entity\Groups')->find($parent_id);

        $groupsEntity = $em->getRepository('AppBundle\Entity\Groups')->findAll();

            /*this function returns all descandants + $parentEntity (node which represent start point of search)*/
            $arrayOfDescandentNodes = $this->listOfDescendants($groupsEntity, $parentEntity, $depth=0);

            /*delete nodes that have children*/
            $arrayOfDescandentNodes = $this->delete_node_have_children($arrayOfDescandentNodes);

            /*order $arrayOfDescandentNodes ascending by ID*/
            usort($arrayOfDescandentNodes, array($this,"compare"));

          /*  get internal products for selected node
            NOTICE: there are products which group ID = {array of IDs of nodes that don't have children}*/
          $internalProductsOfSelectedNode = $this->getinternalProductsOfSelectedNode($arrayOfDescandentNodes);

          $message = '';
          if(count($internalProductsOfSelectedNode)>0) $message = "Fill internal products table process is finished.";
          else $message = "Fill internal products table process is finished. There is no internal products belongs to selected node.";

            //serialize files array of object
            $internalProductsOfSelectedNodeJson = $this->get('serializer')->serialize($internalProductsOfSelectedNode, 'json');

            $response = new JsonResponse();
            $response->setData(array('internalProductsOfSelectedNode'=>$internalProductsOfSelectedNodeJson, 'message'=>$message));
            return $response;
     }

    private function getinternalProductsOfSelectedNode($arrayOfDescandentNodes){
        $nodeIDs  = array();
        foreach ($arrayOfDescandentNodes as $nodeEntity){
            $nodeIDs[] = $nodeEntity->getId();
        }

        $em = $this->getDoctrine()->getEntityManager();
        $internalProductsOfSelectedNode = $em->getRepository('AppBundle\Entity\Products')->findBy(array('groups'=>$nodeIDs));
        return $internalProductsOfSelectedNode;
    }

    private function compare($a,$b){
        if ($a == $b) {
            return 0;
        }
        return ($a < $b) ? -1 : 1;
    }


     /*delete element from array*/
     private function delete_element_from_array($array, $element){
         if(($key = array_search($element, $array)) !== false) {
             unset($array[$key]);
         }
         return $array;
     }

    /*function delete nodes that don't have children*/
    private function delete_node_have_children($arrayOfDescandentNodes){
        $em = $this->getDoctrine()->getEntityManager();
        $groups = $em->getRepository('AppBundle\Entity\Groups')->findAll();

        foreach ($arrayOfDescandentNodes as $element){
            if($this->isParent($element, $groups)){
                $arrayOfDescandentNodes = $this->delete_element_from_array($arrayOfDescandentNodes, $element);
            }
        }

        return $arrayOfDescandentNodes;
    }

    /*return true if node is parent*/
    private function isParent($element, $groups){
        foreach ($groups as $group){
            if($group->getParentid() == $element->getId()){
                return true;
            }
        }
        return false;
    }



  /*  algorithm is like algjoritm of function generatePageTree
    instead $parent = 0 we send id of node which we select
    that means that we start to search in that point(selected node)
    and find out all descendants ids of that node

    this function returns all descandants + $parent (ID which represent start point of search)
  */
    private function listOfDescendants($datas, $parentEntity, $depth=0){
        if($depth > 1000) return ''; // Make sure not to have an endless recursion
        $descendants = array();
        for($i=0, $ni=count($datas); $i < $ni; $i++){
            if($datas[$i]->getParentid() == $parentEntity->getId()){
                $parray = $this->listOfDescendants($datas, $datas[$i], $depth+1);
                foreach ($parray as $element){
                    array_push($descendants, $element);
                }
            }
        }

        $descendants[] = $parentEntity;
        return $descendants;
    }

   /* private function mergeArrays($array1, $array2){
        foreach ($array2 as $element){
            $array1[] = $element;
        }
    }*/

    /**
     * @Route("/get_all_suppliers_external_products", name="get_all_suppliers_external_products")
     */
    public function getAllSuppliersExternalProductsAction(Request $request){
        $em = $this->getDoctrine()->getEntityManager();

        /*retrive id of internal product to find out all suppliers external products linked to internal product*/
        /*NOTICE: $pid is ARRAY!!!!!*/
        $pid = $request->request->get('productid');
        $id = (int) $pid[0];

        /*get entity Products by $id*/
        $product = $em->getRepository('AppBundle\Entity\Products')->find($id);

        /*get entity Internalexternal by $product*/
        $internalExternal = $em->getRepository('AppBundle\Entity\InternalExternal')->findBy(array("internalproductid"=>$product));

        /*this function returns all supplier(external) products from $internalExternal*/
        $arrayOfSupplierProducts = $this->getSupplierProducts($internalExternal);

        $message = '';
        if(count($arrayOfSupplierProducts)>0) $message = "Fill suppliers external products table process is finished.";
        else $message = "Fill suppliers external products table process is finished.. There is no suppliers external products linked to selected internal product.";

        //serialize files array of object
        $arrayOfSupplierProductsJson = $this->get('serializer')->serialize($arrayOfSupplierProducts, 'json');

        $response = new JsonResponse();
        $response->setData(array('externalProductsOfSelectedInternalProduct'=>$arrayOfSupplierProductsJson, 'message'=>$message));
        return $response;
    }

    private function getSupplierProducts($internalExternal){
        $arrayOfSupplierProducts = array();
        foreach ($internalExternal as $product){
            $arrayOfSupplierProducts[] = $product->getExternalproductid();
        }
        return $arrayOfSupplierProducts;
    }
}
