<?php

declare(strict_types=1);

namespace App\Http\Controllers;

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
        $data = json_decode($request->getContent(), true);

        try {
            OrderCreateValidation::validate($data);
        } catch (Exception $exception) {
            return new JsonResponse([
                'message' => $exception->getMessage(),
            ], 400);
        }

        $order = $this->orderService->create($data['items']);

        return new JsonResponse($order);
    }
}