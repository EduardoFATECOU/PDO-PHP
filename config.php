<?php
/**
 * =====================================================
 * Arquivo de Configuração do Banco de Dados
 * =====================================================
 * Este arquivo contém as configurações de conexão com o banco de dados MariaDB/MySQL
 * Centralizando as configurações, facilitamos a manutenção e alterações futuras
 */

// Define as constantes de conexão com o banco de dados
// Essas constantes são usadas pelo arquivo de conexão (conexao.php)

// Host do banco de dados (geralmente 'localhost' em ambiente local)
define('DB_HOST', 'localhost');

// Nome do banco de dados que será utilizado
define('DB_NAME', 'crud_pdo');

// Nome de usuário do banco de dados
define('DB_USER', 'root');

// Senha do usuário do banco de dados
define('DB_PASS', '');

// Charset para garantir suporte a caracteres especiais e acentuação
define('DB_CHARSET', 'utf8mb4');

/**
 * Configurações adicionais
 */

// Define o fuso horário padrão para o sistema
date_default_timezone_set('America/Sao_Paulo');

// Habilita a exibição de erros (desabilitar em produção)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>
