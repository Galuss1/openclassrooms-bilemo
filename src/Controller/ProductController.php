<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use App\Service\APIService;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

final class ProductController extends AbstractController
{
    public function __construct(private readonly APIService $apiService, private readonly ProductRepository $productRepository)
    {
    }

    /**
     * @OA\Get(
     *    path="/api/products/",
     *    tags={"Products"},
     *    summary="Retourne la liste des produits Bilemo avec un système de pagination.",
     *    description="Retourne la liste des produits Bilemo avec un système de pagination.",
     *    operationId="getAllProducts",
     *
     *    @OA\Parameter(
     *        name="page",
     *        in="query",
     *        description="La page courante.",
     *        required=false,
     *
     *        @OA\Schema(type="integer")
     *    ),
     *
     *    @OA\Parameter(
     *        name="limit",
     *        in="query",
     *        description="Le nombre d'éléments par page.",
     *        required=false,
     *
     *        @OA\Schema(type="integer")
     *    )
     *
     *    @OA\Response(
     *       response=200,
     *       description="Retourne la liste des produits Bilemo avec un système de pagination.",
     *
     *       @OA\JsonContent(
     *          type="array",
     *
     *          @OA\Items(ref=@Model(type=Product::class, groups={"product:read"}))
     *       ),
     *    ),
     * )
     */
    #[Route('/api/products/', name: 'product_list', methods: ['GET'])]
    public function getAllProducts(Request $request): JsonResponse
    {
        // Default pagination values
        $page = (int) $request->query->get('page', 1);
        $limit = (int) $request->query->get('limit', 5);

        // Retrieve all products with pagination
        $products = $this->productRepository->findAllWithPagination($page, $limit);

        // Set the cache id
        $idCache = \sprintf('product_list_page_%d_limit_%d', $page, $limit);

        return $this->apiService->get($products, ['product:read'], $idCache, 'product_tag');
    }

    /**
     * @OA\Get(
     *    path="/api/products/{id}",
     *    tags={"Products"},
     *    summary="Retourne le détail d'un produit Bilemo.",
     *    description="Retourne le détail d'un produit Bilemo.",
     *    operationId="getProduct",
     *
     *    @OA\Parameter(
     *       name="id",
     *       in="path",
     *       description="L'identifiant du produit Bilemo.",
     *       required=true,
     *
     *       @OA\Schema(type="integer")
     *    ),
     *
     *    @OA\Response(
     *       response=200,
     *       description="Retourne le détail d'un produit Bilemo.",
     *
     *       @OA\JsonContent(
     *          type="array",
     *
     *          @OA\Items(ref=@Model(type=Product::class, groups={"product:read"}))
     *       ),
     *    )
     * )
     */
    #[Route('/api/products/{id}', name: 'product_detail', methods: ['GET'])]
    public function getProduct(int $id): JsonResponse
    {
        // Retrieve the product
        $product = $this->productRepository->find($id);

        // Check if the product exists
        if (!$product instanceof Product) {
            return $this->json(['message' => "Ce produit n'existe pas"], 404);
        }

        // Set the cache id
        $idCache = \sprintf('product_detail_%s', $product->getId());

        return $this->apiService->get($product, ['product:read'], $idCache, 'product_tag');
    }
}
