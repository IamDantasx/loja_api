<?php
declare(strict_types=1);

use Cake\Routing\Route\DashedRoute;
use Cake\Routing\RouteBuilder;

return function (RouteBuilder $routes): void {
    $routes->setRouteClass(DashedRoute::class);

    // Rotas da API: http://localhost:8765/api/...
    $routes->scope('/api', function (RouteBuilder $builder): void {
        $builder->setExtensions(['json']);

        $builder->resources('Produtos');
        $builder->resources('Categorias');
        $builder->resources('Marcas');
        $builder->resources('Users');
    });

    // Rotas padrão do CakePHP (página inicial etc.)
    $routes->scope('/', function (RouteBuilder $builder): void {
        $builder->connect('/', ['controller' => 'Pages', 'action' => 'display', 'home']);
        $builder->connect('/pages/*', 'Pages::display');
        $builder->fallbacks();
    });
};
