<?php

namespace HnhDigital\HelperCollection\Migrations;

use DB;

trait UuidTrait
{
    /**
     * Create trigger.
     *
     * @param string $table
     * @param string $column
     *
     * @return void
     */
    public function createUuidTrigger($table, $column)
    {
        DB::connection($this->connection)->unprepared(
            'CREATE DEFINER = CURRENT_USER TRIGGER `'.$table.'_BEFORE_INSERT` BEFORE INSERT ON `'.$table.'` FOR EACH ROW '.
            'BEGIN '.
            'IF new.'.$column.'=\'\' OR new.'.$column.' IS NULL THEN '.
            'SET new.'.$column.' = UUID(); '.
            'END IF; '.
            'END'
        );
    }

    /**
     * Drop trigger.
     *
     * @param string $table
     * @param string $column
     *
     * @return void
     */
    public function dropUuidTrigger($table)
    {
        DB::connection($this->connection)->unprepared('DROP TRIGGER `'.$table.'_BEFORE_INSERT`');
    }
}
