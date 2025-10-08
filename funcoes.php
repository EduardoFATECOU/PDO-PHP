<?php
/**
 * =====================================================
 * Arquivo de Funções Auxiliares e Validações
 * =====================================================
 * Este arquivo contém funções reutilizáveis para validação
 * de dados e outras operações auxiliares do sistema
 */

/**
 * Valida se um campo está vazio
 * 
 * @param string $valor Valor a ser validado
 * @return bool Retorna true se estiver vazio, false caso contrário
 */
function campoVazio($valor) {
    return empty(trim($valor));
}

/**
 * Valida formato de e-mail
 * 
 * @param string $email E-mail a ser validado
 * @return bool Retorna true se o e-mail for válido, false caso contrário
 */
function validarEmail($email) {
    // Utiliza filter_var com FILTER_VALIDATE_EMAIL para validação robusta
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Valida formato de telefone brasileiro
 * Aceita formatos: (00) 0000-0000 ou (00) 00000-0000
 * 
 * @param string $telefone Telefone a ser validado
 * @return bool Retorna true se o telefone for válido, false caso contrário
 */
function validarTelefone($telefone) {
    // Remove caracteres não numéricos
    $telefone = preg_replace('/[^0-9]/', '', $telefone);
    
    // Verifica se tem 10 ou 11 dígitos (com DDD)
    return strlen($telefone) >= 10 && strlen($telefone) <= 11;
}

/**
 * Valida formato de data
 * 
 * @param string $data Data no formato Y-m-d
 * @return bool Retorna true se a data for válida, false caso contrário
 */
function validarData($data) {
    if (empty($data)) {
        return true; // Data é opcional
    }
    
    // Separa a data em partes
    $partes = explode('-', $data);
    
    // Verifica se tem 3 partes e se é uma data válida
    if (count($partes) === 3) {
        return checkdate($partes[1], $partes[2], $partes[0]);
    }
    
    return false;
}

/**
 * Valida se a data de nascimento é válida (não pode ser futura)
 * 
 * @param string $data Data de nascimento no formato Y-m-d
 * @return bool Retorna true se for válida, false caso contrário
 */
function validarDataNascimento($data) {
    if (empty($data)) {
        return true; // Data é opcional
    }
    
    // Primeiro valida o formato
    if (!validarData($data)) {
        return false;
    }
    
    // Verifica se a data não é futura
    $dataInformada = new DateTime($data);
    $hoje = new DateTime();
    
    return $dataInformada <= $hoje;
}

/**
 * Sanitiza uma string removendo tags HTML e caracteres especiais
 * 
 * @param string $valor Valor a ser sanitizado
 * @return string Valor sanitizado
 */
function sanitizar($valor) {
    // Remove tags HTML e PHP
    $valor = strip_tags($valor);
    
    // Remove espaços em branco extras
    $valor = trim($valor);
    
    return $valor;
}

/**
 * Formata telefone para exibição
 * 
 * @param string $telefone Telefone sem formatação
 * @return string Telefone formatado
 */
function formatarTelefone($telefone) {
    // Remove caracteres não numéricos
    $telefone = preg_replace('/[^0-9]/', '', $telefone);
    
    // Formata conforme o tamanho
    if (strlen($telefone) === 11) {
        // Formato: (00) 00000-0000
        return preg_replace('/(\d{2})(\d{5})(\d{4})/', '($1) $2-$3', $telefone);
    } elseif (strlen($telefone) === 10) {
        // Formato: (00) 0000-0000
        return preg_replace('/(\d{2})(\d{4})(\d{4})/', '($1) $2-$3', $telefone);
    }
    
    return $telefone;
}

/**
 * Formata data do formato Y-m-d para d/m/Y
 * 
 * @param string $data Data no formato Y-m-d
 * @return string Data formatada em d/m/Y ou string vazia
 */
function formatarData($data) {
    if (empty($data)) {
        return '';
    }
    
    $dataObj = DateTime::createFromFormat('Y-m-d', $data);
    return $dataObj ? $dataObj->format('d/m/Y') : '';
}

/**
 * Calcula idade a partir da data de nascimento
 * 
 * @param string $dataNascimento Data de nascimento no formato Y-m-d
 * @return int Idade em anos
 */
function calcularIdade($dataNascimento) {
    if (empty($dataNascimento)) {
        return 0;
    }
    
    $nascimento = new DateTime($dataNascimento);
    $hoje = new DateTime();
    $idade = $hoje->diff($nascimento);
    
    return $idade->y;
}

/**
 * Verifica se um e-mail já existe no banco de dados
 * 
 * @param PDO $pdo Conexão PDO com o banco
 * @param string $email E-mail a ser verificado
 * @param int|null $idExcluir ID do usuário a excluir da verificação (útil para edição)
 * @return bool Retorna true se o e-mail já existe, false caso contrário
 */
function emailJaExiste($pdo, $email, $idExcluir = null) {
    try {
        // Se tem ID para excluir, não verifica esse registro (útil na edição)
        if ($idExcluir) {
            $sql = "SELECT COUNT(*) FROM usuarios WHERE email = ? AND id != ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$email, $idExcluir]);
        } else {
            $sql = "SELECT COUNT(*) FROM usuarios WHERE email = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$email]);
        }
        
        // Retorna true se encontrou algum registro
        return $stmt->fetchColumn() > 0;
        
    } catch (PDOException $e) {
        return false;
    }
}

/**
 * Valida todos os campos do formulário de usuário
 * 
 * @param array $dados Array com os dados do formulário
 * @param PDO $pdo Conexão PDO com o banco
 * @param int|null $idUsuario ID do usuário (para edição)
 * @return array Array com os erros encontrados (vazio se não houver erros)
 */
function validarFormulario($dados, $pdo, $idUsuario = null) {
    $erros = [];
    
    // Valida nome
    if (campoVazio($dados['nome'])) {
        $erros[] = "O campo Nome é obrigatório.";
    } elseif (strlen($dados['nome']) < 3) {
        $erros[] = "O Nome deve ter no mínimo 3 caracteres.";
    } elseif (strlen($dados['nome']) > 100) {
        $erros[] = "O Nome deve ter no máximo 100 caracteres.";
    }
    
    // Valida e-mail
    if (campoVazio($dados['email'])) {
        $erros[] = "O campo E-mail é obrigatório.";
    } elseif (!validarEmail($dados['email'])) {
        $erros[] = "O E-mail informado não é válido.";
    } elseif (emailJaExiste($pdo, $dados['email'], $idUsuario)) {
        $erros[] = "Este E-mail já está cadastrado no sistema.";
    }
    
    // Valida telefone (opcional)
    if (!campoVazio($dados['telefone']) && !validarTelefone($dados['telefone'])) {
        $erros[] = "O Telefone informado não é válido.";
    }
    
    // Valida data de nascimento (opcional)
    if (!campoVazio($dados['data_nascimento'])) {
        if (!validarData($dados['data_nascimento'])) {
            $erros[] = "A Data de Nascimento não é válida.";
        } elseif (!validarDataNascimento($dados['data_nascimento'])) {
            $erros[] = "A Data de Nascimento não pode ser uma data futura.";
        }
    }
    
    return $erros;
}

/**
 * Registra log de operações (opcional - para auditoria)
 * 
 * @param string $operacao Tipo de operação (INSERT, UPDATE, DELETE)
 * @param string $tabela Nome da tabela
 * @param int $idRegistro ID do registro afetado
 * @param string $detalhes Detalhes adicionais
 */
function registrarLog($operacao, $tabela, $idRegistro, $detalhes = '') {
    // Caminho do arquivo de log
    $arquivoLog = __DIR__ . '/logs/sistema.log';
    
    // Cria o diretório de logs se não existir
    if (!file_exists(__DIR__ . '/logs')) {
        mkdir(__DIR__ . '/logs', 0755, true);
    }
    
    // Monta a mensagem do log
    $dataHora = date('Y-m-d H:i:s');
    $ip = $_SERVER['REMOTE_ADDR'] ?? 'N/A';
    $mensagem = "[$dataHora] [$ip] $operacao em $tabela (ID: $idRegistro) - $detalhes" . PHP_EOL;
    
    // Grava no arquivo
    file_put_contents($arquivoLog, $mensagem, FILE_APPEND);
}

/**
 * Gera token CSRF para proteção de formulários
 * 
 * @return string Token CSRF gerado
 */
function gerarTokenCSRF() {
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Valida token CSRF
 * 
 * @param string $token Token a ser validado
 * @return bool Retorna true se o token for válido, false caso contrário
 */
function validarTokenCSRF($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}
?>
