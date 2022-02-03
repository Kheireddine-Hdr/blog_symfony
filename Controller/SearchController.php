<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SearchController extends AbstractController
{
    /**
     * @Route("/search", name="search")
     */
    public function index(): Response
    {
        return $this->render('search/index.html.twig', [
            'controller_name' => 'SearchController',
        ]);
    }

    public function searchBar()
    {
        $form = $this->createFormBuilder()
            ->setAction($this->generateUrl('handleSearch'))
            ->add('query', TextType::class, [
                'label' => false,
                'attr' => [
                    'style'=>" float:left; padding: 10px; font-size: 17px; border: 1px solid grey; width: 80%; background: #f1f1f1;",
                    'class' => 'form-control',
                    'placeholder' => 'Entrez un mot-clé'
                ]
            ])
            ->add('search', SubmitType::class, [
                'attr' => [
                    'style'=>"margin-top: -48px; margin-left: 300px; width: 20%; padding: 10px;
                     background: #2196F3; color: white; font-size: 17px; border: 1px solid grey; border-left: none; 
                     cursor: pointer; float: left",
                    'class' => 'btn btn-primary'
                ]
            ])
            ->getForm();
        return $this->render('search/searchBar.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/handleSearch", name="handleSearch")
     * @param Request $request
     */
    public function handleSearch(Request $request, ArticleRepository $repo)
    {
        $query = $request->request->get('form')['query'];
        if($query) {
            $articles = $repo->findArticlesByName($query);
        }
        return $this->render('search/index.html.twig', [
            'articles' => $articles
        ]);
    }
}
