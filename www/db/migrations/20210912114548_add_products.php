<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class AddProducts extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change(): void
    {
        $table = $this->table('categories', ['id' => true, 'primary_key' => ['id']]);
        $table->addColumn('name', 'string')
            ->save();

        $table = $this->table('products', ['id' => true, 'primary_key' => ['id']]);
        $table
            ->addColumn('sku', 'string')
            ->addColumn('name', 'string')
            ->addColumn('category_id', 'integer')
            ->addForeignKey('category_id', 'categories', 'id', ['delete'=> 'RESTRICT', 'update' => 'NO_ACTION'])
            ->save();

        $table = $this->table('prices', ['id' => true, 'primary_key' => ['id']]);
        $table
            ->addColumn('product_id', 'integer')
            ->addColumn('amount', 'integer')
            ->addColumn('currency', 'string')
            ->addColumn('start_at', 'date')
            ->addColumn('end_at', 'date')
            ->addForeignKey('product_id', 'products', 'id', ['delete'=> 'RESTRICT', 'update' => 'NO_ACTION'])
            ->save();

        $table = $this->table('discounts', ['id' => true, 'primary_key' => ['id']]);
        $table
            ->addColumn('product_id', 'integer', ['null' => true])
            ->addColumn('category_id', 'integer', ['null' => true])
            ->addColumn('percentage', 'integer')
            ->addColumn('start_at', 'date')
            ->addColumn('end_at', 'date')
            ->addForeignKey('product_id', 'products', 'id', ['delete'=> 'RESTRICT', 'update' => 'NO_ACTION'])
            ->addForeignKey('category_id', 'categories', 'id', ['delete'=> 'RESTRICT', 'update' => 'NO_ACTION'])
            ->save();
    }
}
