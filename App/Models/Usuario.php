<?php

    namespace App\Models;
    use MF\Model\Model;

    class Usuario extends Model {

        private $id;
        private $nome;
        private $email;
        private $senha;

        public function __get($atributo) {
            return $this->$atributo;
        }

        public function __set($atributo, $valor) {
            $this->$atributo = $valor;
        }

        //salvar cadastro

        public function salvar() {
            $query = "INSERT INTO usuarios(nome, email, senha)VALUES(:nome, :email, :senha)";
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':nome', $this->__get('nome'));
            $stmt->bindValue(':email', $this->__get('email'));
            $stmt->bindValue(':senha', $this->__get('senha')); //md5() -> hash de 32 caracteres
            $stmt->execute();

            return $this;
        }

        //validar se um cadastro pode ser feito

        public function validarCadastro() {

            $nomeValido  = strlen($this->__get('nome')) >= 3;
            $emailValido = strlen($this->__get('email')) >= 3;
            $senhaValida = strlen($this->__get('senha')) >= 3;

            return $nomeValido && $emailValido && $senhaValida;

        }

        //recuperar um usuário por e-mail

        public function getUsuarioPorEmail() {
            $query = 'SELECT nome, email FROM usuarios WHERE email = :email';
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':email', $this->__get('email'));
            $stmt->execute();

            return $stmt->fetch(\PDO::FETCH_ASSOC);
        }

        public function autenticar() {
            $query = 'SELECT id, nome, email FROM usuarios WHERE email = :email AND senha = :senha';
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':email', $this->__get('email'));
            $stmt->bindValue(':senha', $this->__get('senha'));
            $stmt->execute();

            $usuario = $stmt->fetch(\PDO::FETCH_ASSOC);

            if (!empty($usuario)) {
                $this->__set('id', $usuario['id']);
                $this->__set('nome', $usuario['nome']);
            }

            return $this;
        }

        public function getAll() {
            $query = "SELECT u.id, u.nome, u.email, 
                        (
                            SELECT count(*) 
                            FROM usuarios_seguidores AS us 
                            WHERE us.id_usuario = :id_usuario 
                            AND us.id_usuario_seguindo = u.id
                        ) AS seguindo_sn
                    FROM usuarios AS u
                    WHERE u.nome LIKE :nome AND u.id != :id_usuario";

            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':nome', '%' . $this->__get('nome') . '%');
            $stmt->bindValue(':id_usuario', $this->__get('id')); // <- Corrigido aqui
            $stmt->execute();

            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        }


        public function seguirUsuario($id_usuario_seguindo) {
            $query = "INSERT INTO usuarios_seguidores(id_usuario, id_usuario_seguindo)
                    VALUES(:id_usuario, :id_usuario_seguindo)";
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':id_usuario', $this->__get('id'));
            $stmt->bindValue(':id_usuario_seguindo', $id_usuario_seguindo);
            $stmt->execute();

            return true;
        }

        public function deixarSeguirUsuario($id_usuario_seguindo) {
            $query = "DELETE FROM usuarios_seguidores 
                    WHERE id_usuario = :id_usuario
                    AND id_usuario_seguindo = :id_usuario_seguindo";
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':id_usuario', $this->__get('id'));
            $stmt->bindValue(':id_usuario_seguindo', $id_usuario_seguindo);
            $stmt->execute();

            return true;
        }
        //Informaçãoes do Usuário
        public function getInforUsuario() {
            $query = "SELECT nome FROM usuarios WHERE id = :id_usuario";
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':id_usuario', $this->__get('id'));
            $stmt->execute();

            return $stmt->fetch(\PDO::FETCH_ASSOC);
        }

        //Total de tweets
        public function getTotalTweets() {
            $query = "SELECT count(*) as total_tweets FROM tweets WHERE id_usuario = :id_usuario";
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':id_usuario', $this->__get('id'));
            $stmt->execute();

            return $stmt->fetch(\PDO::FETCH_ASSOC);
        }

        //Total de usuários que estamos seguindo
        public function getTotalSeguindo() {
            $query = "SELECT count(*) as total_seguindo FROM usuarios_seguidores WHERE id_usuario = :id_usuario";
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':id_usuario', $this->__get('id'));
            $stmt->execute();

            return $stmt->fetch(\PDO::FETCH_ASSOC);
        }

        //Total de seguidores            
        public function getTotalSeguidores() {
            $query = "SELECT count(*) as total_seguidores FROM usuarios_seguidores WHERE id_usuario_seguindo = :id_usuario";
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':id_usuario', $this->__get('id'));
            $stmt->execute();

            return $stmt->fetch(\PDO::FETCH_ASSOC);
        }

    }

?>