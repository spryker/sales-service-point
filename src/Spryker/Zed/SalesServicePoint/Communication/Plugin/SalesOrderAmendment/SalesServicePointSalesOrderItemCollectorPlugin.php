<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesServicePoint\Communication\Plugin\SalesOrderAmendment;

use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\SalesOrderAmendmentItemCollectionTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\SalesOrderAmendmentExtension\Dependency\Plugin\SalesOrderItemCollectorPluginInterface;

/**
 * @method \Spryker\Zed\SalesServicePoint\Business\SalesServicePointFacadeInterface getFacade()
 * @method \Spryker\Zed\SalesServicePoint\Business\SalesServicePointBusinessFactory getBusinessFactory()
 */
class SalesServicePointSalesOrderItemCollectorPlugin extends AbstractPlugin implements SalesOrderItemCollectorPluginInterface
{
    /**
     * {@inheritDoc}
     * - Requires `ItemTransfer.idSalesOrderItem` to be set for each item in `OrderTransfer.items`.
     * - Requires `ItemTransfer.idSalesOrderItem` to be set for each item in `SalesOrderAmendmentItemCollectionTransfer.itemsToSkip`.
     * - Iterates over `SalesOrderAmendmentItemCollectionTransfer.itemsToSkip` and compares item's service points with the corresponding item's service points from `OrderTransfer.items`.
     * - If service points are different, adds items to `SalesOrderAmendmentItemCollectionTransfer.itemsToUpdate` and removes from `SalesOrderAmendmentItemCollectionTransfer.itemsToSkip`.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Generated\Shared\Transfer\SalesOrderAmendmentItemCollectionTransfer $salesOrderAmendmentItemCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderAmendmentItemCollectionTransfer
     */
    public function collect(
        OrderTransfer $orderTransfer,
        SalesOrderAmendmentItemCollectionTransfer $salesOrderAmendmentItemCollectionTransfer
    ): SalesOrderAmendmentItemCollectionTransfer {
        return $this->getBusinessFactory()
            ->createSalesServicePointSalesOrderItemCollector()
            ->collect($orderTransfer, $salesOrderAmendmentItemCollectionTransfer);
    }
}
