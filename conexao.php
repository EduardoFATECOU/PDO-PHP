<?php
/**
 * =====================================================
 * Arquivo de Conexão com Banco de Dados usando PDO
 * =====================================================
 * PDO (PHP Data Objects) é uma extensão que fornece uma interface consistente
 * para acessar diferentes bancos de dados em PHP
 * 
 * Vantagens do PDO:
 * - Suporte a múltiplos bancos de dados
 * - Prepared statements (proteção contra SQL Injection)
 * - Tratamento de erros com exceções
 * - Código mais limpo e orientado a objetos
 */

// Inclui o arquivo de configuração com as constantes do banco
require_once 'config.php';

/**
 * Classe Database
 * Responsável por gerenciar a conexão com o banco de dados
 * Utiliza o padrão Singleton para garantir uma única instância de conexão
 */
class Database {
    // Propriedade estática que armazenará a instância da conexão PDO
    private static $conexao = null;
    
    /**
     * Método estático para obter a conexão com o banco de dados
     * Se a conexão não existir, cria uma nova
     * Se já existir, retorna a conexão existente (Singleton)
     * 
     * @return PDO Objeto de conexão PDO
     */
    public static function getConexao() {
        // Verifica se a conexão já foi estabelecida
        if (self::$conexao === null) {
            try {
                // Monta a string DSN (Data Source Name) para conexão
                $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
                
                // Opções de configuração do PDO
                $opcoes = [
                    // Define o modo de erro para exceções (facilita tratamento de erros)
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    
                    // Define o modo de fetch padrão como array associativo
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    
                    // Desabilita a emulação de prepared statements (mais seguro)
                    PDO::ATTR_EMULATE_PREPARES => false,
                    
                    // Define o charset da conexão
                    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES " . DB_CHARSET
                ];
                
                // Cria a nova conexão PDO
                self::$conexao = new PDO($dsn, DB_USER, DB_PASS, $opcoes);
                
            } catch (PDOException $e) {
                // Captura e trata erros de conexão
                // Em produção, registre o erro em um log ao invés de exibir
                die("Erro na conexão com o banco de dados: " . $e->getMessage());
            }
        }
        
        // Retorna a conexão estabelecida
        return self::$conexao;
    }
    
    /**
     * Método para fechar a conexão (opcional)
     * O PHP fecha automaticamente ao final do script
     */
    public static function fecharConexao() {
        self::$conexao = null;
    }
}

/**
 * Função auxiliar para obter a conexão rapidamente
 * Facilita o uso em outros arquivos do sistema
 * 
 * @return PDO Objeto de conexão PDO
 */
function getDB() {
    return Database::getConexao();
}
?>
