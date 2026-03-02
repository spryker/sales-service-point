<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesServicePoint\Persistence;

use Generated\Shared\Transfer\SalesOrderItemServicePointCollectionTransfer;
use Generated\Shared\Transfer\SalesOrderItemServicePointCriteriaTransfer;
use Orm\Zed\SalesServicePoint\Persistence\SpySalesOrderItemServicePointQuery;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\SalesServicePoint\Persistence\SalesServicePointPersistenceFactory getFactory()
 */
class SalesServicePointRepository extends AbstractRepository implements SalesServicePointRepositoryInterface
{
    public function getSalesOrderItemServicePointCollection(
        SalesOrderItemServicePointCriteriaTransfer $salesOrderItemServicePointCriteriaTransfer
    ): SalesOrderItemServicePointCollectionTransfer {
        $salesOrderItemServicePointQuery = $this->getFactory()->getSalesOrderItemServicePointQuery();
        $salesOrderItemServicePointQuery = $this->applySalesOrderItemServicePointFilters(
            $salesOrderItemServicePointQuery,
            $salesOrderItemServicePointCriteriaTransfer,
        );

        return $this->getFactory()
            ->createSalesServicePointMapper()
            ->mapSalesOrderItemServicePointEntityCollectionToSalesOrderItemServicePointCollectionTransfer(
                $salesOrderItemServicePointQuery->find(),
                new SalesOrderItemServicePointCollectionTransfer(),
            );
    }

    protected function applySalesOrderItemServicePointFilters(
        SpySalesOrderItemServicePointQuery $salesOrderItemServicePointQuery,
        SalesOrderItemServicePointCriteriaTransfer $salesOrderItemServicePointCriteriaTransfer
    ): SpySalesOrderItemServicePointQuery {
        $salesOrderItemServicePointConditionsTransfer = $salesOrderItemServicePointCriteriaTransfer->getSalesOrderItemServicePointConditions();

        if (!$salesOrderItemServicePointConditionsTransfer) {
            return $salesOrderItemServicePointQuery;
        }

        if ($salesOrderItemServicePointConditionsTransfer->getSalesOrderItemIds()) {
            $salesOrderItemServicePointQuery->filterByFkSalesOrderItem_In($salesOrderItemServicePointConditionsTransfer->getSalesOrderItemIds());
        }

        return $salesOrderItemServicePointQuery;
    }
}
