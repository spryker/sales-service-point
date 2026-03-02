<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesServicePoint\Business\Deleter;

use Generated\Shared\Transfer\SalesOrderItemServicePointCollectionDeleteCriteriaTransfer;
use Generated\Shared\Transfer\SalesOrderItemServicePointCollectionResponseTransfer;

interface SalesOrderItemServicePointDeleterInterface
{
    public function deleteSalesOrderItemServicePointCollection(
        SalesOrderItemServicePointCollectionDeleteCriteriaTransfer $salesOrderItemServicePointCollectionDeleteCriteriaTransfer
    ): SalesOrderItemServicePointCollectionResponseTransfer;
}
