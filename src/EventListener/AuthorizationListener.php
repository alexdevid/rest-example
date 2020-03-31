<?php

namespace App\EventListener;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\VarDumper\VarDumper;

/**
 * @author Alexander Tsukanov <https://alexdevid.com>
 */
class AuthorizationListener
{
    private const TOKEN_PARAM = 'token';
    private const TOKEN_VALUE = 'secret';

    public function onKernelRequest(RequestEvent $event)
    {
        if (!$event->isMasterRequest()) {
            return;
        }

        $request = $event->getRequest();
        if (in_array($request->getMethod(), [Request::METHOD_POST, Request::METHOD_PUT, Request::METHOD_DELETE])) {
            if ($request->get(self::TOKEN_PARAM) !== self::TOKEN_VALUE) {
                throw new UnauthorizedHttpException('', 'Token value is incorrect');
            }
        }
    }
}