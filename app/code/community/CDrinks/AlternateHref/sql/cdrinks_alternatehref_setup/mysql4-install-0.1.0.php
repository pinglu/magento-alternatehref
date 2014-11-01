<?php
$installer = $this;

$installer->startSetup();

$installer->run("
ALTER TABLE
    {$this->getTable('cms_page')}
ADD COLUMN
    `alternate_href_common_identifier` VARCHAR(100) NOT NULL DEFAULT '' AFTER `identifier`;
");

$installer->endSetup();
