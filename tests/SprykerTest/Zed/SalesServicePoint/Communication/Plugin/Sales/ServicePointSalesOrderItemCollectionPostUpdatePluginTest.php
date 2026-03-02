<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SalesServicePoint\Communication\Plugin\Sales;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\QuoteBuilder;
use Generated\Shared\DataBuilder\ServicePointBuilder;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SalesOrderItemCollectionResponseTransfer;
use Generated\Shared\Transfer\ServicePointTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Shared\Kernel\Transfer\Exception\NullValueException;
use Spryker\Zed\SalesServicePoint\Communication\Plugin\Sales\ServicePointSalesOrderItemCollectionPostUpdatePlugin;
use SprykerTest\Zed\SalesServicePoint\SalesServicePointCommunicationTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group SalesServicePoint
 * @group Communication
 * @group Plugin
 * @group Sales
 * @group ServicePointSalesOrderItemCollectionPostUpdatePluginTest
 * Add your own group annotations below this line
 */
class ServicePointSalesOrderItemCollectionPostUpdatePluginTest extends Unit
{
    /**
     * @var string
     */
    protected const DEFAULT_OMS_PROCESS_NAME = 'Test01';

    /**
     * @var \SprykerTest\Zed\SalesServicePoint\SalesServicePointCommunicationTester
     */
    protected SalesServicePointCommunicationTester $tester;

    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->configureTestStateMachine([static::DEFAULT_OMS_PROCESS_NAME]);
        $this->tester->ensureSalesOrderItemServicePointDatabaseTableIsEmpty();
    }

    public function testShouldNotUpdateAnyServicePoints(): void
    {
        // Arrange
        $quoteTransfer = $this->createOrder();
        $salesOrderItemCollectionResponseTransfer = (new SalesOrderItemCollectionResponseTransfer())
            ->setItems($quoteTransfer->getItems());

        // Act
        (new ServicePointSalesOrderItemCollectionPostUpdatePlugin())->postUpdate($salesOrderItemCollectionResponseTransfer);

        // Assert
        $this->assertSame(0, $this->tester->getSalesOrderItemServicePointQuery()->count());
    }

    public function testShouldCreateServicePoint(): void
    {
        // Arrange
        $servicePointTransfer = (new ServicePointBuilder())->build();
        $quoteTransfer = $this->createOrder($servicePointTransfer);

        $salesOrderItemCollectionResponseTransfer = (new SalesOrderItemCollectionResponseTransfer())
            ->setItems($quoteTransfer->getItems());

        // Act
        (new ServicePointSalesOrderItemCollectionPostUpdatePlugin())->postUpdate($salesOrderItemCollectionResponseTransfer);

        // Assert
        $this->assertSalesOrderItemServicePointEntity($quoteTransfer);
    }

    public function testShouldUpdateServicePoint(): void
    {
        // Arrange
        $servicePointTransfer = (new ServicePointBuilder())->build();
        $quoteTransfer = $this->createOrder($servicePointTransfer);

        $this->tester->getFacade()->saveSalesOrderItemServicePointsFromQuote($quoteTransfer);
        $servicePointTransfer->setKey('new-key')->setName('new-name');

        $salesOrderItemCollectionResponseTransfer = (new SalesOrderItemCollectionResponseTransfer())
            ->setItems($quoteTransfer->getItems());

        // Act
        (new ServicePointSalesOrderItemCollectionPostUpdatePlugin())->postUpdate($salesOrderItemCollectionResponseTransfer);

        // Assert
        $this->assertSalesOrderItemServicePointEntity($quoteTransfer);
    }

    public function testShouldDeleteServicePoint(): void
    {
        // Arrange
        $servicePointTransfer = (new ServicePointBuilder())->build();
        $quoteTransfer = $this->createOrder($servicePointTransfer);

        $this->tester->getFacade()->saveSalesOrderItemServicePointsFromQuote($quoteTransfer);
        $servicePointTransfer->setKey('new-key')->setName('new-name');
        $quoteTransfer->getItems()->offsetGet(0)->setServicePoint(null);

        $salesOrderItemCollectionResponseTransfer = (new SalesOrderItemCollectionResponseTransfer())
            ->setItems($quoteTransfer->getItems());

        // Act
        (new ServicePointSalesOrderItemCollectionPostUpdatePlugin())->postUpdate($salesOrderItemCollectionResponseTransfer);

        // Assert
        $this->assertNull($this->tester->findSalesOrderItemServicePoint($quoteTransfer->getItems()->offsetGet(0)->getIdSalesOrderItemOrFail()));
    }

    public function testShouldThrowNullValueExceptionWhenIdSalesOrderItemIsNotSet(): void
    {
        // Arrange
        $servicePointTransfer = (new ServicePointBuilder())->build();
        $quoteTransfer = $this->createOrder($servicePointTransfer);

        $quoteTransfer->getItems()->offsetGet(0)->setIdSalesOrderItem(null);

        $salesOrderItemCollectionResponseTransfer = (new SalesOrderItemCollectionResponseTransfer())
            ->setItems($quoteTransfer->getItems());

        // Assert
        $this->expectException(NullValueException::class);
        $this->expectExceptionMessage(sprintf('Property "idSalesOrderItem" of transfer `%s` is null.', ItemTransfer::class));

        // Act
        (new ServicePointSalesOrderItemCollectionPostUpdatePlugin())->postUpdate($salesOrderItemCollectionResponseTransfer);
    }

    public function testShouldThrowNullValueExceptionWhenServicePointKeyIsNotSet(): void
    {
        // Arrange
        $servicePointTransfer = (new ServicePointBuilder())->build();
        $quoteTransfer = $this->createOrder($servicePointTransfer);

        $quoteTransfer->getItems()->offsetGet(0)->getServicePoint()->setKey(null);

        $salesOrderItemCollectionResponseTransfer = (new SalesOrderItemCollectionResponseTransfer())
            ->setItems($quoteTransfer->getItems());

        // Assert
        $this->expectException(NullValueException::class);
        $this->expectExceptionMessage(sprintf('Property "key" of transfer `%s` is null.', ServicePointTransfer::class));

        // Act
        (new ServicePointSalesOrderItemCollectionPostUpdatePlugin())->postUpdate($salesOrderItemCollectionResponseTransfer);
    }

    public function testShouldThrowNullValueExceptionWhenServicePointNameIsNotSet(): void
    {
        // Arrange
        $servicePointTransfer = (new ServicePointBuilder())->build();
        $quoteTransfer = $this->createOrder($servicePointTransfer);

        $quoteTransfer->getItems()->offsetGet(0)->getServicePoint()->setName(null);

        $salesOrderItemCollectionResponseTransfer = (new SalesOrderItemCollectionResponseTransfer())
            ->setItems($quoteTransfer->getItems());

        // Assert
        $this->expectException(NullValueException::class);
        $this->expectExceptionMessage(sprintf('Property "name" of transfer `%s` is null.', ServicePointTransfer::class));

        // Act
        (new ServicePointSalesOrderItemCollectionPostUpdatePlugin())->postUpdate($salesOrderItemCollectionResponseTransfer);
    }

    protected function assertSalesOrderItemServicePointEntity(QuoteTransfer $quoteTransfer): void
    {
        $itemTransfer = $quoteTransfer->getItems()->offsetGet(0);
        $idSalesOrderItem = $itemTransfer->getIdSalesOrderItemOrFail();
        $servicePointTransfer = $itemTransfer->getServicePointOrFail();

        $salesOrderItemServicePointEntity = $this->tester->findSalesOrderItemServicePoint($idSalesOrderItem);

        $this->assertSame($idSalesOrderItem, $salesOrderItemServicePointEntity->getFkSalesOrderItem());
        $this->assertSame($servicePointTransfer->getKey(), $salesOrderItemServicePointEntity->getKey());
        $this->assertSame($servicePointTransfer->getName(), $salesOrderItemServicePointEntity->getName());
    }

    protected function createOrder(?ServicePointTransfer $servicePointTransfer = null): QuoteTransfer
    {
        $quoteTransfer = (new QuoteBuilder())
            ->withItem([
                ItemTransfer::SERVICE_POINT => $servicePointTransfer,
            ])
            ->withAnotherItem()
            ->withBillingAddress()
            ->withTotals()
            ->withCustomer()
            ->withCurrency()
            ->build();

        $storeTransfer = $this->tester->haveStore([StoreTransfer::NAME => 'DE']);
        $quoteTransfer->setStore($storeTransfer);

        $saveOrderTransfer = $this->tester->haveOrderFromQuote($quoteTransfer, static::DEFAULT_OMS_PROCESS_NAME);
        $quoteTransfer->setItems($saveOrderTransfer->getOrderItems());

        return $quoteTransfer;
    }
}
