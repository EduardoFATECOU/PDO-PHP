# Guia de Instalação - Sistema CRUD PHP PDO MariaDB

Este guia fornece instruções detalhadas para instalar e configurar o sistema CRUD em diferentes ambientes.

## 📋 Índice

1. [Requisitos do Sistema](#requisitos-do-sistema)
2. [Instalação no XAMPP (Windows)](#instalação-no-xampp-windows)
3. [Instalação no WAMP (Windows)](#instalação-no-wamp-windows)
4. [Instalação no Linux (Ubuntu/Debian)](#instalação-no-linux-ubuntudebian)
5. [Instalação no macOS](#instalação-no-macos)
6. [Configuração do Banco de Dados](#configuração-do-banco-de-dados)
7. [Verificação da Instalação](#verificação-da-instalação)
8. [Solução de Problemas](#solução-de-problemas)

---

## Requisitos do Sistema

### Requisitos Mínimos

- **Servidor Web**: Apache 2.4+ ou Nginx 1.18+
- **PHP**: Versão 7.4 ou superior
- **Banco de Dados**: MariaDB 10.3+ ou MySQL 5.7+
- **Espaço em Disco**: 10 MB
- **Memória RAM**: 512 MB (recomendado 1 GB)

### Extensões PHP Necessárias

As seguintes extensões devem estar habilitadas no PHP:

- `pdo`
- `pdo_mysql`
- `mbstring`
- `json`

Para verificar se as extensões estão habilitadas, crie um arquivo `info.php` com o seguinte conteúdo:

```php
<?php phpinfo(); ?>
```

Acesse o arquivo no navegador e procure pelas extensões listadas acima.

---

## Instalação no XAMPP (Windows)

### Passo 1: Instalar o XAMPP

1. Baixe o XAMPP em: https://www.apachefriends.org/
2. Execute o instalador e siga as instruções
3. Instale no diretório padrão: `C:\xampp`

### Passo 2: Copiar os Arquivos

1. Copie a pasta `crud-php-pdo` para: `C:\xampp\htdocs\`
2. O caminho completo será: `C:\xampp\htdocs\crud-php-pdo\`

### Passo 3: Iniciar os Serviços

1. Abra o **XAMPP Control Panel**
2. Clique em **Start** ao lado de **Apache**
3. Clique em **Start** ao lado de **MySQL**

### Passo 4: Criar o Banco de Dados

1. Abra o navegador e acesse: `http://localhost/phpmyadmin`
2. Clique em **Novo** na barra lateral esquerda
3. Digite o nome: `crud_pdo`
4. Selecione **utf8mb4_unicode_ci** como collation
5. Clique em **Criar**
6. Clique na aba **SQL**
7. Copie e cole o conteúdo do arquivo `database.sql`
8. Clique em **Executar**

### Passo 5: Configurar a Conexão

1. Abra o arquivo `config.php` em um editor de texto
2. Verifique se as configurações estão corretas:

```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'crud_pdo');
define('DB_USER', 'root');
define('DB_PASS', '');  // Senha vazia no XAMPP por padrão
```

### Passo 6: Acessar o Sistema

Abra o navegador e acesse: `http://localhost/crud-php-pdo/`

---

## Instalação no WAMP (Windows)

### Passo 1: Instalar o WAMP

1. Baixe o WAMP em: https://www.wampserver.com/
2. Execute o instalador e siga as instruções
3. Instale no diretório padrão: `C:\wamp64`

### Passo 2: Copiar os Arquivos

1. Copie a pasta `crud-php-pdo` para: `C:\wamp64\www\`
2. O caminho completo será: `C:\wamp64\www\crud-php-pdo\`

### Passo 3: Iniciar os Serviços

1. Inicie o **WAMP Server** pelo menu Iniciar
2. Aguarde o ícone ficar **verde** na bandeja do sistema
3. Se ficar **laranja** ou **vermelho**, verifique se as portas 80 e 3306 não estão em uso

### Passo 4: Criar o Banco de Dados

1. Clique no ícone do WAMP na bandeja
2. Selecione **phpMyAdmin**
3. Siga os mesmos passos do XAMPP (Passo 4)

### Passo 5: Configurar e Acessar

Siga os mesmos passos 5 e 6 do XAMPP, mas acesse: `http://localhost/crud-php-pdo/`

---

## Instalação no Linux (Ubuntu/Debian)

### Passo 1: Instalar o LAMP Stack

Abra o terminal e execute:

```bash
# Atualizar repositórios
sudo apt update

# Instalar Apache
sudo apt install apache2 -y

# Instalar MariaDB
sudo apt install mariadb-server -y

# Instalar PHP e extensões
sudo apt install php libapache2-mod-php php-mysql php-mbstring php-json -y

# Reiniciar Apache
sudo systemctl restart apache2
```

### Passo 2: Configurar o MariaDB

```bash
# Executar script de segurança
sudo mysql_secure_installation

# Responda as perguntas:
# - Enter current password: [pressione Enter]
# - Set root password? [Y/n]: Y
# - Digite uma senha segura
# - Remove anonymous users? [Y/n]: Y
# - Disallow root login remotely? [Y/n]: Y
# - Remove test database? [Y/n]: Y
# - Reload privilege tables? [Y/n]: Y
```

### Passo 3: Criar o Banco de Dados

```bash
# Acessar o MariaDB
sudo mysql -u root -p

# Dentro do MySQL, execute:
CREATE DATABASE crud_pdo CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
EXIT;

# Importar o arquivo SQL
sudo mysql -u root -p crud_pdo < /caminho/para/database.sql
```

### Passo 4: Copiar os Arquivos

```bash
# Copiar arquivos para o diretório do Apache
sudo cp -r crud-php-pdo /var/www/html/

# Ajustar permissões
sudo chown -R www-data:www-data /var/www/html/crud-php-pdo
sudo chmod -R 755 /var/www/html/crud-php-pdo
```

### Passo 5: Configurar a Conexão

Edite o arquivo `config.php`:

```bash
sudo nano /var/www/html/crud-php-pdo/config.php
```

Ajuste as credenciais conforme necessário.

### Passo 6: Acessar o Sistema

Abra o navegador e acesse: `http://localhost/crud-php-pdo/`

---

## Instalação no macOS

### Passo 1: Instalar o Homebrew

Abra o Terminal e execute:

```bash
/bin/bash -c "$(curl -fsSL https://raw.githubusercontent.com/Homebrew/install/HEAD/install.sh)"
```

### Passo 2: Instalar o LAMP Stack

```bash
# Instalar Apache (já vem no macOS, mas pode atualizar)
brew install httpd

# Instalar PHP
brew install php

# Instalar MariaDB
brew install mariadb

# Iniciar os serviços
brew services start httpd
brew services start mariadb
```

### Passo 3: Configurar o Apache

```bash
# Editar configuração do Apache
sudo nano /usr/local/etc/httpd/httpd.conf

# Encontre e descomente a linha:
LoadModule php_module /usr/local/opt/php/lib/httpd/modules/libphp.so

# Adicione no final do arquivo:
<FilesMatch \.php$>
    SetHandler application/x-httpd-php
</FilesMatch>

# Reiniciar Apache
brew services restart httpd
```

### Passo 4: Criar o Banco de Dados

```bash
# Acessar MariaDB
mysql -u root

# Criar banco e importar SQL
CREATE DATABASE crud_pdo CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE crud_pdo;
SOURCE /caminho/para/database.sql;
EXIT;
```

### Passo 5: Copiar os Arquivos

```bash
# Copiar para o diretório do Apache
sudo cp -r crud-php-pdo /usr/local/var/www/htdocs/

# Ajustar permissões
sudo chown -R _www:_www /usr/local/var/www/htdocs/crud-php-pdo
```

### Passo 6: Acessar o Sistema

Abra o navegador e acesse: `http://localhost/crud-php-pdo/`

---

## Configuração do Banco de Dados

### Opção 1: Via phpMyAdmin

1. Acesse `http://localhost/phpmyadmin`
2. Faça login (usuário: `root`, senha: conforme configurado)
3. Clique em **Novo** → Digite `crud_pdo`
4. Selecione collation: `utf8mb4_unicode_ci`
5. Clique em **Criar**
6. Vá para a aba **SQL**
7. Cole o conteúdo de `database.sql`
8. Clique em **Executar**

### Opção 2: Via Linha de Comando

```bash
# MySQL/MariaDB
mysql -u root -p

# Dentro do MySQL
CREATE DATABASE crud_pdo CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE crud_pdo;
SOURCE /caminho/completo/para/database.sql;
EXIT;
```

### Opção 3: Importação Direta

```bash
mysql -u root -p crud_pdo < database.sql
```

---

## Verificação da Instalação

### Checklist de Verificação

- [ ] Apache está rodando
- [ ] MySQL/MariaDB está rodando
- [ ] PHP está instalado e funcionando
- [ ] Extensões PDO estão habilitadas
- [ ] Banco de dados `crud_pdo` foi criado
- [ ] Tabela `usuarios` existe no banco
- [ ] Arquivo `config.php` tem as credenciais corretas
- [ ] Permissões dos arquivos estão corretas

### Teste de Conexão

Crie um arquivo `teste-conexao.php` na raiz do projeto:

```php
<?php
require_once 'conexao.php';

try {
    $pdo = getDB();
    echo "✅ Conexão com o banco de dados estabelecida com sucesso!";
} catch (Exception $e) {
    echo "❌ Erro na conexão: " . $e->getMessage();
}
?>
```

Acesse no navegador: `http://localhost/crud-php-pdo/teste-conexao.php`

---

## Solução de Problemas

### Erro: "Access denied for user 'root'@'localhost'"

**Solução:**
- Verifique o usuário e senha no arquivo `config.php`
- No XAMPP/WAMP, a senha padrão é vazia
- No Linux/macOS, use a senha definida na instalação

### Erro: "Could not find driver"

**Solução:**
- A extensão PDO não está habilitada
- Edite o `php.ini` e descomente:
  ```
  extension=pdo_mysql
  ```
- Reinicie o Apache

### Erro: "Table 'crud_pdo.usuarios' doesn't exist"

**Solução:**
- O banco de dados não foi criado corretamente
- Execute novamente o arquivo `database.sql`

### Erro: "SQLSTATE[HY000] [2002] Connection refused"

**Solução:**
- O MySQL/MariaDB não está rodando
- Inicie o serviço:
  - Windows: XAMPP/WAMP Control Panel
  - Linux: `sudo systemctl start mariadb`
  - macOS: `brew services start mariadb`

### Página em branco (tela branca)

**Solução:**
- Erro de sintaxe no PHP
- Habilite exibição de erros no `config.php`:
  ```php
  ini_set('display_errors', 1);
  error_reporting(E_ALL);
  ```
- Verifique os logs de erro do Apache

### CSS não está carregando

**Solução:**
- Verifique o caminho do arquivo `style.css`
- Limpe o cache do navegador (Ctrl+F5)
- Verifique permissões do arquivo

---

## Próximos Passos

Após a instalação bem-sucedida:

1. **Teste todas as funcionalidades**: Inserir, editar, excluir usuários
2. **Personalize o sistema**: Altere cores, adicione campos
3. **Implemente melhorias**: Adicione paginação, busca, filtros
4. **Estude o código**: Leia os comentários para entender cada parte

---

## Suporte

Se encontrar problemas não listados aqui:

1. Verifique os logs de erro do PHP e Apache
2. Confirme que todas as extensões necessárias estão instaladas
3. Teste a conexão com o banco de dados separadamente
4. Verifique as permissões dos arquivos e diretórios

---

**Boa sorte com seu sistema CRUD! 🚀**
