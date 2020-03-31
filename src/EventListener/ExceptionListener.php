<?php

namespace App\EventListener;

use App\Serializer\Serializer;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

/**
 * @author Alexander Tsukanov <https://alexdevid.com>
 */
class ExceptionListener
{
    /**
     * @var Serializer
     */
    private $serializer;

    /**
     * @param Serializer $serializer
     */
    public function __construct(Serializer $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * @param ExceptionEvent $event
     */
    public function onKernelException(ExceptionEvent $event)
    {
        $response = new Response();
        $exception = $event->getThrowable();
        $error = [
            'code' => $exception->getCode(),
            'type' => get_class($exception),
            'message' => $exception->getMessage()
        ];

        if ($exception instanceof HttpExceptionInterface) {
            $response->setStatusCode($exception->getStatusCode());
            $response->headers->replace($exception->getHeaders());
            $error['code'] = $response->getStatusCode();
        } else {
            $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $response->setContent($this->serializer->serialize($error));

        $event->setResponse($response);
    }
}