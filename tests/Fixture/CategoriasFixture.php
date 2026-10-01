<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * CategoriasFixture
 */
class CategoriasFixture extends TestFixture
{
    /**
     * Init method
     *
     * @return void
     */
    public function init(): void
    {
        $this->records = [
            [
                'id' => 1,
                'nome' => 'Lorem ipsum dolor sit amet',
                'created' => '2026-10-01 03:08:57',
                'modified' => '2026-10-01 03:08:57',
                'deleted' => '2026-10-01 03:08:57',
            ],
        ];
        parent::init();
    }
}
