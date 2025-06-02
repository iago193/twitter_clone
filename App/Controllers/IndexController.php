<?php

    namespace App\Controllers;

    use MF\Controller\Action;


    class IndexController extends Action {

        public function index() {
            $this->render('index', 'layout');
        }

        public function inscreverse() {
            $this->render('inscreverse', 'layout');
        }

        public function registrar() {

            echo '<pre>';
            print_r($_POST);
            echo '</pre>';

            //receber os dados so formulário

            $usuario = new Usuario();


            //sucesso


            //erro
        }
    }
?>
