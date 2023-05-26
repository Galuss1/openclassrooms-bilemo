<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\CustomerRepository;
use App\Service\APIService;
use App\Service\CustomerService;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

final class CustomerController extends AbstractController
{
    public function __construct(private readonly APIService $apiService, private readonly CustomerService $customerService, private readonly CustomerRepository $customerRepository)
    {
    }

    /**
     *@OA\Post(
     *    path="/api/companies/{companyId}/customers",
     *    tags={"Customers"},
     *    summary="Créer un nouveau client attacher à une entreprise.",
     *    description="Créer un nouveau client attacher à une entreprise.",
     *    operationId="createCustomer",
     *
     *    @OA\RequestBody(
     *       required=true,
     *       description="Créer un nouveau client attacher à une entreprise.",
     *
     *       @OA\JsonContent(
     *          type="object",
     *
     *          @OA\Property(property="email", type="string", example="test@email.fr"),
     *          @OA\Property(property="first_name", type="string", example="John"),
     *          @OA\Property(property="last_name", type="string", example="Doe"),
     *          @OA\Property(property="password", type="string", example="password"),
     *          @OA\Property(property="phone_number", type="string", example="0102030405"),
     *          @OA\Property(property="address", type="object",
     *              @OA\Property(property="country", type="string", example="France"),
     *              @OA\Property(property="city", type="string", example="Paris"),
     *              @OA\Property(property="postal_code", type="integer", example=75000),
     *              @OA\Property(property="address", type="string", example="123 Rue du test"),
     *              @OA\Property(property="address_details", type="string", example="Appartement 123", nullable=true),
     *          )
     *      )
     *    ),
     *
     *    @OA\Parameter(
     *       name="companyId",
     *       in="path",
     *       description="L'identifiant de l'entreprise.",
     *       required=true,
     *
     *       @OA\Schema(type="integer")
     *    ),
     *
     *   @OA\Response(
     *      response=201,
     *      description="Créer un nouveau client attaché à une entreprise.",
     *
     *      @OA\JsonContent(
     *         type="array",
     *
     *         @OA\Items(ref=@Model(type=Customer::class, groups={"customer:read"}))
     *      )
     *   ),
     *)
     */
    #[Route('/api/companies/{companyId}/customers', name: 'customer_create', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN', message: 'Vous n\'avez pas les droits suffisants pour créer un nouveau client')]
    public function createCustomer(Request $request): JsonResponse
    {
        return $this->customerService->createCustomer($request, ['customer_tag']);
    }

    /**
     * @OA\Get(
     *    path="/api/companies/{companyId}/customers",
     *    tags={"Customers"},
     *    summary="Retourne la liste des clients attachés à une entreprise avec un système de pagination.",
     *    description="Retourne la liste des clients attachés à une entreprise avec un système de pagination.",
     *    operationId="getAllCustomers",
     *
     *    @OA\Parameter(
     *       name="companyId",
     *       in="path",
     *       description="L'identifiant de l'entreprise.",
     *       required=true,
     *
     *       @OA\Schema(type="integer")
     *    ),
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
     *       description="Retourne la liste des clients attachés à une entreprise avec un système de pagination.",
     *
     *       @OA\JsonContent(
     *          type="array",
     *
     *          @OA\Items(ref=@Model(type=Customer::class, groups={"customer:read"}))
     *       ),
     *    ),
     * )
     */
    #[Route('/api/companies/{companyId}/customers', name: 'customer_list', methods: ['GET'])]
    public function getAllCustomers(int $companyId, Request $request): JsonResponse
    {
        // Default pagination values
        $page = (int) $request->query->get('page', 1);
        $limit = (int) $request->query->get('limit', 5);

        // Retrieve all customers by company with pagination
        $customers = $this->customerRepository->findAllByCompanyWithPagination($companyId, $page, $limit);

        // Check if customers with company id exist
        if (!$customers) {
            return $this->json(['message' => "Aucun client n'a été trouvé pour cette entreprise"], 404);
        }

        // Set the cache id
        $idCache = \sprintf('customer_list_page_%d_limit_%d', $page, $limit);

        return $this->apiService->get($customers, ['customer:read'], $idCache, 'customer_tag');
    }

    /**
     * @OA\Get(
     *    path="/api/companies/{companyId}/customers/{customerId}",
     *    tags={"Customers"},
     *    summary="Retourne le détail d'un client attaché à une entreprise.",
     *    description="Retourne le détail d'un client attaché à une entreprise.",
     *    operationId="getCustomer",
     *
     *    @OA\Parameter(
     *       name="companyId",
     *       in="path",
     *       description="L'identifiant de l'entreprise.",
     *       required=true,
     *
     *       @OA\Schema(type="integer")
     *    ),
     *
     *    @OA\Parameter(
     *       name="customerId",
     *       in="path",
     *       description="L'identifiant du client.",
     *       required=true,
     *
     *       @OA\Schema(type="integer")
     *    ),
     *
     *    @OA\Response(
     *       response=200,
     *       description="Retourne le détail d'un client attaché à une entreprise.",
     *
     *       @OA\JsonContent(
     *          type="array",
     *
     *          @OA\Items(ref=@Model(type=Customer::class, groups={"customer:read"}))
     *       ),
     *    )
     * )
     */
    #[Route('/api/companies/{companyId}/customers/{customerId}', name: 'customer_detail', methods: ['GET'])]
    public function getCustomer(int $companyId, int $customerId): JsonResponse
    {
        // Retrieve the customer
        $customer = $this->customerRepository->findByCompany($companyId, $customerId);

        // Check if the customer exists
        if (!$customer) {
            return $this->json(['message' => "Ce client n'existe pas"], 404);
        }

        // Set the cache id
        $idCache = \sprintf('customer_detail_%s', $customer->getId());

        return $this->apiService->get($customer, ['customer:read'], $idCache, 'customer_tag');
    }

    /**
     * @OA\Delete(
     *    path="/api/companies/{companyId}/customers/{customerId}",
     *    tags={"Customers"},
     *    summary="Supprime le client d'une entreprise.",
     *    description="Supprime le client d'une entreprise.",
     *    operationId="deleteCustomer",
     *
     *    @OA\Parameter(
     *       name="companyId",
     *       in="path",
     *       description="L'identifiant de l'entreprise.",
     *       required=true,
     *
     *       @OA\Schema(type="integer")
     *    ),
     *
     *    @OA\Parameter(
     *       name="customerId",
     *       in="path",
     *       description="L'identifiant du client.",
     *       required=true,
     *
     *       @OA\Schema(type="integer")
     *    ),
     *
     *    @OA\Response(
     *      response=204,
     *      description="Supprime le client d'une entreprise.",
     *    ),
     *    @OA\Response(
     *      response=404,
     *      description="Ce client n'existe pas ou n'est pas attaché à cette entreprise."
     *    )
     * )
     */
    #[Route('/api/companies/{companyId}/customers/{customerId}', name: 'customer_delete', methods: ['DELETE'])]
    #[IsGranted('ROLE_ADMIN', message: "Vous n'avez pas les droits suffisants pour supprimer un client")]
    public function deleteCustomer(int $customerId, int $companyId): JsonResponse
    {
        // Retrieve the customer
        $customer = $this->customerRepository->find($customerId);

        // Check if the customer exists and if it belongs to the company
        if (!$customer || $customer->getCompany()->getId() !== $companyId) {
            return $this->json(['message' => "Ce client n'existe pas ou n'est pas attaché à cette entreprise"], 404);
        }

        return $this->apiService->delete($customer, ['customer_tag']);
    }
}
