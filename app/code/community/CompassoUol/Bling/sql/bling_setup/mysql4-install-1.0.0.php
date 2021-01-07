<?php

try {
    $installer = $this;
    $installer->startSetup();

    $installer->run("
        CREATE TABLE IF NOT EXISTS `bling_orders` (
            `id` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
            `content` varchar(1000) NOT NULL,
            `order_id` int(10) unsigned NOT NULL,
            `invoiced` int(1) NOT NULL,
            `created_at` timestamp NOT NULL ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (`order_id`) REFERENCES `sales_flat_order` (`entity_id`)
        );
    ");

} catch (Exception $e) {
    Mage::log($e->getMessage(), null, __FILE__ . '_setup_exception.log', true);
} finally {
    $installer->endSetup();
}
