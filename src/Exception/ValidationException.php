<?php

namespace App\Exception;

use Symfony\Component\Validator\ConstraintViolationListInterface;

/**
 * Exception thrown when an entity fails validation.
 */
class ValidationException extends \Exception
{
    private ConstraintViolationListInterface $violations;

    /**
     * @param ConstraintViolationListInterface $violations The list of validation errors.
     * @param string $message Optional error message.
     * @param int $code Optional error code.
     * @param \Throwable|null $previous Optional previous exception.
     */
    public function __construct(
        ConstraintViolationListInterface $violations,
        string $message = 'Validation error',
        int $code = 0,
        \Throwable $previous = null
    ) {
        $this->violations = $violations;
        parent::__construct($message, $code, $previous);
    }

    /**
     * Get the list of validation errors.
     *
     * @return ConstraintViolationListInterface
     */
    public function getViolations(): ConstraintViolationListInterface
    {
        return $this->violations;
    }
}
