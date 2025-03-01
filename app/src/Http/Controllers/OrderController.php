<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Helpers\RequestHelper;
use App\Modules\Order\Exception\OrderNotFoundException;
use App\Modules\Order\Validation\OrderOrderIdInVarsValidation;
use Exception;
use Modules\Auth\Service\AuthService;
use Modules\Order\Service\OrderService;
use Modules\Order\Validation\OrderItemsValidation;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

readonly class OrderController
{
    private LoggerInterface $logger;

    /**
     * @throws ContainerExceptionInterface
     */
    public function __construct(
        private OrderService $orderService,
        private AuthService $authService,
        private ContainerInterface $container,
    ) {
        $this->logger = $this->container->get('appLogger');
    }

    /**
     * Получение списка всех заказов
     *
     * @throws \Doctrine\DBAL\Exception
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $this->authService->checkRequestHaveAuthHeader($request->headers->get('X-Auth-Key'));
        } catch (Exception $exception) {
            return new JsonResponse([
                'message' => $exception->getMessage(),
            ], $exception->getCode());
        }
        $orders = $this->orderService->getOrders($request->query->get('done'));

        return new JsonResponse($orders->toArray());
    }

    /**
     * Создание нового заказа клиентом
     *
     * @throws \Doctrine\DBAL\Exception
     */
    public function create(Request $request): JsonResponse
    {
        try {
            OrderItemsValidation::validate(RequestHelper::extractItems($request));
        } catch (Exception $exception) {
            $this->logger->error('orders.create', [$exception->getMessage()]);
            return new JsonResponse([
                'message' => $exception->getMessage(),
            ], $exception->getCode());
        }

        $order = $this->orderService->create(RequestHelper::extractItems($request));

        return new JsonResponse($order);
    }

    /**
     * @throws \Doctrine\DBAL\Exception|OrderNotFoundException
     */
    public function addItems(Request $request, array $vars): JsonResponse
    {
        try {
            OrderOrderIdInVarsValidation::validate($vars);
            OrderItemsValidation::validate(RequestHelper::extractItems($request));
            $this->orderService->checkOrderExistsAndNotDone(RequestHelper::extractOrderId($vars));
        } catch (Exception $exception) {
            $this->logger->error('orders.addItems', [$exception->getMessage()]);
            return new JsonResponse([
                'message' => $exception->getMessage(),
            ], 400);
        }

        $orderId = RequestHelper::extractOrderId($vars);
        $this->orderService->addItems($orderId, RequestHelper::extractItems($request));

        return new JsonResponse(null, 200);
    }

    /**
     * В диспатчере при вызове контроллера туда передаются $request, $vars(параметры роута)
     *
     * @throws \Doctrine\DBAL\Exception
     */
    public function getById(Request $request, array $vars): JsonResponse
    {
        try {
            $order = $this->orderService->getById(RequestHelper::extractOrderId($vars));
        } catch (OrderNotFoundException $exception) {
            return new JsonResponse([
                'message' => $exception->getMessage(),
            ], $exception->getCode());
        }

        return new JsonResponse($order);
    }

    /**
     * @throws \Doctrine\DBAL\Exception
     */
    public function setDone(Request $request, array $vars): JsonResponse
    {
        try {
            $this->authService->checkRequestHaveAuthHeader($request->headers->get('X-Auth-Key'));
            $this->orderService->checkOrderExistsAndNotDone(RequestHelper::extractOrderId($vars));
        } catch (Exception $exception) {
            $this->logger->error('orders.setDone', [$exception->getMessage()]);
            return new JsonResponse([
                'message' => $exception->getMessage(),
            ], $exception->getCode());
        }

        return new JsonResponse(null, 200);
    }
}