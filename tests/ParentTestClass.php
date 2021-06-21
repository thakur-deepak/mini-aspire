<?php
namespace Tests;

use Tests\TestCase;

class ParentTestClass extends TestCase
{
    public static $is_migrated = false;

    public static function setUpBeforeClass():void
    {
        if (self::$is_migrated === false) {
            exec('php artisan migrate:refresh');
            exec('php artisan db:seed');    
            self::$is_migrated = true;
        }    
    }

    public function tearDown(): void
    {
        \DB::connection()->setPdo(null);
        parent::tearDown();
    }
}
