<?php

namespace App\Controller;

use Cake\Mailer\Mailer;
use DateTime;

class TestesController extends AppController {
    // Publico -> tudo o que o usuário for ver
    // Protegido -> funções ou rotas que o usuário não é pra ver

    public function index() {
        // pega a sessão atual
        $sessao = $this->request->getSession();
        $itens = $sessao->read('itens') ?? [];
        $form = null;

        // Se requisição for do tipo post
        if($this->request->is('post')) {
            // Pega os dados vindos do post
            $form = $this->request->getData();
            $codigo = rand(000000, 999999);


            if(empty(trim($form['produto']))) {
                $this->Flash->error(__("Produto está vazio"));
                return;
            }
            if(empty($form['quantidade']) || !is_numeric($form['quantidade']) || $form['quantidade'] < 0)  {
                $this->Flash->error(__("Quantidade do produto está vazio ou não é número!!"));
                return;
            }
            if(empty($form['valor']) || !is_numeric($form['valor']) || $form['valor'] < 0)  {
                $this->Flash->error(__("Valor do produto está vazio ou não é número!!"));
                return;
            }

            $itens[] = $form;
            $sessao->write('itens', $itens);
            $this->Flash->success(__("Cadastrado com sucesso!!"));
            $this->set('itens', $itens);
            $this->enviarEmail('nicr3432@gmail.com', $codigo);
        }
        $this->set(compact('itens', 'form'));
    }

    protected function enviarEmail($destinatario, $codigo) {
        $mailer = new Mailer('default');

        try {
            $mailer
                ->setTo($destinatario)
                ->setSubject('Teste de envio de email')
                ->deliver('Olá! Seu codigo é: ' . $codigo . ' para alteração da senha!!');
            $this->Flash->success(__("Email enviado com sucesso!!"));
        }
        catch(Exception $e) {
            $this->Flash->error('Erro ao enviar a mensagem: ' . $e->getMessage());
        }
    }
}
