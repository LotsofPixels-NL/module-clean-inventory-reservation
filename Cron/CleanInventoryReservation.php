<?php

declare(strict_types=1);

namespace  Lotsofpixels\CleanInventoryReservation\Cron;

use Psr\Log\LoggerInterface;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\App\Config\ScopeConfigInterface;

/**
 *
 */
class CleanInventoryReservation
{

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var ResourceConnection
     */
    private $resourceConnection;

    /**
     * @param LoggerInterface $logger
     * @param ResourceConnection $resourceConnection
     */

    private $storeConfig;

    /**
     * @param LoggerInterface $logger
     * @param ResourceConnection $resourceConnection
     * @param ScopeConfigInterface $ScopeConfig
     */
    public function __construct(
        LoggerInterface $logger,
        ResourceConnection  $resourceConnection,
        ScopeConfigInterface $storeConfig

    )
    {
        $this->logger =$logger;
        $this->resourceConnection = $resourceConnection;
        $this->storeConfig = $storeConfig;
    }

    /**
     * @return void
     */
    public function execute() {
        if ($this->storeConfig->getValue('cleaninventoryreservation/general/enabled')) {
            $connection = $this->resourceConnection->getConnection();
            $table = $connection->getTableName('inventory_reservation');
            $query = "SELECT COUNT(*) AS NumberOfEntries FROM " . $table;
            $result = $connection->fetchOne($query);
            $connection->truncateTable('inventory_reservation');
            $this->logger->info('Deleted entries from inventory_reservation: ' . $result);
        }
    }
}
