<?php

namespace App;

use MF\Init\Bootstrap;

class Route extends Bootstrap {

    protected function initRoutes() {

        $routes['home'] = [
            'route' => '/',
            'controller' => 'IndexController',
            'action' => 'index'
        ];

        $routes['inscreverse'] = [
            'route' => '/inscreverse',
            'controller' => 'IndexController',
            'action' => 'inscreverse'
        ];

        $routes['registrar'] = [
            'route' => '/registrar',
            'controller' => 'IndexController',
            'action' => 'registrar'
        ];

        $routes['autenticar'] = [
            'route' => '/autenticar',
            'controller' => 'AuthController',
            'action' => 'autenticar'
        ];

        $routes['timeline'] = [
            'route' => '/timeline',
            'controller' => 'AppController',
            'action' => 'timeline'
        ];

        $routes['sair'] = [
            'route' => '/sair',
            'controller' => 'AuthController',
            'action' => 'sair'
        ];

        $routes['tweet'] = [
            'route' => '/tweet',
            'controller' => 'AppController',
            'action' => 'tweet'
        ];

        $routes['quem_seguir'] = [
            'route' => '/quem_sequir',
            'controller' => 'AppController',
            'action' => 'quemSequir'
        ];

        $routes['acao'] = [
            'route' => '/acao',
            'controller' => 'AppController',
            'action' => 'acao'
        ];

        $this->setRoutes($routes);
    }
}
?>