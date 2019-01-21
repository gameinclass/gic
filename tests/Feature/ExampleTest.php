<?php
/**
 * Created by PhpStorm.
 * User: jorge
 * Date: 21/01/19
 * Time: 17:17
 */

namespace Tests\Feature;

use Tests\TestCase;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class ExampleTest extends TestCase
{
    /**
     * A basic functional test example.
     *
     * @return void
     */
    public function test_basic_example()
    {
        $this->json('POST', '/user', ['name' => 'Sally'])
            ->seeJson([
                'created' => true,
            ]);
    }

}