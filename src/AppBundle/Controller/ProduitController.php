<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Produit;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest;
use AppBundle\Form\ProduitType;


class ProduitController extends Controller
{
    /**
     * @Rest\View()
     * @Rest\Get("/produits")
     */
    public function indexAction(Request $request)
    {
        /*$em = $this->getDoctrine()->getManager();

        $produits = $em->getRepository('AppBundle:Produit')->findAll();

        return $this->render('produit/index.html.twig', array(
            'produits' => $produits,
        ));*/
        
        $produits = $this->get('doctrine.orm.entity_manager')
        ->getRepository('AppBundle:Produit')
        ->findAll();
        return $produits;
            /* @var $produits Produit[] */

           /* $formatted = [];
            foreach ($produits as $produit) {
                $formatted[] = [
                'id' => $produit->getId(),
                'designation' => $produit->getDesignation(),
                'code' => $produit->getCode(),
                'quantite' => $produit->getQuantite(),
                'prixachat' => $produit->getPrixachat(),
                'prixvente' => $produit->getPrixvente(),
                ];
            }

         // Création d'une vue FOSRestBundle
        $view = View::create($produits);
        $view->setFormat('json');

        return $view;
           // return new JsonResponse($formatted);*/


    }

    
    /**
     * @Rest\View(statusCode=Response::HTTP_CREATED)
     * @Rest\Post("/produits")
     */
    public function newAction(Request $request)
    {
      /*  $produit = new Produit();
        $form = $this->createForm('AppBundle\Form\ProduitType', $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($produit);
            $em->flush();

            return $this->redirectToRoute('produit_show', array('id' => $produit->getId()));
        }

        return $this->render('produit/new.html.twig', array(
            'produit' => $produit,
            'form' => $form->createView(),
        ));*/

        
        /*return [
            'payload' => [
                $request->get('designation'),
                $request->get('code'),
                $request->get('quantite'),
                $request->get('prixachat'),
                $request->get('prixvente')
             ]
        ];
        $produit = new Produit();
        $produit->set('designation')
        ->set('code')
        ->set('quantite')
        ->set('prixachat')
        ->set('prixvente');

        $em = $this->get('doctrine.orm.entity_manager');
        $em->persist($produit);
        $em->flush();

        return $produit;*/
        

        //$produit = new Produit();
        //$form = $this->createForm(ProduitType::class, $produit);
       
        $produit = new Produit();
        $form = $this->createForm('AppBundle\Form\ProduitType', $produit);

        $form->submit($request->request->all()); // Validation des données

        if ($form->isValid()) {
            $em = $this->get('doctrine.orm.entity_manager');
            $em->persist($produit);
            $em->flush();
            return $produit;
        } else {
            return $form;
        }


    }

    
    
   /**
     * @Rest\View()
     * @Rest\Get("/produits/{id}")
     */

    public function showAction(Request $request)
    {
        /*$deleteForm = $this->createDeleteForm($produit);

        return $this->render('produit/show.html.twig', array(
            'produit' => $produit,
            'delete_form' => $deleteForm->createView(),
        ));*/

        $produit = $this->get('doctrine.orm.entity_manager')
        ->getRepository('AppBundle:Produit')
        ->find($request->get('id'));
            
        if (empty($produit)) {
            return new JsonResponse(['message' => 'le produit n existe pas'], Response::HTTP_NOT_FOUND);
        } 

           /* @var $produit produit */
              return $produit;
           /* $formatted = [
                'id' => $produit->getId(),
                'designation' => $produit->getDesignation(),
                'code' => $produit->getCode(),
                'quantite' => $produit->getQuantite(),
                'prixachat' => $produit->getPrixachat(),
                'prixvente' => $produit->getPrixvente(),
            ];

            return new JsonResponse($formatted);*/
    }

   
     /**
     * @Rest\View()
     * @Rest\Put("/produits/{id}")
     */
    public function editAction(Request $request)
    {
        /*$deleteForm = $this->createDeleteForm($produit);
        $editForm = $this->createForm('AppBundle\Form\ProduitType', $produit);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('produit_edit', array('id' => $produit->getId()));
        }

        return $this->render('produit/edit.html.twig', array(
            'produit' => $produit,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));*/

        $produit = $this->get('doctrine.orm.entity_manager')
        ->getRepository('AppBundle:Produit')
        ->find($request->get('id')); // L'identifiant en tant que paramètre n'est plus nécessaire
        /* @var $produit Produit */

        if (empty($produit)) {
            return new JsonResponse(['message' => 'Produit not found'], Response::HTTP_NOT_FOUND);
        }

        //$form = $this->createForm(ProduitType::class, $produit);
        $form = $this->createForm('AppBundle\Form\ProduitType', $produit);  
        $form->submit($request->request->all());

        if ($form->isValid()) {
            $em = $this->get('doctrine.orm.entity_manager');
            // l'entité vient de la base, donc le merge n'est pas nécessaire.
            // il est utilisé juste par soucis de clarté
            $em->merge($produit);
            $em->flush();
            return $produit;
        } else {
            return $form;
        }

            }

        
            /**
             * @Rest\View(statusCode=Response::HTTP_NO_CONTENT)
             * @Rest\Delete("/produits/{id}")
             */
            public function deleteAction(Request $request)
            {
                /*$form = $this->createDeleteForm($produit);
                $form->handleRequest($request);

                if ($form->isSubmitted() && $form->isValid()) {
                    $em = $this->getDoctrine()->getManager();
                    $em->remove($produit);
                    $em->flush();
                }

                return $this->redirectToRoute('produit_index');*/


                $em = $this->get('doctrine.orm.entity_manager');
                $produit = $em->getRepository('AppBundle:Produit')
                            ->find($request->get('id'));
                /* @var $produit Produit */

                if ($produit) {
                    $em->remove($produit);
                    $em->flush();
                }
            }

            /**
             * Creates a form to delete a produit entity.
             *
             * @param Produit $produit The produit entity
             *
             * @return \Symfony\Component\Form\Form The form
             */
            private function createDeleteForm(Produit $produit)
            {
                return $this->createFormBuilder()
                    ->setAction($this->generateUrl('produit_delete', array('id' => $produit->getId())))
                    ->setMethod('DELETE')
                    ->getForm()
                ;
    }
}
