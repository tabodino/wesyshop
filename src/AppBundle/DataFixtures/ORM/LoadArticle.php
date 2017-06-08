<?php

/**
 * Created by PhpStorm.
 * User: jm
 * Date: 08/06/17
 * Time: 11:14
 */

namespace ShopBundle\DataFixtures\ORM;

use ShopBundle\Entity\Article;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;


class LoadArticle implements FixtureInterface
{

    const NB_MAX_ARTICLES = 12;

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        for ($i = 1; $i <= self::NB_MAX_ARTICLES; $i++)
        {
            $article = new Article();

            $article->setTitle("article_".$i);
            $article->setDescription("Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis sed sodales nibh, in venenatis sapien. Fusce elementum sapien in interdum porta. Cras porta ullamcorper elementum. Fusce nec massa nec enim dapibus egestas. Vivamus ullamcorper placerat lectus tempus faucibus. Maecenas id magna eget eros pulvinar dapibus. Duis arcu arcu, gravida ac sem at, congue ornare tortor. Vestibulum sit amet diam congue, gravida nulla sed, sagittis nunc. Donec interdum magna ante. Praesent in ante maximus massa lobortis volutpat. Nam quis suscipit tortor, eu aliquet nisi. Fusce ut rutrum sem. Mauris a commodo eros, et vehicula elit. Suspendisse pretium odio ligula, et tristique tellus ultrices sed. ");
            $article->setCategory("category_".rand(1,5));
            $article->setQuantity(rand(1, 10));
            $article->setThumb("p0".rand(1, 3).".png");
            $article->setPrice(rand(199, 299));

            $manager->persist($article);
        }

        $manager->flush();
    }
}