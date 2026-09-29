<?php

namespace App\Controller\Api;

use App\Controller\Api\AppController;
use Cake\Datasource\Exception\RecordNotFoundException;
use Exception;

class MarcasController extends AppController {

    public function add() {
        // Função add chamado pela rota adicionar
        if ($this->request->is('post')) {
            $marcasTable = $this->fetchTable('Marcas');
            $novaMarca = $marcasTable->newEmptyEntity();
            $form = $this->request->getData();
            $novaMarca = $marcasTable->patchEntity($novaMarca, $form);


            if ($marcasTable->save($novaMarca)) {
                $result = 'Marca foi cadastrada com sucesso!';
                $statusCode = 200;
            } else {
                $result = 'Erro ao cadastrar marca!';
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
        $sql = 'SELECT * FROM marcas WHERE deleted IS NULL ORDER BY id DESC';
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
        $marcasTable = $this->fetchTable('Marcas');
        $statusCode = 404;

        if ($this->request->is(['put'])) {
            try {
                // Pega o produto pelo id com a condição que não foi deletado
                $marcasAlterar = $marcasTable->get(intval($id), conditions: ['deleted IS NULL']);

                // Pega os dados enviados do put
                $form = $this->request->getData();

                // Joga
                $marcasAlterar = $marcasTable->patchEntity($marcasAlterar, $form);

                // Se salvou na tabela
                if ($marcasTable->save($marcasAlterar)) {
                    $result = 'Marca foi alterada com sucesso!';
                    $statusCode = 200;
                } else {
                    $statusCode = 400;
                    $result = 'Erro ao alterar marca!';
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
        $marcasTable = $this->fetchTable('Marcas');

        try {
            $marcasAlterar = $marcasTable->get(intval($id), conditions: ['deleted IS NULL']);

            $marcasTable->deleted = date('Y-m-d H:i:s');

            if ($marcasTable->save($marcasAlterar)) {
                $result = 'Marca foi deletada com sucesso!';
                $statusCode = 200;
            } else {
                $result = 'Erro ao deletar marca!';
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
        $marcasTable = $this->fetchTable('Marcas');

        try {
            $marcasAlterar = $marcasTable->get(intval($id), conditions: ['deleted IS NOT NULL']);
            $marcasTable->deleted = null;

            if ($marcasTable->save($marcasAlterar)) {
                $result = 'Marca foi reativada com sucesso!';
                $statusCode = 200;
            } else {
                $result = 'Erro ao reativar marcas!';
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
        $sql = 'SELECT * FROM marcas WHERE id = :id AND deleted IS NULL ORDER BY id DESC';
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
