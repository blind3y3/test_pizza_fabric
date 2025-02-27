<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Exception;
use Modules\Order\Service\OrderService;
use Modules\Order\Validation\OrderCreateValidation;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

readonly class OrderController
{
    private readonly LoggerInterface $logger;

    public function __construct(
        private OrderService $orderService,
        private ContainerInterface $container,
    ) {
        $this->logger = $this->container->get('appLogger');
    }

    public function index(): JsonResponse
    {
        $orders = $this->orderService->getOrders();
        return new JsonResponse(['success' => true, 'data' => $orders]);
    }

    /**
     * @throws Exception
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

        return new JsonResponse(
            [
                'order_id' => 123,
                'items'    => [1, 2, 3, 4],
                'done'     => false,
            ]
        );
    }

    public function addItems(Request $request): JsonResponse
    {
        return new JsonResponse(null, 200);
    }

    public function getById(int $id): JsonResponse
    {
        return new JsonResponse(
            [
                'order_id' => 123,
                'items'    => [1, 2, 3, 4],
                'done'     => false,
            ], 200);
    }

    public function setDone(int $id): JsonResponse
    {
    }
}