<?php

namespace App\Controller\Api;


use Cake\Datasource\Exception\RecordNotFoundException;
use Exception;

class ProdutosController extends AppController {

    public function add() {
    // Função add chamado pela rota adicionar
        if($this->request->is('post')) {
            $produtoTable = $this->fetchTable('Produtos');
            $novoProduto = $produtoTable->newEmptyEntity();
            $form = $this->request->getData();
            $novoProduto = $produtoTable->patchEntity($novoProduto, $form);

            if($produtoTable->save($novoProduto)) {
                $result = 'Produto foi cadastrado com sucesso!';
                $statusCode = 200;
            }
            else {
                $result = 'Erro ao cadastrar produto!';
                $statusCode = 400;
            }
        }
        else {
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
        $sql = 'SELECT * FROM produtos WHERE deleted IS NULL ORDER BY id DESC';
        $statusCode = 400;

        try {
            $result = $GLOBALS['conexao']->execute($sql)->fetchAll('assoc');
            $statusCode = 200;
        }
        catch (Exception $e) {
            $result = "Registro não encontrado!";
        }

        return $this->response
            ->withType('application/json')
            ->withStatus($statusCode)
            ->withStringBody(json_encode($result));
    }
    public function edit($id = null) {
        $produtoTable = $this->fetchTable('Produtos');
        $statusCode = 404;

        if($this->request->is(['put'])) {
            try {
                // Pega o produto pelo id com a condição que não foi deletado
                $produtoAlterar = $produtoTable->get(intval($id), conditions: ['deleted IS NULL']);

                // Pega os dados enviados do put
                $form = $this->request->getData();

                // Joga
                $produtoAlterar = $produtoTable->patchEntity($produtoAlterar, $form);

                // Se salvou na tabela
                if($produtoTable->save($produtoAlterar)) {
                    $result = 'Produto foi alterado com sucesso!';
                    $statusCode = 200;
                }
                else {
                    $statusCode = 400;
                    $result = 'Erro ao alterar produto!';
                }
            }
            catch (RecordNotFoundException $e) {
                $result = 'Registro não encontrado ou foi deletado!';
            }
        }
        else {
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
        $produtoTable = $this->fetchTable('Produtos');

        try {
            $produtoAlterar = $produtoTable->get(intval($id), conditions: ['deleted IS NULL']);

            $produtoTable->deleted = date('Y-m-d H:i:s');

            if($produtoTable->save($produtoAlterar)) {
                $result = 'Produto foi deletado com sucesso!';
                $statusCode = 200;
            }
            else {
                $result = 'Erro ao deletar produto!';
                $statusCode = 400;
            }
        }
        catch(RecordNotFoundException $e) {
            $result = 'Registro não encontrado ou foi deletado!';
        }

        return $this->response
            ->withType('application/json')
            ->withStatus($statusCode)
            ->withStringBody(json_encode($result));
    }

    public function active($id = null) {
        $statusCode = 404;
        $produtoTable = $this->fetchTable('Produtos');

        try {
            $produtoAlterar = $produtoTable->get(intval($id), conditions: ['deleted IS NOT NULL']);
            $produtoTable->deleted = null;

            if($produtoTable->save($produtoAlterar)) {
                $result = 'Produto foi reativado com sucesso!';
                $statusCode = 200;
            }
            else {
                $result = 'Erro ao reativar produto!';
                $statusCode = 400;
            }
        }
        catch(RecordNotFoundException $e) {
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
        $sql = 'SELECT * FROM produtos WHERE id = :id AND deleted IS NULL ORDER BY id DESC';
        $statusCode = 400;

        try {
            $result = $GLOBALS['conexao']->execute($sql, ['id' => intval($id)])->fetchAll('assoc');
            $statusCode = 200;
        }
        catch (Exception $e) {
            $result = $e->getMessage();
        }
        return $this->response
            ->withType('application/json')
            ->withStatus($statusCode)
            ->withStringBody(json_encode($result));
    }
}
