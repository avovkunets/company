<?php

namespace App\Entity;

use App\Repository\EmployeeRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as JMS;
use OpenApi\Attributes as OA;
use Gedmo\Mapping\Annotation as Gedmo;

#[UniqueEntity(fields: ["email"], message: "This email is already in use.")]
#[ORM\Entity(repositoryClass: EmployeeRepository::class)]
#[OA\Schema(
    schema: "Employee",
    description: "Represents an employee in the company",
    required: ["firstName", "lastName", "email", "hiredAt", "salary"]
)]
class Employee
{
    #[OA\Property(description: "Employee ID", example: 1)]
    #[JMS\Type("integer")]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[OA\Property(description: "First name of the employee", example: "John")]
    #[JMS\SerializedName("firstName")]
    #[JMS\Type("string")]
    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "First name is required.")]
    private ?string $firstName = null;

    #[OA\Property(description: "Last name of the employee", example: "Doe")]
    #[JMS\Type("string")]
    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Last name is required.")]
    private ?string $lastName = null;

    #[OA\Property(description: "Email of the employee", example: "john@example.com")]
    #[JMS\Type("string")]
    #[ORM\Column(length: 255, unique: true)]
    #[Assert\NotBlank(message: "Email is required.")]
    #[Assert\Email(message: "The email '{{ value }}' is not a valid email.")]
    private ?string $email = null;

    #[OA\Property(description: "Hiring date in ISO format", example: "2025-04-01T00:00:00+00:00")]
    #[JMS\Type("DateTimeImmutable<'Y-m-d\\TH:i:sP'>")]
    #[ORM\Column]
    #[Assert\NotBlank(message: "Hired date is required.")]
    #[Assert\GreaterThanOrEqual("today", message: "The hired date cannot be in the past.")]
    private ?\DateTimeImmutable $hiredAt = null;

    #[OA\Property(description: "Employee salary", example: 150)]
    #[JMS\Expose]
    #[JMS\SerializedName("salary")]
    #[JMS\Type("float")]
    #[ORM\Column(type: "float")]
    #[Assert\NotBlank(message: "Salary is required.")]
    #[Assert\GreaterThanOrEqual(value: 100, message: "Salary must be at least 100.")]
    private ?float $salary = null;

    #[OA\Property(description: "Timestamp of when the employee was created", example: "2025-04-01T12:00:00+00:00")]
    #[JMS\Accessor(getter: "getCreatedAt", setter: false)]
    #[JMS\Type("DateTime<'Y-m-d\\TH:i:sP'>")]
    #[Gedmo\Timestampable(on: 'create')]
    #[ORM\Column(type: "datetime", options: ["default" => "CURRENT_TIMESTAMP"])]
    private ?\DateTime $createdAt = null;

    #[OA\Property(description: "Timestamp of the last update", example: "2025-04-02T15:30:00+00:00")]
    #[JMS\Accessor(getter: "getUpdatedAt", setter: false)]
    #[JMS\Type("DateTime<'Y-m-d\\TH:i:sP'>")]
    #[Gedmo\Timestampable(on: 'update')]
    #[ORM\Column(type: "datetime", options: ["default" => "CURRENT_TIMESTAMP"])]
    private ?\DateTime $updatedAt = null;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): static
    {
        $this->firstName = $firstName;
        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): static
    {
        $this->lastName = $lastName;
        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;
        return $this;
    }

    public function getHiredAt(): ?\DateTimeImmutable
    {
        return $this->hiredAt;
    }

    public function setHiredAt(\DateTimeImmutable $hiredAt): static
    {
        $this->hiredAt = $hiredAt;
        return $this;
    }

    public function getSalary(): ?float
    {
        return $this->salary;
    }

    public function setSalary(float $salary): static
    {
        $this->salary = $salary;
        return $this;
    }

    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): ?\DateTime
    {
        return $this->updatedAt;
    }
}
