<?php

    namespace App\Controllers;

    //os recursos do miniframework
    use MF\Controller\Action;
    use MF\Model\Container;


    class IndexController extends Action {

        public function index() {
            $this->render('index', 'layout');
        }

        public function inscreverse() {
            $this->render('inscreverse', 'layout');
        }

        public function registrar() {

            //receber os dados so formulário

            $usuario = Container::getModel('Usuario');

            $usuario->__set('nome', $_POST['nome']);
            $usuario->__set('email', $_POST['email']);
            $usuario->__set('senha', $_POST['senha']);

            $usuario->salvar();
            //sucesso


            //erro
        }
    }
?>
