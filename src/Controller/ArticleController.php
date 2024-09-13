<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Facture;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\DBAL\Exception\RetryableException;

class ArticleController extends AbstractController
{

    public function __construct(
        private EntityManagerInterface $entityManager,
        private UserRepository $userRepository
    ) {
    }

    #[Route('/api/articles/save_article', name: 'save_article', methods: ['POST'])]
    public function createArticle(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $entityManager->beginTransaction();

        try {

            $article = new Article();
            $article->setCode($data['code']);
            $article->setDesignation($data['designation']);

            $entityManager->persist($article);
            $entityManager->flush();


            $entityManager->commit();

            return $this->json(['message' => 'Article enregistré avec succès !', 'numero' => $article->getCode()], 201);

        } catch (\Exception $e) {
            $entityManager->rollback();
            return $this->json(['error' => $e->getMessage()], 400);
        } catch (RetryableException $retryableException) {
            $entityManager->rollback();
            return $this->json(['error' => 'Erreur de la base de données, veuillez réessayer.'], 500);
        }
    }
}