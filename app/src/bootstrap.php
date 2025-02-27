<?php

namespace App\Bootstrap;

use FastRoute\Dispatcher;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


readonly class App
{
    public function __construct(private ContainerInterface $container)
    {
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function run(): void
    {
        $request = Request::createFromGlobals();

        $dispatcher = $this->container->get('routes')->dispatch(
            $request->getMethod(),
            $request->getPathInfo(),
        );

        switch ($dispatcher[0]) {
            case Dispatcher::NOT_FOUND:
                $response = new JsonResponse(['message' => '404 Not Found'], 404);
                break;
            case Dispatcher::METHOD_NOT_ALLOWED:
                $response = new JsonResponse(['message' => '405 Method Not Allowed'], 405);
                break;
            case Dispatcher::FOUND:
                [$controller, $method] = $dispatcher[1];
                $vars = $dispatcher[2];

                $controllerInstance = $this->container->get($controller);
                $response = $controllerInstance->$method($request, $vars);
                break;
        }

        /** @var Response $response */
        $response->send();
    }
}
