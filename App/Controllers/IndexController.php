<?php

    namespace App\Controllers;

    //os recursos do miniframework
    use MF\Controller\Action;
    use MF\Model\Container;


    class IndexController extends Action {

        public function index() {

            $this->view->login = isset($_GET['login']) ? $_GET['login'] : '';
            $this->render('index', 'layout');
        }

        public function inscreverse() {
            $this->view->usuario = [
                'nome' => '',
                'email' => '',
                'senha' => ''
            ];

            $this->view->erroCadastro = false;
            $this->render('inscreverse', 'layout');
        }

        public function registrar() {

            $usuario = Container::getModel('Usuario');

            $usuario->__set('nome', $_POST['nome']);
            $usuario->__set('email', $_POST['email']);
            $usuario->__set('senha', md5($_POST['senha']));

            if($usuario->validarCadastro() && empty($usuario->getUsuarioPorEmail())) {

                $usuario->salvar();
                $this->render('Cadastro');
                
            } else {
                $this->view->usuario = [
                    'nome' => $_POST['nome'],
                    'email' => $_POST['email'],
                    'senha' => $_POST['senha']
                ];

                $this->view->erroCadastro = true;
                $this->render('inscreverse');
            }
        }
    }
?>
