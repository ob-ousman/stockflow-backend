<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Fournisseur;
use App\Entity\Lot;
use App\Entity\LotReception;
use App\Entity\Reception;
use App\Repository\ArticleRepository;
use App\Repository\FournisseurRepository;
use DateTime;
use Doctrine\DBAL\Exception\RetryableException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\File;

class ReceptionController extends AbstractController
{
    private $imagesDirectory;


    public function __construct(string $imagesDirectory, private EntityManagerInterface $entityManager)
    {
        $this->imagesDirectory = $imagesDirectory;
    }

    #[Route('/api/receptions/save_reception', name: 'save_reception', methods: ['POST'])]
    public function saveReception(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $this->entityManager->beginTransaction();

        try {

            if (!$data) {
                return new JsonResponse(['error' => 'Invalid JSON'], Response::HTTP_BAD_REQUEST);
            }

            // Récupération des informations principales
            $numero = $data['numero'] ?? null;
            $date = $data['date'] ?? null;
            $fournisseur = $this->entityManager->getRepository(Fournisseur::class)->find($data['fournisseur']);
            $lotReceptions = $data['lotReceptions'] ?? [];

            if (!$numero || !$date || !$fournisseur || empty($lotReceptions)) {
                return new JsonResponse(['error' => 'Données manquantes'], Response::HTTP_BAD_REQUEST);
            }

            // Créer une nouvelle instance de la réception
            $reception = new Reception();
            $reception->setNumero($numero);
            $reception->setDate(new \DateTime($date));
            $reception->setFournisseur($fournisseur);

            // Enregistrement de la réception dans la base de données
            $this->entityManager->persist($reception);
            $this->entityManager->flush();

            $filesystem = new Filesystem();

            // Gérer les lots de la réception
            foreach ($lotReceptions as $lotRecep) {
                $quantite = $lotRecep['quantite'];
                $article =  $this->entityManager->getRepository(Article::class)->find($lotRecep['article']);

                if (!$article | !$quantite) {
                    return new JsonResponse(['error' => 'Données manquantes'], Response::HTTP_BAD_REQUEST);
                }

                //Sauvegarder les informations du lot dans la base de données
                $datePeremption = DateTime::createFromFormat('Y-m-d', $lotRecep['peremption']);;
                $numereLot = $lotRecep['lot'];
                $photoLotFourn = $lotRecep['photo'];

                $lot = new Lot();
                $lot->setNumero($numereLot);
                $lot->setPeremption($datePeremption);
                $lot->setQrcode('');

                // Extraction du type de fichier et conversion Base64 en fichier image
                if ($photoLotFourn) {
                    $photoFilePath = $this->saveBase64Image($photoLotFourn, $numereLot);
                    if (!$photoFilePath) {
                        return new JsonResponse(['error' => 'Invalid photo format'], Response::HTTP_BAD_REQUEST);
                    }
                    $lot->setLotFournisseur($photoFilePath);
                }
                $this->entityManager->persist($lot);
                $this->entityManager->flush();

                //sauvegarde de lotReception
                $lotReception = new LotReception();
                $lotReception->setArticle($article);
                $lotReception->setQuantite($quantite);
                $lotReception->setReception($reception);
                $lotReception->setLot($lot);
                $this->entityManager->persist($lotReception);
                $this->entityManager->flush();
            }

            $this->entityManager->commit();

            return new JsonResponse(['message' => 'Reception enregistrée avec succès'], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            $this->entityManager->rollback();
            return $this->json(['error' => $e->getMessage()], 400);
        } catch (RetryableException $retryableException) {
            $this->entityManager->rollback();
            return $this->json(['error' => 'Erreur de la base de données, veuillez réessayer.'], 500);
        }
    }

    /**
     * Fonction pour sauvegarder une image à partir d'un format base64
     * 
     * @param string $base64Image
     * @return string|null
     */
    private function saveBase64Image(string $base64Image, $fileName): ?string
    {
        // Extraire les données du fichier base64
        if (preg_match('/^data:image\/(\w+);base64,/', $base64Image, $type)) {
            $base64Image = substr($base64Image, strpos($base64Image, ',') + 1);
            $type = strtolower($type[1]); // jpg, png, gif

            // Vérifier le type de fichier
            if (!in_array($type, ['jpg', 'jpeg', 'png', 'gif'])) {
                return null;
            }

            $base64Image = base64_decode($base64Image);

            if ($base64Image === false) {
                return null;
            }

            $filePath = $this->imagesDirectory . '/' . $fileName . '.' . $type;

            file_put_contents($filePath, $base64Image);

            return $fileName;
        } else {
            return null;
        }
    }
}
