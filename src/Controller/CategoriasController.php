<?php

namespace App\Controller\Api;

use App\Controller\Api\AppController;
use Cake\Datasource\Exception\RecordNotFoundException;
use Exception;

class CategoriasController extends AppController {

    public function add() {
        // Função add chamado pela rota adicionar
        if ($this->request->is('post')) {
            $categoriasTable = $this->fetchTable('Categorias');
            $novaCategoria = $categoriasTable->newEmptyEntity();
            $form = $this->request->getData();
            $novaCategoria = $categoriasTable->patchEntity($novaCategoria, $form);


            if ($categoriasTable->save($novaCategoria)) {
                $result = 'Categoria foi cadastrada com sucesso!';
                $statusCode = 200;
            } else {
                $result = 'Erro ao cadastrar categoria!';
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
        $sql = 'SELECT * FROM categorias WHERE deleted IS NULL ORDER BY id DESC';
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
        $categoriasTable = $this->fetchTable('Categorias');
        $statusCode = 404;

        if ($this->request->is(['put'])) {
            try {
                // Pega o produto pelo id com a condição que não foi deletado
                $categoriaAlterar = $categoriasTable->get(intval($id), conditions: ['deleted IS NULL']);

                // Pega os dados enviados do put
                $form = $this->request->getData();

                // Joga
                $categoriaAlterar = $categoriasTable->patchEntity($categoriaAlterar, $form);

                // Se salvou na tabela
                if ($categoriasTable->save($categoriaAlterar)) {
                    $result = 'Categoria foi alterada com sucesso!';
                    $statusCode = 200;
                } else {
                    $statusCode = 400;
                    $result = 'Erro ao alterar categoria!';
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
        $categoriasTable = $this->fetchTable('Categorias');

        try {
            $categoriaAlterar = $categoriasTable->get(intval($id), conditions: ['deleted IS NULL']);

            $categoriasTable->deleted = date('Y-m-d H:i:s');

            if ($categoriasTable->save($categoriaAlterar)) {
                $result = 'Categoria foi deletada com sucesso!';
                $statusCode = 200;
            } else {
                $result = 'Erro ao deletar categoria!';
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
        $categoriasTable = $this->fetchTable('Categorias');

        try {
            $categoriaAlterar = $categoriasTable->get(intval($id), conditions: ['deleted IS NOT NULL']);
            $categoriasTable->deleted = null;

            if ($categoriasTable->save($categoriaAlterar)) {
                $result = 'Categoria foi reativada com sucesso!';
                $statusCode = 200;
            } else {
                $result = 'Erro ao reativar categoria!';
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
        $sql = 'SELECT * FROM categorias WHERE id = :id AND deleted IS NULL ORDER BY id DESC';
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
