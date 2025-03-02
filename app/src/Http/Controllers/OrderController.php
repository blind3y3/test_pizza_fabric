<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Helpers\RequestHelper;
use App\Modules\Order\Serializer\OrderListSerializer;
use App\Modules\Order\Service\OrderServiceInterface;
use App\Modules\Order\Validation\OrderOrderIdInVarsValidation;
use Exception;
use Modules\Auth\Service\AuthServiceInterface;
use Modules\Order\Validation\OrderItemsValidation;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

readonly class OrderController
{
    public function __construct(
        private OrderServiceInterface $orderService,
        private AuthServiceInterface $authService,
    ) {
    }

    public function index(Request $request): JsonResponse
    {
        $this->authService->checkRequestHaveAuthHeader($request->headers->get('X-Auth-Key'));
        $orders = $this->orderService->getOrders($request->query->get('done'));

        return new JsonResponse(OrderListSerializer::serialize($orders));
    }

    /**
     * @throws Exception
     */
    public function create(Request $request): JsonResponse
    {
        OrderItemsValidation::validate(RequestHelper::extractItems($request));
        $order = $this->orderService->create(RequestHelper::extractItems($request));

        return new JsonResponse($order);
    }

    /**
     * Примечание: структура body в одном формате как для создания, так и для добавления товаров
     *
     * @throws Exception
     */
    public function addItems(Request $request, array $vars): JsonResponse
    {
        OrderOrderIdInVarsValidation::validate($vars);
        OrderItemsValidation::validate(RequestHelper::extractItems($request));
        $this->orderService->checkOrderExistsAndNotDone(RequestHelper::extractOrderId($vars));

        $orderId = RequestHelper::extractOrderId($vars);
        $this->orderService->addItems($orderId, RequestHelper::extractItems($request));

        return new JsonResponse(null, Response::HTTP_OK);
    }

    /**
     * Примечание: в диспатчере при вызове контроллера туда передаются $request, $vars(параметры роута)
     */
    public function getById(Request $request, array $vars): JsonResponse
    {
        $order = $this->orderService->getById(RequestHelper::extractOrderId($vars));

        return new JsonResponse($order);
    }

    public function setDone(Request $request, array $vars): JsonResponse
    {
        $this->authService->checkRequestHaveAuthHeader($request->headers->get('X-Auth-Key'));
        $this->orderService->checkOrderExistsAndNotDone(RequestHelper::extractOrderId($vars));
        $this->orderService->setDone(RequestHelper::extractOrderId($vars));

        return new JsonResponse(null, Response::HTTP_OK);
    }
}