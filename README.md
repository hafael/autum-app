# Autum App (Repositório-Semente)

Este repositório é o **repositório-semente (template / skeleton)** oficial para a criação de novas aplicações dentro da plataforma **Autum** (como `autum-crm`, `autum-helpdesk`, etc.). 

Quando uma nova aplicação Autum precisa ser desenvolvida, o desenvolvedor faz o download do estado atual da branch `dev-main` deste repositório e inicia um novo repositório git para o projeto final.

O objetivo deste template é fornecer uma estrutura padronizada com toda a infraestrutura de autenticação, integração com microsserviços e controle de limites já configurados e prontos para uso.

## 🛠️ Infraestrutura e Tecnologias Inclusas

Cada nova aplicação criada a partir deste repositório já vem equipada com:

*   **Backend:** [Laravel 13](file:///Users/rafael/Work/autum-app/composer.json) executando sob **PHP 8.4**.
*   **Frontend:** [Vue.js 3](file:///Users/rafael/Work/autum-app/package.json), [Inertia.js v3](file:///Users/rafael/Work/autum-app/package.json) (SPA sem APIs REST tradicionais) e **Tailwind CSS v3**.
*   **Autenticação Integrada (SSO & Mesh):**
    *   Single Sign-On (SSO) via **SAML 2.0** integrado com o Provedor de Identidade (IdP) da Autum (`accounts-local.autum.com.br`) utilizando a biblioteca `hafael/autum-saml-sp`.
    *   Autenticação de API interna e emissão de tokens de microsserviços por meio da biblioteca `hafael/laravel-mesh-auth`.
    *   [Laravel Jetstream](file:///Users/rafael/Work/autum-app/composer.json) e [Fortify](file:///Users/rafael/Work/autum-app/composer.json) (gerenciamento de times/equipes, fotos de perfil e autenticação de dois fatores - 2FA).
*   **Controle de Cotas & Consumo:**
    *   Gerenciamento e controle de limites de planos por usuário através da tabela `spend_limits`.
    *   Integração dinâmica via View de Banco de Dados `current_spend_user` para monitoramento automatizado de limites em tempo real (limite de equipes, membros, período de testes, requisições, etc.).
*   **Ferramentas de Testes e Linting:**
    *   [Pest PHP](file:///Users/rafael/Work/autum-app/composer.json) para suite de testes moderna.
    *   [Laravel Pint](file:///Users/rafael/Work/autum-app/composer.json) para formatação de código.

## 📁 Arquivos Estruturais Principais

*   **[`app/Models/User.php`](file:///Users/rafael/Work/autum-app/app/Models/User.php):** Model de usuário configurado com Jetstream, Fortify 2FA, verificação de administradores, limites de contas (`currentSpendUser`) e emissão de tokens de acesso à API (`AppAccessToken`).
*   **[`app/Models/CurrentSpendUser.php`](file:///Users/rafael/Work/autum-app/app/Models/CurrentSpendUser.php):** Model que mapeia a view SQL dinâmica `current_spend_user` para consultar e validar limites do usuário.
*   **[`app/Models/Phone.php`](file:///Users/rafael/Work/autum-app/app/Models/Phone.php):** Objeto de valor especializado para normalização e validação de números de telefone no formato internacional.
*   **[`app/Services/AutumPlatformService.php`](file:///Users/rafael/Work/autum-app/app/Services/AutumPlatformService.php):** Serviço que estende o SDK da plataforma Autum (`Autum\SDK\Platform\Client`) para delegar requisições em nome do usuário logado.
*   **[`app/Http/Controllers/API/`](file:///Users/rafael/Work/autum-app/app/Http/Controllers/API/):** Controladores pré-prontos para busca de times (`TeamsController`) e aplicações (`ApplicationsController`) que consomem a API central do Autum.

## 🚀 Como Iniciar um Novo Aplicativo

### 1. Inicializar a partir da Semente
Para criar seu novo aplicativo (por exemplo, `autum-crm`):

1. Baixe o estado atual da branch `dev-main` deste repositório.
2. Crie e inicialize um novo repositório Git para o seu novo projeto.
3. Atualize o arquivo `composer.json` ajustando a chave `"name"` para o nome da nova aplicação (ex: `autum/autum-crm`) e outras configurações específicas do projeto.

### 2. Configurar o Ambiente Local

Certifique-se de que possui o PHP 8.2 ou superior (Recomendado PHP 8.4), Composer e Node.js instalados.

1.  **Instalação de Dependências**
    ```bash
    composer install
    npm install
    ```

2.  **Variáveis de Ambiente**
    Copie o arquivo de exemplo:
    ```bash
    cp .env.example .env
    ```
    Edite o arquivo `.env` preenchendo as configurações de banco de dados, chaves de API (`AUTUM_API_KEY`, `IDP_API_SECRET`, etc.) e a URL do Provedor SAML Autum.

3.  **Geração da Chave da Aplicação**
    ```bash
    php artisan key:generate
    ```

4.  **Banco de Dados e Seeds**
    Execute as migrações (que criam as tabelas e a view SQL de cota de uso) e popule os limites básicos:
    ```bash
    php artisan migrate
    php artisan db:seed --class=SpendLimitSeeder
    ```

5.  **Executar o Ambiente de Desenvolvimento**
    Use o atalho configurado no Composer para subir em paralelo o servidor web local, fila de jobs, log watcher e Vite:
    ```bash
    composer run dev
    ```

6.  **Testes e Formatação**
    Execute a suite de testes Pest para validar o ambiente:
    ```bash
    php artisan test
    ```
    Use o Pint para garantir os padrões de codificação:
    ```bash
    vendor/bin/pint --dirty --format agent
    ```
