<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use AppBundle\Entity\Product;

class ProductController extends Controller
{
    /**
     * @Route("/products", name="product_list")
     * @Template()
     */
    public function indexAction()
    {
       $products =  $this
           ->getDoctrine()
           ->getRepository('AppBundle:Product')
           //->findBy(['active' => true])
           ->findAllProducts()
       ;

       //dump($products);

        return ['products' => $products];
    }


    /**
     * @Route("/products/{id}", name="product_page")
     * @Template()
     * @param $id
     * @return array
     */
    public function showAction($id)
    {
        $product =  $this
           ->getDoctrine()
           ->getRepository('AppBundle:Product')
           ->findActiveProduct($id)
        ;

        //Выводим имя категории продукта
        //$categoryName = $product->getCategory()->getName();
        
        //dump($product);

        //получаем все продукты определенной категории
        // $category = $product->getCategory();
        // foreach ($category->getProducts() as $p) {
        //     echo $p->getTitle();
        // }
        // dump($category->getProducts());

        if (!$product) {
            throw $this->createNotFoundException();
            
        }
        return ['product' => $product];
    }

    /**
     * @Route("/api/products", name="products_api")
     * @Template()
     */
    public function apiIndex()
    {
        $products = $this
            ->getDoctrine()
            ->getRepository('AppBundle:Product')
            ->findAllProducts()
        ;
        return new JsonResponse($products);
    }
}
