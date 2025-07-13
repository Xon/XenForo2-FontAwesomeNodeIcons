<?php

declare(strict_types=1);

namespace SV\FontAwesomeNodeIcons;

use SV\StandardLib\InstallerHelper;
use XF\AddOn\AbstractSetup;
use XF\AddOn\StepRunnerInstallTrait;
use XF\AddOn\StepRunnerUninstallTrait;
use XF\AddOn\StepRunnerUpgradeTrait;
use XF\Db\Schema\Alter;

class Setup extends AbstractSetup
{
    use InstallerHelper;
    use StepRunnerInstallTrait;
    use StepRunnerUpgradeTrait;
    use StepRunnerUninstallTrait;

    public function installStep1(): void
    {
        $this->applySchemaChanges();
    }

    /**
     * Drops add-on tables.
     */
    public function uninstallStep1(): void
    {
        $sm = $this->schemaManager();

        foreach ($this->getTables() as $tableName => $callback)
        {
            $sm->dropTable($tableName);
        }

        foreach ($this->getRemoveAlterTables() as $tableName => $callback)
        {
            if ($sm->tableExists($tableName))
            {
                $sm->alterTable($tableName, $callback);
            }
        }
    }

    protected function applySchemaChanges(): void
    {
        $sm = $this->schemaManager();

        foreach ($this->getTables() as $tableName => $closure)
        {
            $sm->createTable($tableName, $closure);
            $sm->alterTable($tableName, $closure);
        }

        foreach ($this->getAlterTables() as $tableName => $callback)
        {
            if ($sm->tableExists($tableName))
            {
                $sm->alterTable($tableName, $callback);
            }
        }
    }

    protected function getTables(): array
    {
        return [
        ];
    }

    private function getAlterTables(): array
    {
        return [
            'xf_node' => function (Alter $table) {
                $this->addOrChangeColumn($table, 'fa_node_icon', 'varchar', '100')->nullable()->setDefault(null);
            },
        ];
    }

    private function getRemoveAlterTables(): array
    {
        return [
            'xf_node' => function (Alter $table) {
                $table->dropColumns(['fa_node_icon']);
            },
        ];
    }
}
