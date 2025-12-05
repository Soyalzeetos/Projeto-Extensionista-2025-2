# Center Ferramentas

Projeto de E-commerce de ferramentas desenvolvido para a disciplina de Extensão Universitária (2025-2). Aplicação Full Stack utilizando **PHP 8.2**, **Docker** e **MySQL**.

---

## Como Iniciar o Projeto (Passo a Passo)

### 1. Pré-requisitos
Certifique-se de ter instalado no seu computador:
* [Docker Desktop](https://www.docker.com/products/docker-desktop/)
* Git

### 2. Instalação e Execução

Siga os comandos abaixo no seu terminal:

1.  **Clone o repositório:**
    ```bash
    git clone [https://github.com/Soyalzeetos/Projeto-Extensionista-2025-2](https://github.com/Soyalzeetos/Projeto-Extensionista-2025-2)
    cd center-ferramentas
    ```

2.  **Verifique o arquivo de ambiente:**
    Confirme se o arquivo `.env` existe na raiz do projeto com as configurações do banco de dados e a porta `APP_PORT=8080`.

3.  **Suba os containers:**
    ```bash
    docker compose up -d
    ```
    *Aguarde alguns instantes. Isso irá configurar o servidor PHP e criar o banco de dados automaticamente.*

4.  **Instale as dependências (Importante!):**
    Para gerar o carregador automático de classes (Autoload), execute:
    ```bash
    docker compose exec app composer install
    ```

5.  **Acesse a aplicação:**
    Abra o navegador em: [http://localhost:8080](http://localhost:8080)

---

## 📂 Estrutura Simplificada

* `src/`: Contém a lógica do sistema (Controllers, Models, Banco de Dados).
* `views/`: Arquivos visuais (HTML/PHP).
* `public/`: Arquivos acessíveis pelo navegador (Imagens, CSS, index.php).
* `database/`: Script de criação inicial do banco (`init.sql`).

---

## Problemas Comuns

**Erro: "Warning: require(.../vendor/autoload.php): Failed to open stream"**
* **Solução:** Você esqueceu do passo 4. Rode `docker compose exec app composer install` no terminal para criar a pasta `vendor`.

**Erro: Banco de dados não conecta**
* **Solução:** Na primeira vez que você roda o projeto, o MySQL pode demorar de 10 a 20 segundos para iniciar. Espere um pouco e atualize a página.

**Porta 8080 em uso**
* **Solução:** Mude a variável `APP_PORT` no arquivo `.env` para outro valor (ex: 8081) e reinicie com `docker compose up -d`.
