<?php

namespace App\EventListener;

use App\Exception\ValidationException;
use JMS\Serializer\Exception\RuntimeException as JMSSerializerRuntimeException;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\KernelEvents;

#[AsEventListener(event: KernelEvents::EXCEPTION, method: 'onKernelException')]
class ApiExceptionListener
{
    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();
        $errors = [];
        $httpStatus = Response::HTTP_INTERNAL_SERVER_ERROR;

        if ($exception instanceof ValidationException) {
            foreach ($exception->getViolations() as $violation) {
                $errors[] = [
                    'property' => $violation->getPropertyPath(),
                    'message'  => $violation->getMessage(),
                ];
            }
            $httpStatus = Response::HTTP_BAD_REQUEST;
        } elseif ($exception instanceof JMSSerializerRuntimeException) {
            $errors[] = [
                'property' => null,
                'message'  => $exception->getMessage(),
            ];
            $httpStatus = Response::HTTP_BAD_REQUEST;
        } elseif ($exception instanceof NotFoundHttpException) {
            $errors[] = [
                'property' => null,
                'message'  => $exception->getMessage() ?: 'Resource not found.',
            ];
            $httpStatus = Response::HTTP_NOT_FOUND;
        } else {
            $errors[] = [
                'message'  => 'Internal Server Error. Please contact the system administrator.',
            ];
        }

        $data = [
            'status' => $httpStatus,
            'errors' => $errors,
        ];

        $event->setResponse(new JsonResponse($data, $httpStatus));
    }
}
