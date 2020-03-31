<?php

namespace App\DataFixtures;

use App\Entity\Article;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

/**
 * @author Alexander Tsukanov <alexander.tsukanov@opensoftdev.ru>
 */
class ArticleFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < 30; $i++) {
            $article = new Article();
            $article
                ->setTitle($i . ' Lorem ipsum dolor sit amet')
                ->setBody($i . ' Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque volutpat efficitur dolor, vel mollis ex viverra iaculis. Fusce feugiat mattis tellus sed scelerisque. Nunc pharetra quam arcu, ac congue enim aliquam id. Donec auctor ornare magna eget suscipit. In orci nisi, rutrum non tincidunt vitae, dapibus sed nulla. Vestibulum ante velit, molestie dapibus placerat id, posuere ut odio. In sed auctor neque. Ut ultrices, nulla non euismod vestibulum, mauris enim facilisis purus, ut vehicula justo enim eget nisi. Sed dictum posuere est eget posuere.');

            $manager->persist($article);
        }

        $manager->flush();
    }
}
