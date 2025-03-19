<?php
namespace App\Tests\Functional;

use App\Entity\Employee;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class EmployeeControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $entityManager;

    protected function setUp(): void
    {
        self::ensureKernelShutdown();
        $this->client = static::createClient();
        $this->entityManager = static::getContainer()->get(EntityManagerInterface::class);

        // Clear the database before each test
        $this->clearDatabase();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->entityManager->close();
    }

    /**
     * @throws Exception
     */
    private function clearDatabase(): void
    {
        $this->entityManager->getConnection()->executeStatement('TRUNCATE employee RESTART IDENTITY CASCADE');
    }

    public function testCreateEmployee(): void
    {
        $this->client->request('POST', '/api/employees', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'firstName' => 'John',
            'lastName' => 'Doe',
            'email' => 'john.doe@example.com',
            'hiredAt' => '2025-04-01T00:00:00+00:00',
            'salary' => 150
        ]));

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(201);
    }

    public function testReadEmployee(): void
    {
        $employee = new Employee();
        $employee->setFirstName('Jane')
            ->setLastName('Doe')
            ->setEmail('jane.doe@example.com')
            ->setHiredAt(new \DateTimeImmutable('2025-04-01'))
            ->setSalary(100);

        $this->entityManager->persist($employee);
        $this->entityManager->flush();

        $id = $employee->getId();

        $this->client->request('GET', "/api/employees/$id");

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(200);
    }

    public function testUpdateEmployee(): void
    {
        $employee = new Employee();
        $employee->setFirstName('John')
            ->setLastName('Doe')
            ->setEmail('update@example.com')
            ->setHiredAt(new \DateTimeImmutable('2025-04-01'))
            ->setSalary(100);

        $this->entityManager->persist($employee);
        $this->entityManager->flush();
        $this->entityManager->clear();

        $id = $employee->getId();

        $this->client->request('PUT', "/api/employees/$id", [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'id' => $id,
            'salary' => 250
        ]));

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(200);

        $updatedEmployee = $this->entityManager->find(Employee::class, $id);
        $this->assertNotNull($updatedEmployee);
        $this->assertSame(250.0, $updatedEmployee->getSalary());
    }

    public function testDeleteEmployee(): void
    {
        $employee = new Employee();
        $employee->setFirstName('John')
            ->setLastName('Doe')
            ->setEmail('delete@example.com')
            ->setHiredAt(new \DateTimeImmutable('2025-04-01'))
            ->setSalary(100);

        $this->entityManager->persist($employee);
        $this->entityManager->flush();
        $this->entityManager->clear();

        $id = $employee->getId();

        $this->client->request('DELETE', "/api/employees/$id");

        $this->assertResponseStatusCodeSame(204);

        $deletedEmployee = $this->entityManager->find(Employee::class, $id);
        $this->assertNull($deletedEmployee);
    }

    public function testValidationFailsOnInvalidSalary(): void
    {
        $this->client->request('POST', '/api/employees', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'firstName' => 'John',
            'lastName' => 'Doe',
            'email' => 'invalid-salary@example.com',
            'hiredAt' => '2025-04-01T00:00:00+00:00',
            'salary' => 50
        ]));

        $this->assertResponseStatusCodeSame(400);
    }
}
