<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Customer;
use App\Entity\CustomerAddress;
use App\Repository\CompanyRepository;
use Doctrine\ORM\EntityManagerInterface;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class CustomerService
{
    public function __construct(private readonly SerializerInterface $serializer, private readonly CompanyRepository $companyRepository, private readonly EntityManagerInterface $em, private readonly ValidatorInterface $validator, private readonly UrlGeneratorInterface $urlGenerator, private readonly APIService $apiService, private readonly UserPasswordHasherInterface $passwordHasher)
    {
    }

    public function createCustomer(Request $request, ?array $tags = null): JsonResponse
    {
        // Recovers all the data that has been sent
        $content = $request->toArray();

        // Deserializes the data into a Customer object
        $deserializedCustomer = $this->serializer->deserialize($request->getContent(), Customer::class, 'json');

        // Creates a new Customer object (do not use the object deserialized with JMS)
        $newCustomer = new Customer();
        $newCustomer->setEmail($deserializedCustomer->getEmail());
        $newCustomer->setFirstName($deserializedCustomer->getFirstName());
        $newCustomer->setLastName($deserializedCustomer->getLastName());
        $newCustomer->setPhoneNumber($deserializedCustomer->getPhoneNumber());
        $newCustomer->setPassword($this->passwordHasher->hashPassword($newCustomer, $deserializedCustomer->getPassword()));

        // Check if the customer email already exists
        if ($this->checkCustomerExists($deserializedCustomer->getEmail())) {
            return new JsonResponse('Cet email est déjà utilisé', Response::HTTP_BAD_REQUEST, [], true);
        }

        // Check if the company exists
        $companyId = (int) $request->attributes->get('companyId');
        if (!$this->checkCompanyExists($companyId)) {
            return new JsonResponse("Cette entreprise n'existe pas", Response::HTTP_BAD_REQUEST, [], true);
        }

        // Sets the company to the customer
        $newCustomer->setCompany($this->companyRepository->find($companyId));

        // Sets the address to the customer
        $address = $this->createCustomerAddress($newCustomer, $content);
        $newCustomer->addCustomerAddress($address);

        // Validates the data
        if (($result = $this->dataValidation($newCustomer, $address)) instanceof JsonResponse) {
            return $result;
        }

        $this->em->persist($newCustomer);
        $this->em->persist($address);
        $this->em->flush();

        return $this->apiService->post($newCustomer, $this->getLocation($newCustomer), ['customer:read'], $tags);
    }

    private function checkCompanyExists(int $companyId): bool
    {
        $company = $this->companyRepository->findOneBy(['id' => $companyId]);

        return (bool) $company;
    }

    private function checkCustomerExists(string $email): bool
    {
        $customer = $this->em->getRepository(Customer::class)->findOneBy(['email' => $email]);

        return (bool) $customer;
    }

    private function createCustomerAddress(Customer $customer, array $content): CustomerAddress
    {
        $address = new CustomerAddress();
        $address->setCustomer($customer);
        $address->setCountry($content['address']['country']);
        $address->setCity($content['address']['city']);
        $address->setPostalCode($content['address']['postal_code']);
        $address->setAddress($content['address']['address']);
        $address->setAddressDetails(($content['address']['address_details'] ?? null));

        return $address;
    }

    private function dataValidation(Customer $customer, CustomerAddress $address): bool | JsonResponse
    {
        $customerErrors = $this->validator->validate($customer);
        $addressErrors = $this->validator->validate($address);
        $errors = \array_merge($customerErrors->getIterator()->getArrayCopy(), $addressErrors->getIterator()->getArrayCopy());

        if ([] !== $errors) {
            $messages = [];
            foreach ($errors as $error) {
                $property = $error->getPropertyPath();
                $message = (string) $error;
                $messages[$property] = $message;
            }

            $jsonResponse = \json_encode($messages, JSON_PRETTY_PRINT);

            return new JsonResponse($jsonResponse, Response::HTTP_BAD_REQUEST, [], true);
        }

        return true;
    }

    private function getLocation(Customer $customer): string
    {
        return $this->urlGenerator->generate(
            'customer_detail',
            [
                'customerId' => $customer->getId(),
                'companyId' => $customer->getCompany()->getId(),
            ]
        );
    }
}
