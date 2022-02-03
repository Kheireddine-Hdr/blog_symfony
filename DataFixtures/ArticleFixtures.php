<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Article;
use App\Entity\Category;
use App\Entity\Comment;

class ArticleFixtures extends Fixture
{
    
    public function load(ObjectManager $manager): void // la fonction load va recevoir le fameux manager
    {
        $faker = \Faker\Factory::create('fr_FR');

        // créer 03 categorie fackée
        for($i=1; $i <= 3; $i++){
            $categorie = new Category();
            $categorie->setTitle($faker->sentence())
                      ->setDescription($faker->paragraph());

            $manager->persist($categorie);

            for($j = 1; $j <= mt_rand(4, 6); $j++){ // ici je vais créer 10 articles
                $article = new Article(); // pour créer un article, instancier une classe Article qui sera egal a new Article
                                        // dans symfony dès que nous utilisons le nom d'une classe, n'oublions pas de rajouter 
                                        //un use en haut qui est une sorte de require once presque pour expliquer à PHP 
                                        // d'ou vient la classe  Article (use App\Entity\Article;
                // commencer à ecrire des truc dans mon article avec set...
                
                $content = '<p>' . join($faker->paragraphs(5), '<p><p>') . '</p>';
                
                $article->setTitle($faker->sentence())
                        ->setContent($content)
                        ->setImage($faker->imageUrl())
                        ->setCreatedAt($faker->dateTimeBetween('-6 months'))
                        ->setCategory($categorie);
                $manager->persist($article); 
                
                for($k = 1; $k <=mt_rand(4, 10); $k++){
                    $comment = new Comment();

                    $content = '<p>' . join($faker->paragraphs(2), '<p><p>') . '</p>';

                    $now = new \DateTime();
                    $interval = $now->diff($article->getCreatedAt());
                    $days = $interval->days;
                    $minimum = '-' . 'days'; //-100 days

                    $comment->setAuthor($faker->name)
                            ->setContent($content)
                            ->setCreatedAt($faker->dateTimeBetween($minimum))
                            ->setArticle($article);
                    
                    $manager->persist($comment);
                }
            }
        }

        
        $manager->flush();
    }
}
