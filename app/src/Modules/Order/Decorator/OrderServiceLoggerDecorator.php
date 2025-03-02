<?php

declare(strict_types=1);

namespace App\Modules\Order\Decorator;

use App\Modules\Order\Service\OrderServiceInterface;
use Exception;
use Modules\Order\Collection\OrderCollection;
use Modules\Order\Dto\OrderDto;
use Psr\Log\LoggerInterface;

readonly class OrderServiceLoggerDecorator implements OrderServiceInterface
{
    public function __construct(
        private OrderServiceInterface $orderService,
        private LoggerInterface $logger,
    ) {
    }

    /**
     * @throws Exception
     */
    public function getOrders(?string $done): OrderCollection
    {
        try {
            return $this->orderService->getOrders($done);
        } catch (Exception $exception) {
            $this->logger->error(sprintf('%s: %s', __FUNCTION__, $exception->getMessage()));
            throw $exception;
        }
    }

    /**
     * @throws Exception
     */
    public function create(array $items): OrderDto
    {
        try {
            return $this->orderService->create($items);
        } catch (Exception $exception) {
            $this->logger->error(
                sprintf('%s: %s', __FUNCTION__, $exception->getMessage()),
                ['items' => $items],
            );
            throw $exception;
        }
    }

    /**
     * @throws Exception
     */
    public function checkOrderExistsAndNotDone(int $orderId): void
    {
        try {
            $this->orderService->checkOrderExistsAndNotDone($orderId);
        } catch (Exception $exception) {
            $this->logger->error(
                sprintf('%s: %s', __FUNCTION__, $exception->getMessage()),
                ['orderId' => $orderId],
            );
            throw $exception;
        }
    }

    /**
     * @throws Exception
     */
    public function addItems(int $orderId, array $items): void
    {
        try {
            $this->orderService->addItems($orderId, $items);
        } catch (Exception $exception) {
            $this->logger->error(
                sprintf('%s: %s', __FUNCTION__, $exception->getMessage()),
                ['orderId' => $orderId, 'items' => $items],
            );
            throw $exception;
        }
    }

    /**
     * @throws Exception
     */
    public function getById(int $orderId): OrderDto
    {
        try {
            return $this->orderService->getById($orderId);
        } catch (Exception $exception) {
            $this->logger->error(
                sprintf('%s: %s', __FUNCTION__, $exception->getMessage()),
                ['orderId' => $orderId],
            );
            throw $exception;
        }
    }

    /**
     * @throws Exception
     */
    public function setDone(int $orderId): void
    {
        try {
            $this->orderService->setDone($orderId);
        } catch (Exception $exception) {
            $this->logger->error(
                sprintf('%s: %s', __FUNCTION__, $exception->getMessage()),
                ['orderId' => $orderId],
            );
            throw $exception;
        }
    }
}