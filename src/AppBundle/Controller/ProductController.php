<?php

namespace AppBundle\Controller;

use AppBundle\Form\ProductType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
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
    public function indexAction(Request $request)
    {
        $query =  $this
           ->getDoctrine()
           ->getRepository('AppBundle:Product')
           //->findBy(['active' => true])
           ->findAllProducts()
       ;

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            9 /*limit per page*/
        );

       //dump($products);

        return ['pagination' => $pagination];
    }


    /**
     * @Route("/products/{id}", name="product_page")
     * @Template()
     * @param $id
     * @return array
     */
    public function showAction($id)
    {
        $product = $this->getBook($id);

        //Выводим имя категории продукта
        //$categoryName = $product->getCategory()->getName();
        
        //dump($product);

        //получаем все продукты определенной категории
        // $category = $product->getCategory();
        // foreach ($category->getProducts() as $p) {
        //     echo $p->getTitle();
        // }
        // dump($category->getProducts());


        return ['product' => $product];
    }


    /**
     * @Route("/products/{id}/edit", name="product_edit")
     * @Template()
     * @param $id
     * @param $request
     * @return array
     */
    public function editAction($id, Request $request)
    {
        $product = $this->getBook($id);

        $form = $this->createForm(ProductType::class, $product);
        $form->add('submit', SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            //сообщение об отправке(ошибке)
            $this->addFlash('success', 'Book saved!');
            //$this->addFlash('fail', 'Not saved!');

            //редирект
            return $this->redirectToRoute('product_list');
        }
        return[
            'product' => $product,
            'form' => $form->createView()
        ];

    }


    /**
     * @Route("/products/{id}/remove", name="product_remove")
     * @param $id
     * @return RedirectResponse
     */
    public function deleteAction($id)
    {
        $product = $this->getBook($id);

        $em = $this->getDoctrine()->getManager();
        $em->remove($product);
        $em->flush();

        //сообщение об отправке(ошибке)
        $this->addFlash('success', 'Book removed!');

        //редирект
        return $this->redirectToRoute('product_list');
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

    private function getBook($id)
    {
        $product =  $this
            ->getDoctrine()
            ->getRepository('AppBundle:Product')
            ->findActiveProduct($id)
        ;

        if (!$product) {
            throw $this->createNotFoundException();
        }

        return $product;
    }

    /**
     * @Route("/products/{id}/export", name="products_export")
     * @param Product $product
     * @return RedirectResponse
     */
    public function jsonExportAction(Product $product)
    {
        $service = $this->get('json_service');
        $res = $service->export($product);

        if (!$res){
            //сообщение об отправке(ошибке)
            $this->addFlash('error', 'Book not exported!');
        }else{
            //сообщение об отправке
            $this->addFlash('success', 'Book exported!');
        }

        //редирект
        return $this->redirectToRoute('product_list');
    }
}
