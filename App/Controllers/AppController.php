<?php

    namespace App\Controllers;

    //os recursos do miniframework
    use MF\Controller\Action;
    use MF\Model\Container;


    class AppController extends Action {

        public function timeline() {

            $this->validaAutenticacao();
        
                //recuperar tweets
                $tweet = Container::getModel('Tweet');
                $tweet->__set('id_usuario', $_SESSION['id']);

                //variaveis de páginação
                $total_registros_pagina = 10; //limit
                $pagina = isset($_GET['pagina']) && is_numeric($_GET['pagina']) ? (int) $_GET['pagina'] : 1;
                $pagina = max($pagina, 1); // garante que não seja menor que 1
                $deslocamento = ($pagina - 1) * $total_registros_pagina; //offset


                //$tweets = $tweet->getAll();
                $tweets = $tweet->getPorPagina($total_registros_pagina, $deslocamento);
                $total_tweets = $tweet->getTotalRegistros();

                $this->view->total_de_paginas = ceil($total_tweets['total'] / $total_registros_pagina);
                $this->view->pagina_ativa = $pagina;
                
                $this->view->tweets = $tweets;

                $usuario = Container::getModel('Usuario');
                $usuario->__set('id', $_SESSION['id']);

                $this->view->infor_usuario = $usuario->getInforUsuario();
                $this->view->total_tweets = $usuario->getTotalTweets();
                $this->view->total_seguindo = $usuario->getTotalSeguindo();
                $this->view->total_seguidores = $usuario->getTotalSeguidores();
                
                $this->render('timeline');

        }

        public function tweet() {

            $this->validaAutenticacao();

                $tweet = Container::getModel('Tweet');

                $tweet->__set('tweet', $_POST['tweet']);
                $tweet->__set('id_usuario', $_SESSION['id']);

                $tweet->salvar();

                header('Location: /timeline');
        }

        public function validaAutenticacao() {

            session_start();
            
            if (empty($_SESSION['id']) || empty($_SESSION['nome'])) {
                header('Location:/?login=erro');
                exit;
            }
        }

        public function quemSequir() {

            $this->validaAutenticacao();

            $pesquisarPor = isset($_GET['pesquisarPor']) ? $_GET['pesquisarPor'] : '';

            $usuarios = [];

            if($pesquisarPor != '') {
                
                $usuario = Container::getModel('Usuario');
                $usuario->__set('nome', $pesquisarPor);
                $usuario->__set('id', $_SESSION['id']);
                $usuarios = $usuario->getAll();
            }

            $this->view->usuarios = $usuarios;

                $usuario = Container::getModel('Usuario');
                $usuario->__set('id', $_SESSION['id']);

                $this->view->infor_usuario = $usuario->getInforUsuario();
                $this->view->total_tweets = $usuario->getTotalTweets();
                $this->view->total_seguindo = $usuario->getTotalSeguindo();
                $this->view->total_seguidores = $usuario->getTotalSeguidores();

            $this->render('quemSeguir');
        }

        public function acao() {

            $this->validaAutenticacao();

            $acao = isset($_GET['acao']) ? $_GET['acao'] : '';
            $id_usuario_seguindo = isset($_GET['id_usuario']) ? $_GET['id_usuario'] : '';
        
            $usuario = Container::getModel('Usuario');
            $usuario->__set('id', $_SESSION['id']);

            if($acao == 'seguir') {

                $usuario->seguirUsuario($id_usuario_seguindo);

            } else if($acao == 'deixar_de_seguir') {

                $usuario->deixarSeguirUsuario($id_usuario_seguindo);

            }

            header('Location: /quem_sequir');
        }

        public function remover_tweet() {

            $tweet_remover = Container::getModel('Tweet');
            $tweet_remover->__set('id', $_POST['id']);
            $tweet_remover->remover();

            header('Location: /timeline');

        }

    }

?>