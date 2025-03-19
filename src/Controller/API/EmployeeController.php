<?php

namespace App\Controller\API;

use App\Entity\Employee;
use App\Exception\ValidationException;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/employees', name: 'employee_')]
#[OA\Tag(name: "Employees", description: "Endpoints related to employee management")]
class EmployeeController extends BaseApiController
{
    /**
     * @throws ValidationException
     */
    #[Route('', name: 'create', methods: ['POST'])]
    #[OA\Post(
        summary: "Create a new employee",
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: "#/components/schemas/Employee")
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: "Employee created successfully",
                content: new OA\JsonContent(ref: "#/components/schemas/Employee")
            )
        ]
    )]
    public function create(Request $request): Response
    {
        $employee = $this->createEntityFromJson($request, Employee::class);
        $this->saveEntity($employee);

        return $this->response($employee, Response::HTTP_CREATED);
    }

    #[Route('/{id}', name: 'read', methods: ['GET'])]
    #[OA\Get(
        summary: "Get an employee by ID",
        parameters: [
            new OA\Parameter(
                name: "id",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "integer")
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Employee details",
                content: new OA\JsonContent(ref: "#/components/schemas/Employee")
            ),
            new OA\Response(response: 404, description: "Employee not found")
        ]
    )]
    public function read(Employee $employee): Response
    {
        return $this->response($employee);
    }

    /**
     * @throws ValidationException
     */
    #[Route('/{id}', name: 'update', methods: ['PUT'])]
    #[OA\Put(
        summary: "Update an existing employee",
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: "#/components/schemas/Employee")
        ),
        parameters: [
            new OA\Parameter(
                name: "id",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "integer")
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Employee updated successfully",
                content: new OA\JsonContent(ref: "#/components/schemas/Employee")
            ),
            new OA\Response(response: 404, description: "Employee not found")
        ]
    )]
    public function update(Request $request, Employee $employee): Response
    {
        $this->updateEntityFromJson($request, Employee::class, $employee);
        $this->saveEntity($employee);

        return $this->response($employee);
    }

    #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
    #[OA\Delete(
        summary: "Delete an employee",
        parameters: [
            new OA\Parameter(
                name: "id",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "integer")
            )
        ],
        responses: [
            new OA\Response(response: 204, description: "Employee deleted successfully"),
            new OA\Response(response: 404, description: "Employee not found")
        ]
    )]
    public function delete(Employee $employee): Response
    {
        $this->removeEntity($employee);
        return new Response(null, Response::HTTP_NO_CONTENT);
    }
}

