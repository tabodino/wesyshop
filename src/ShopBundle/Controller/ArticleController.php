<?php
/**
 * Created by PhpStorm.
 * User: jm
 * Date: 08/06/17
 * Time: 20:32
 */

namespace ShopBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ArticleController extends Controller
{
    /**
     * @Route("/", name="list_articles")
     * Liste de nos articles
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $articles = $em->getRepository('ShopBundle:Article')->findAll();

        return $this->render('ShopBundle:Shop:index.html.twig', array('articles' => $articles));
    }

    /**
     * @Route("/article/{id}", name="detail_articles")
     * Détail d'un article
     */
    public function viewArticleAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $article = $em->getRepository('ShopBundle:Article')->find($id);

        if (!$article) {
            throw $this->createNotFoundException(
                'Article non trouvé'
            );
        }

        return $this->render('ShopBundle:Shop:article.html.twig', array('article' => $article));
    }


    /**
     * @Route("/cart", name="cart_articles")
     * Vue de notre panier
     */
    public function viewCartAction(Request $request)
    {
        // Nous récupérons la session
        $session = $request->getSession();
        // Si la session de notre panier n'existe pas nous la créons
        if (!$session->has('cart')) $session->set('cart', array());

        $em = $this->getDoctrine()->getManager();

        $articles = $em->getRepository('ShopBundle:Article')->findArray(array_keys($session->get('cart')));

        return $this->render('ShopBundle:Shop:cart.html.twig', array(
            'articles' => $articles,
            'cart' => $session->get('cart'),
        ));
    }



    /**
     * @Route("/add/{id}", name="add_articles")
     * Methode d'ajout au panier
     */
    public function addCartAction($id, Request $request)
    {
        // Récupère la session
        $session = $request->getSession();
        // Vérifie si la session panier existe déjà
        if (!$session->has('cart')) $session->set('cart', array());
        //
        $cart = $session->get('cart');

        $cart[$id] = 1;
        // Vérfie si l'id du produit existe déjà dans notre panier
        if (array_key_exists($id, $cart)) {
            if ($request->query->get('quantity') != null)
                // Affectation nouvelle quantité
                $cart[$id] = $request->query->get('quantity');
        }else {
            if ($request->query->get('quantity') != null)
                // Ajout nouvelle quantité
                $cart[$id] = $request->query->get('quantity');
            else
                // Quantité par défaut
                $cart[$id] = 1;
        }

        $session->set('cart', $cart);

        return $this->redirectToRoute('cart_articles');
    }

    /**
     * @Route("/remove/{id}", name="remove_articles")
     * Suppression d'un article du panier
     */
    public function removeFromCartAction($id, Request $request)
    {
        // récupère la session
        $session = $request->getSession();
        $cart = $session->get('cart');
        // Vérifier si l'id produit est bien dans le panier
        if (array_key_exists($id, $cart))
        {
            // Supprime le produit de panier
            unset($cart[$id]);
            // Mise à jour de la session
            $session->set('cart', $cart);
        }

        return $this->redirect($this->generateUrl('cart_articles'));
    }


}