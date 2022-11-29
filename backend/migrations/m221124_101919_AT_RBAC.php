<?php

use yii\db\Migration;

/**
 * Class m221124_101919_AT_RBAC
 */
class m221124_101919_AT_RBAC extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $authManager  = Yii::$app->getAuthManager();
        $this->db     = $authManager->db;
        $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ENGINE=InnoDB';

        $this->createTable($authManager->ruleTable, [
            'name'       => $this->string(255)->notNull(),
            'data'       => $this->binary(),
            'created_at' => $this->integer()->unsigned()->notNull()->defaultValue(0),
            'updated_at' => $this->integer()->unsigned()->notNull()->defaultValue(0),
            'PRIMARY KEY ([[name]])',
        ], $tableOptions);

        $this->createTable($authManager->itemTable, [
            'name'        => $this->string(255)->notNull(),
            'type'        => $this->smallInteger()->notNull(),
            'description' => $this->text(),
            'rule_name'   => $this->string(255),
            'data'        => $this->binary(),
            'created_at'  => $this->integer()->unsigned()->notNull()->defaultValue(0),
            'updated_at'  => $this->integer()->unsigned()->notNull()->defaultValue(0),
            'PRIMARY KEY ([[name]])',
            'FOREIGN KEY ([[rule_name]]) REFERENCES ' . $authManager->ruleTable . ' ([[name]])' .
            $this->buildFkClause('ON DELETE SET NULL', 'ON UPDATE CASCADE'),
        ], $tableOptions);
        $this->createIndex('auth_item_type_idx', $authManager->itemTable, 'type');

        $this->createTable($authManager->itemChildTable, [
            'parent' => $this->string(255)->notNull(),
            'child'  => $this->string(255)->notNull(),
            'PRIMARY KEY ([[parent]], [[child]])',
            'FOREIGN KEY ([[parent]]) REFERENCES ' . $authManager->itemTable . ' ([[name]])' .
            $this->buildFkClause('ON DELETE CASCADE', 'ON UPDATE CASCADE'),
            'FOREIGN KEY ([[child]]) REFERENCES ' . $authManager->itemTable . ' ([[name]])' .
            $this->buildFkClause('ON DELETE CASCADE', 'ON UPDATE CASCADE'),
        ], $tableOptions);


        $this->createTable($authManager->assignmentTable, [
            'item_name'  => $this->string(255)->notNull(),
            'user_id'    => $this->string(255)->notNull(),
            'created_at' => $this->integer()->unsigned()->notNull()->defaultValue(0),
            'PRIMARY KEY ([[item_name]], [[user_id]])',
            'FOREIGN KEY ([[item_name]]) REFERENCES ' . $authManager->itemTable . ' ([[name]])' .
            $this->buildFkClause('ON DELETE CASCADE', 'ON UPDATE CASCADE'),
        ], $tableOptions);
        $this->createIndex('auth_assignment_user_id_idx', $authManager->assignmentTable, 'user_id');


    }

    /**
     * @return bool
     */
    protected function isMSSQL(): bool
    {
        return $this->db->driverName === 'mssql' || $this->db->driverName === 'sqlsrv' || $this->db->driverName === 'dblib';
    }

    protected function isOracle(): bool
    {
        return $this->db->driverName === 'oci' || $this->db->driverName === 'oci8';
    }

    /**
     * @param string $delete
     * @param string $update
     *
     * @return string
     */
    protected function buildFkClause(string $delete = '', string $update = ''): string
    {
        if ($this->isMSSQL()) {
            return '';
        }

        if ($this->isOracle()) {
            return ' ' . $delete;
        }

        return implode(' ', ['', $delete, $update]);
    }


    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $authManager  = Yii::$app->getAuthManager();
        $this->db     = $authManager->db;
        $this->dropTable($authManager->assignmentTable);
        $this->dropTable($authManager->itemChildTable);
        $this->dropTable($authManager->itemTable);
        $this->dropTable($authManager->ruleTable);

        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m221124_101919_AT_RBAC cannot be reverted.\n";

        return false;
    }
    */
}
