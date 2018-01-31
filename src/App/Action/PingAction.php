<?php

namespace App\Action;

use App\Command\PingCommand;
use DateTime;
use DateTimeImmutable;
use InFw\Framework\Action\AbstractAction;
use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface;
use League\Tactician\CommandBus;
use Zend\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ServerRequestInterface;

class PingAction implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, DelegateInterface $delegate)
    {
        $time = DateTimeImmutable::createFromMutable(new DateTime());

        return new JsonResponse(['ack' => $time]);
    }
}
