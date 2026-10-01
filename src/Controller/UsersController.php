<?php

namespace App\Controller;

use App\Controller\AppController;
use Cake\Datasource\Exception\RecordNotFoundException;
use Exception;

class UsersController extends AppController {

    public function add() {
        // Função add chamado pela rota adicionar
        if ($this->request->is('post')) {
            $usersTable = $this->fetchTable('Users');
            $novoUsuario = $usersTable->newEmptyEntity();
            $form = $this->request->getData();
            $novoUsuario = $usersTable->patchEntity($novoUsuario, $form);


            if ($usersTable->save($novoUsuario)) {
                $result = 'Usuário foi cadastrado com sucesso!';
                $statusCode = 200;
            } else {
                $result = 'Erro ao cadastrar usuário!';
                $statusCode = 400;
            }
        } else {
            $result = 'Formulário não foi enviado';
            $statusCode = 400;
        }

        return $this->response
            ->withType('application/json')
            ->withStatus($statusCode)
            ->withStringBody(json_encode($result));
    }

    // Função list, usado na rota listar
    public function list() {
        // String SQL que será passado pro banco de dados
        $sql = 'SELECT * FROM users WHERE deleted IS NULL ORDER BY id DESC';
        $statusCode = 400;

        try {
            $result = $GLOBALS['conexao']->execute($sql)->fetchAll('assoc');
            $statusCode = 200;
        } catch (Exception $e) {
            $result = "Registro não encontrado!";
        }

        return $this->response
            ->withType('application/json')
            ->withStatus($statusCode)
            ->withStringBody(json_encode($result));
    }

    public function edit($id = null) {
        $usuarioTable = $this->fetchTable('Users');
        $statusCode = 404;

        if ($this->request->is(['put'])) {
            try {
                // Pega o produto pelo id com a condição que não foi deletado
                $usuarioAlterar = $usuarioTable->get(intval($id), conditions: ['deleted IS NULL']);

                // Pega os dados enviados do put
                $form = $this->request->getData();

                // Joga
                $usuarioAlterar = $usuarioTable->patchEntity($usuarioAlterar, $form);

                // Se salvou na tabela
                if ($usuarioTable->save($usuarioAlterar)) {
                    $result = 'Usuario foi alterado com sucesso!';
                    $statusCode = 200;
                } else {
                    $statusCode = 400;
                    $result = 'Erro ao alterar usuario!';
                }
            } catch (RecordNotFoundException $e) {
                $result = 'Registro não encontrado ou foi deletado!';
            }
        } else {
            $statusCode = 400;
            $result = 'Formulário não foi enviado.';
        }


        return $this->response
            ->withType('application/json')
            ->withStatus($statusCode)
            ->withStringBody(json_encode($result));
    }

    public function delete($id = null) {
        $statusCode = 404;
        $usuarioTable = $this->fetchTable('Users');

        try {
            $usuarioAlterar = $usuarioTable->get(intval($id), conditions: ['deleted IS NULL']);

            $usuarioTable->deleted = 0;

            if ($usuarioTable->save($usuarioAlterar)) {
                $result = 'Usuário foi deletado com sucesso!';
                $statusCode = 200;
            } else {
                $result = 'Erro ao deletar usuário!';
                $statusCode = 400;
            }
        } catch (RecordNotFoundException $e) {
            $result = 'Registro não encontrado ou foi deletado!';
        }

        return $this->response
            ->withType('application/json')
            ->withStatus($statusCode)
            ->withStringBody(json_encode($result));
    }

    public function active($id = null) {
        $statusCode = 404;
        $usuarioTable = $this->fetchTable('Users');

        try {
            $usuarioAlterar = $usuarioTable->get(intval($id), conditions: ['deleted IS NOT NULL']);
            $usuarioTable->deleted = null;

            if ($usuarioTable->save($usuarioAlterar)) {
                $result = 'Usuário foi reativado com sucesso!';
                $statusCode = 200;
            } else {
                $result = 'Erro ao reativar usuário!';
                $statusCode = 400;
            }
        } catch (RecordNotFoundException $e) {
            $result = 'Registro não encontrado ou foi deletado!';
        }

        return $this->response
            ->withType('application/json')
            ->withStatus($statusCode)
            ->withStringBody(json_encode($result));
    }

    public function find($id = null) {
        // :id fala que vai ser trocado pelo valor de id recebido pela função
        // que é um intval (ele tenta parsear, caso dê erro ele explode excessão)
        $sql = 'SELECT * FROM users WHERE id = :id AND deleted IS NULL ORDER BY id DESC';
        $statusCode = 400;

        try {
            $result = $GLOBALS['conexao']->execute($sql, ['id' => intval($id)])->fetchAll('assoc');
            $statusCode = 200;
        } catch (Exception $e) {
            $result = $e->getMessage();
        }
        return $this->response
            ->withType('application/json')
            ->withStatus($statusCode)
            ->withStringBody(json_encode($result));
    }
}
