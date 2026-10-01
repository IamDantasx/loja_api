<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * ProdutosFixture
 */
class ProdutosFixture extends TestFixture
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
                'valor' => 1.5,
                'peso' => 1.5,
                'unidMedida' => 'Lor',
                'created' => '2026-10-01 01:24:07',
                'modified' => '2026-10-01 01:24:07',
                'deleted' => '2026-10-01 01:24:07',
            ],
        ];
        parent::init();
    }
}
