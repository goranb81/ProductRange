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

        /*convert array of Groups entity into array of node's arrays*/
        $datas = $this->getArrayOFArrayNodes($groupsEntity);

        /*generate tree HTML from array of node's arrays into*/
        $treeHTML = $this->generatePageTree($datas);

        return $this->render('user/user_base.html.twig', array('tree'=>$treeHTML));
    }

    /*convert array of Groups entity into following format
    $datas = array(
    array('id' => 1, 'parent' => 0, 'name' => 'Page 1'),
    array('id' => 2, 'parent' => 1, 'name' => 'Page 1.1'),
    array('id' => 3, 'parent' => 2, 'name' => 'Page 1.1.1'),
    array('id' => 4, 'parent' => 3, 'name' => 'Page 1.1.1.1'),
    array('id' => 5, 'parent' => 3, 'name' => 'Page 1.1.1.2'),
    array('id' => 6, 'parent' => 1, 'name' => 'Page 1.2'));*/
    private function getArrayOFArrayNodes($groupsEntity){
        $datas = array();
        foreach ($groupsEntity as $entity){
            $datas[] = $this->convertEntityToNodeArray($entity);
        }
        return $datas;
    }

    private function convertEntityToNodeArray($entity){
        $nodeArray = array();
        $nodeArray['id'] = $entity->getId();
        $nodeArray['parent'] = $entity->getParentid();
        $nodeArray['name'] = $entity->getName();
        return $nodeArray;
    }

    private function generatePageTree($datas, $parent = 0, $depth=0){
        if($depth > 1000) return ''; // Make sure not to have an endless recursion
        $tree = '<ul>';
        for($i=0, $ni=count($datas); $i < $ni; $i++){
            if($datas[$i]['parent'] == $parent){
                $tree .= '<li ' . 'id="' . $datas[$i]['id'] . '"' . '>';
                $tree .= $datas[$i]['name'];
                $tree .= $this->generatePageTree($datas, $datas[$i]['id'], $depth+1);
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
            /*this function returns all descandants + $parent (ID which represent start point of search)
            becouse of that we delete from array value of ID that represent start point of search*/
            $arrayOfDescandentNodeIDs = $this->listOfDescendants($datas, $parent, $depth=0);

                /*delete $parent from $arrayOfDescandentNodeIDs*/
            if(($key = array_search($arrayOfDescandentNodeIDs, $parent)) !== false) {
                unset($arrayOfDescandentNodeIDs[$key]);
            }

            $response = new JsonResponse();
            $response->setData(array('descandentsIDs'=>$arrayOfDescandentNodeIDs));
            return $response;
     }


  /*  algorithm is like algjoritm of function generatePageTree
    instead $parent = 0 we send id of node which we select
    that means that we start to search in that point(selected node)
    and find out all descendants ids of that node

    this function returns all descandants + $parent (ID which represent start point of search)
  */
    private function listOfDescendants($datas, $parent, $depth=0){
        if($depth > 1000) return ''; // Make sure not to have an endless recursion
        $descendantsIds = array();
        for($i=0, $ni=count($datas); $i < $ni; $i++){
            if($datas[$i]['parent'] == $parent){
                $parray = $this->listOfDescendants($datas, $datas[$i]['id'], $depth+1);
                foreach ($parray as $element){
                    array_push($descendantsIds, $element);
                }
            }
        }

        $descendantsIds[] = $parent;
        return $descendantsIds;
    }

   /* private function mergeArrays($array1, $array2){
        foreach ($array2 as $element){
            $array1[] = $element;
        }
    }*/
}
