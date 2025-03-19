<?php

namespace App\Controller\API;

use App\Exception\ValidationException;
use Doctrine\ORM\EntityManagerInterface;
use JMS\Serializer\SerializerInterface;
use JMS\Serializer\DeserializationContext;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validator\ValidatorInterface;

abstract class BaseApiController extends AbstractController
{
    protected EntityManagerInterface $em;
    protected SerializerInterface $serializer;
    protected ValidatorInterface $validator;

    public function __construct(
        EntityManagerInterface $em,
        SerializerInterface $serializer,
        ValidatorInterface $validator
    ) {
        $this->em = $em;
        $this->serializer = $serializer;
        $this->validator = $validator;
    }

    protected function removeEntity($entity): void
    {
        $this->em->remove($entity);
        $this->em->flush();
    }

    /**
     * @throws ValidationException
     */
    protected function saveEntity(object $entity, bool $validate = true): void
    {
        $this->em->persist($entity);
        if ($validate) {
            $this->validateEntity($entity);
        }
        $this->em->flush();
    }

    /**
     * @throws ValidationException
     */
    protected function validateEntity($entity):void
    {
        $violations = $this->validator->validate($entity);
        if ($violations->count()) {
            throw new ValidationException($violations);
        }
    }

    protected function createEntityFromJson(Request $request, string $class): object
    {
        $entity = new $class();
        $context = DeserializationContext::create();
        $context->setAttribute('object_to_populate', $entity);

        return $this->serializer->deserialize($request->getContent(), $class, 'json', $context);
    }

    protected function updateEntityFromJson(Request $request, string $class, object $entity): object
    {
        $context = DeserializationContext::create();
        $context->setAttribute('object_to_populate', $entity);

        return $this->serializer->deserialize($request->getContent(), $class, 'json', $context);
    }

    protected function response(object $entity, int $httpStatus = Response::HTTP_OK): JsonResponse
    {
        $json = $this->serializer->serialize($entity, 'json');

        return new JsonResponse($json, $httpStatus, ['Content-Type' => 'application/json'], true);
    }
}
