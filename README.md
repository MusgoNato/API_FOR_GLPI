# README - Configuração do Bot de Pedidos de Pizza

Bem-vindo ao guia de configuração do **Bot de Pedidos de Pizza**! Este documento explica como configurar o ambiente localmente e integrá-lo ao Telegram e ao GLPI. Siga os passos abaixo cuidadosamente para garantir que tudo funcione como esperado.

---
### Integração com GLPI ainda em desenvolvimento
---

## Pré-requisitos

Antes de começar, certifique-se de ter instalado:
- **PHP** (versão 7.4 ou superior)
- **Ngrok** (para expor o servidor local)
- **GLPI** (instância local ou remota para integração)
- Um editor de texto para modificar arquivos `.ini` e `.env`

---

## Passos de Configuração

### 1. Habilitar `allow_url_fopen` no `php.ini`
O bot utiliza `file_get_contents` para fazer requisições à API do Telegram, então é necessário habilitar a opção `allow_url_fopen`.

- Localize o arquivo `php.ini` no seu ambiente PHP (ex.: `/etc/php/7.4/cli/php.ini` no Linux ou via `php --ini`).
- Encontre a linha:
;allow_url_fopen = Off
- Altere para:
allow_url_fopen = On
- Salve o arquivo e reinicie o servidor PHP, se necessário.

> **Nota**: Se estiver usando um servidor local como XAMPP ou WAMP, ajuste via interface gráfica ou diretamente no `php.ini`.

---

### 2. Descomentar e Comentar a Linha 22
Para depuração inicial, descomente temporariamente a linha 22 do arquivo principal (provavelmente um log ou configuração específica).

- Abra o arquivo principal (ex.: `BotMessagesController.php`).
- Localize a linha:
```
// Conecta a webhook (Executar apenas uma vez, depois comentar)
// $responseTelegram = InitConectionTelegramController::initWebhookTelegram();
```
- Descomente, após inicializar, comente novamente
---

### 3. Rodar o Servidor Localmente
Execute o servidor PHP localmente para testar o bot.

- Abra o terminal na raiz do projeto.
- Execute o comando:
```bash
php -S localhost:8000 -t public

```
---
### 4. Expor localhost com ngrok para WEBHOOK
Faça o download do ngrok em sua máquina e deixe seu localhost exposto remotamente, para uso da webhook.

- ngrok ngrok config add-authtoken seu_token_aqui. 
- ngrok http 8000 (Se estiver rodando em outra porta com php -S, insira ela).
- No arquivo .env, insira a url gerada pelo ngrok
---
### 5. Resolução de Problemas
Bot não responde no Telegram?
- Verifique se a URL do Ngrok está correta no .env.
- Confirme que o webhook foi configurado no Telegram (ex.: via setWebhook com a URL).
    
Erro de conexão com o GLPI?
- Cheque as credenciais no .env e se o GLPI está rodando.
    
Logs não aparecem?
- Certifique-se de que o error_log está habilitado no PHP e o caminho do log é acessível.