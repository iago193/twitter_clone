<?php

    namespace App\Controllers;

    use MF\Controller\Action;
    use App\Models\Info;


    class IndexController extends Action {

        public function index() {

            $this->render('index', 'layout');
        }

        public function inscreverse() {

            $this->render('inscreverse', 'layout');
        }
    }
?>
