# 🧰 Emprega.me — Plataforma de Registo e Pesquisa de Emprego

Projeto desenvolvido em **PHP puro com MVC personalizado**, focado em:

- 🧑‍💼 Registo e login de candidatos e empresas
- ✅ Confirmação de conta por email
- 🔐 Sistema de autenticação com proteção CSRF e reCAPTCHA (Google NoCaptcha)
- ♻️ Integração com **RabbitMQ** para eventos assíncronos
- 🔎 Pesquisa/autocomplete com **Elasticsearch**
- 📦 Estrutura modular com PSR-4 e separação clara de responsabilidades
- 🌐 Totalmente responsivo e preparado para produção

---

## 🔧 Tecnologias Usadas

- **PHP 8+**
- **MySQL** (base de dados relacional)
- **JavaScript (AJAX)**
- **RabbitMQ** (fila de mensagens)
- **Elasticsearch** (motor de busca e autocomplete)
- **Docker** (para containers)
- **Composer** (gestão de dependências PHP)

---

## ⚙️ Como configurar o projeto

### 1. Clonar o repositório

```bash
git clone https://github.com/MARCIOMM-LL/Emprega.me.git
cd Emprega.me
```

### 2. Instalar dependências PHP

```bash
composer install
```

### 3. Configurar variáveis de ambiente

Cria um ficheiro `.env` com o seguinte conteúdo:

```ini
DB_HOST=localhost
DB_NAME=empregame
DB_USER=root
DB_PASS=

NOCAPTCHA_SITEKEY=chave_site_google
NOCAPTCHA_SECRET=segredo_google

MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=teu_utilizador_mailtrap
MAIL_PASSWORD=tua_senha_mailtrap
MAIL_FROM=nao-responder@emprega.me
MAIL_FROM_NAME="Emprega.me"
```

### 4. Importar base de dados

```bash
mysql -u root -p -e "CREATE DATABASE empregame CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
mysql -u root -p empregame < data_base_empregame.sql
```

> 📌 O ficheiro `data_base_empregame.sql` está incluído na raiz do projeto.

### 5. Iniciar containers (RabbitMQ e Elasticsearch)

```bash
docker run -d --name rabbitmq -p 5672:5672 -p 15672:15672 rabbitmq:3-management
docker run -d --name elasticsearch -p 9200:9200 -e "discovery.type=single-node" elasticsearch:7.17.10
```

- 🔗 RabbitMQ UI: [http://localhost:15672](http://localhost:15672)  
  - Utilizador: `guest`  
  - Palavra-passe: `guest`

- 🔗 Elasticsearch: [http://localhost:9200](http://localhost:9200)

### 6. Iniciar o Worker

Este worker escuta eventos de novas cidades/profissões e envia para o Elasticsearch:

```bash
php worker_elasticsearch.php
```

---

## 📁 Estrutura do Projeto (MVC)

```txt
Emprega.me/
├── app/
│   ├── Controllers/
│   ├── Models/
│   ├── Views/
│   ├── Core/
│   └── Helpers/
├── config/
├── public/
├── routes/
├── vendor/
└── worker_elasticsearch.php
```

---

## 🎉 Funcionalidades Atuais

- CRUD de cidades, profissões e utilizadores
- Confirmação de conta por token
- Login com verificação de conta confirmada
- Recuperação de senha por email
- Proteção CSRF e reCAPTCHA nos formulários
- Autocomplete com Elasticsearch via AJAX
- Envio automático de eventos para RabbitMQ
- Worker PHP para indexação dos dados

---

## 🔧 A fazer / melhorias futuras

- Upload de CVs e foto de perfil
- Painel de administração completo
- Validações frontend e UX melhorada
- Logs de email e falhas
- Paginação e filtros de resultados
- Testes unitários e de integração

---

## ❤️ Contribuições

Contribuições são bem-vindas! Abre uma issue ou faz um pull request.

---

---

## 🙋‍♂️ Sobre o Autor

Desenvolvido por [Márcio Miranda](https://www.linkedin.com/in/developer1988/), entusiasta de desenvolvimento web com foco em **PHP moderno**, **microserviços** e **boas práticas de código limpo**.


## 📦 Licença

Este projeto é de código aberto e está licenciado sob a Licença MIT.

Sugestões são bem vindo. Livre para alterar e evoluir.
