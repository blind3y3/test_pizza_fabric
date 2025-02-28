<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Helpers\RequestHelper;
use App\Modules\Order\Validation\OrderAddItemsRequestValidation;
use Exception;
use Modules\Order\Service\OrderService;
use Modules\Order\Validation\OrderCreateValidation;
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
            OrderCreateValidation::validate(RequestHelper::extractItems($request));
        } catch (Exception $exception) {
            $this->logger->error($exception->getMessage());
            return new JsonResponse([
                'message' => $exception->getMessage(),
            ], 400);
        }

        $order = $this->orderService->create(RequestHelper::extractItems($request));

        return new JsonResponse($order);
    }

    /**
     * @throws \Doctrine\DBAL\Exception
     */
    public function addItems(Request $request, array $vars): JsonResponse
    {
        try {
            OrderAddItemsRequestValidation::validate($vars);
            OrderCreateValidation::validate(RequestHelper::extractItems($request));
        } catch (Exception $exception) {
            $this->logger->error($exception->getMessage());
            return new JsonResponse([
                'message' => $exception->getMessage(),
            ], 400);
        }

        $orderId = RequestHelper::extractOrderId($vars);
        $this->orderService->addItems($orderId, RequestHelper::extractItems($request));

        return new JsonResponse(null, 200);
    }
}