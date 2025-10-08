<?php
/**
 * =====================================================
 * Sistema CRUD - Página Principal
 * =====================================================
 * Este arquivo contém a interface principal do sistema e processa
 * todas as operações CRUD (Create, Read, Update, Delete)
 */

// Inclui o arquivo de conexão com o banco de dados
require_once 'conexao.php';

// Inicializa variáveis para mensagens e controle
$mensagem = '';
$tipo_mensagem = '';
$editando = false;
$usuario_editando = null;

/**
 * =====================================================
 * PROCESSAMENTO DAS OPERAÇÕES CRUD
 * =====================================================
 */

// Verifica se há uma ação sendo solicitada via POST ou GET
if ($_SERVER['REQUEST_METHOD'] === 'POST' || isset($_GET['acao'])) {
    
    // Obtém a conexão com o banco de dados
    $pdo = getDB();
    
    /**
     * OPERAÇÃO: INSERIR (CREATE)
     * Adiciona um novo registro no banco de dados
     */
    if (isset($_POST['acao']) && $_POST['acao'] === 'inserir') {
        try {
            // Prepara a query SQL com placeholders (?) para evitar SQL Injection
            $sql = "INSERT INTO usuarios (nome, email, telefone, data_nascimento) VALUES (?, ?, ?, ?)";
            
            // Prepara o statement
            $stmt = $pdo->prepare($sql);
            
            // Executa o statement passando os valores do formulário
            // Os valores são automaticamente escapados pelo PDO
            $stmt->execute([
                $_POST['nome'],
                $_POST['email'],
                $_POST['telefone'],
                $_POST['data_nascimento']
            ]);
            
            // Define mensagem de sucesso
            $mensagem = "Usuário cadastrado com sucesso!";
            $tipo_mensagem = "sucesso";
            
        } catch (PDOException $e) {
            // Captura erros (ex: email duplicado, campos obrigatórios vazios)
            $mensagem = "Erro ao cadastrar usuário: " . $e->getMessage();
            $tipo_mensagem = "erro";
        }
    }
    
    /**
     * OPERAÇÃO: ATUALIZAR (UPDATE)
     * Modifica um registro existente no banco de dados
     */
    if (isset($_POST['acao']) && $_POST['acao'] === 'atualizar') {
        try {
            // Prepara a query SQL para atualização
            // WHERE id = ? garante que apenas o registro específico será atualizado
            $sql = "UPDATE usuarios SET nome = ?, email = ?, telefone = ?, data_nascimento = ? WHERE id = ?";
            
            // Prepara o statement
            $stmt = $pdo->prepare($sql);
            
            // Executa passando os novos valores e o ID do registro
            $stmt->execute([
                $_POST['nome'],
                $_POST['email'],
                $_POST['telefone'],
                $_POST['data_nascimento'],
                $_POST['id']
            ]);
            
            // Define mensagem de sucesso
            $mensagem = "Usuário atualizado com sucesso!";
            $tipo_mensagem = "sucesso";
            
        } catch (PDOException $e) {
            // Captura erros de atualização
            $mensagem = "Erro ao atualizar usuário: " . $e->getMessage();
            $tipo_mensagem = "erro";
        }
    }
    
    /**
     * OPERAÇÃO: EXCLUIR (DELETE)
     * Remove um registro do banco de dados
     */
    if (isset($_GET['acao']) && $_GET['acao'] === 'excluir' && isset($_GET['id'])) {
        try {
            // Prepara a query SQL para exclusão
            $sql = "DELETE FROM usuarios WHERE id = ?";
            
            // Prepara o statement
            $stmt = $pdo->prepare($sql);
            
            // Executa passando o ID do registro a ser excluído
            $stmt->execute([$_GET['id']]);
            
            // Define mensagem de sucesso
            $mensagem = "Usuário excluído com sucesso!";
            $tipo_mensagem = "sucesso";
            
        } catch (PDOException $e) {
            // Captura erros de exclusão
            $mensagem = "Erro ao excluir usuário: " . $e->getMessage();
            $tipo_mensagem = "erro";
        }
    }
    
    /**
     * OPERAÇÃO: CARREGAR DADOS PARA EDIÇÃO
     * Busca os dados de um usuário específico para preencher o formulário
     */
    if (isset($_GET['acao']) && $_GET['acao'] === 'editar' && isset($_GET['id'])) {
        try {
            // Prepara a query SQL para buscar um usuário específico
            $sql = "SELECT * FROM usuarios WHERE id = ?";
            
            // Prepara o statement
            $stmt = $pdo->prepare($sql);
            
            // Executa passando o ID
            $stmt->execute([$_GET['id']]);
            
            // Busca o resultado como array associativo
            $usuario_editando = $stmt->fetch();
            
            // Se encontrou o usuário, ativa o modo de edição
            if ($usuario_editando) {
                $editando = true;
            } else {
                $mensagem = "Usuário não encontrado!";
                $tipo_mensagem = "erro";
            }
            
        } catch (PDOException $e) {
            // Captura erros de busca
            $mensagem = "Erro ao buscar usuário: " . $e->getMessage();
            $tipo_mensagem = "erro";
        }
    }
}

/**
 * =====================================================
 * CONSULTA DE TODOS OS REGISTROS (READ)
 * =====================================================
 * Busca todos os usuários cadastrados para exibir na tabela
 */
try {
    // Obtém a conexão
    $pdo = getDB();
    
    // Prepara a query para buscar todos os usuários ordenados por ID decrescente
    $sql = "SELECT * FROM usuarios ORDER BY id DESC";
    
    // Executa a query diretamente (sem parâmetros, não há risco de SQL Injection)
    $stmt = $pdo->query($sql);
    
    // Busca todos os resultados como array de arrays associativos
    $usuarios = $stmt->fetchAll();
    
} catch (PDOException $e) {
    // Captura erros de consulta
    $mensagem = "Erro ao buscar usuários: " . $e->getMessage();
    $tipo_mensagem = "erro";
    $usuarios = [];
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema CRUD - PHP PDO MariaDB</title>
    <!-- Link para o arquivo CSS externo -->
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <!-- Cabeçalho da página -->
        <h1>Sistema CRUD</h1>
        <p class="subtitle">Cadastro de Usuários com PHP, PDO e MariaDB</p>
        
        <?php
        /**
         * Exibe mensagens de feedback para o usuário
         * Mensagens de sucesso ou erro após operações CRUD
         */
        if ($mensagem): ?>
            <div class="mensagem <?php echo $tipo_mensagem; ?>">
                <?php echo $mensagem; ?>
            </div>
        <?php endif; ?>
        
        <!-- ========================================== -->
        <!-- SEÇÃO DO FORMULÁRIO -->
        <!-- ========================================== -->
        <div class="form-section">
            <h2><?php echo $editando ? 'Editar Usuário' : 'Cadastrar Novo Usuário'; ?></h2>
            
            <!-- 
                Formulário para inserção ou edição de dados
                - method="POST": envia dados via POST (mais seguro)
                - action="": envia para a própria página
            -->
            <form method="POST" action="">
                <!-- Campo oculto para identificar a ação (inserir ou atualizar) -->
                <input type="hidden" name="acao" value="<?php echo $editando ? 'atualizar' : 'inserir'; ?>">
                
                <!-- Se estiver editando, inclui o ID do usuário -->
                <?php if ($editando): ?>
                    <input type="hidden" name="id" value="<?php echo $usuario_editando['id']; ?>">
                <?php endif; ?>
                
                <!-- Campo: Nome -->
                <div class="form-group">
                    <label for="nome">Nome Completo *</label>
                    <input 
                        type="text" 
                        id="nome" 
                        name="nome" 
                        required 
                        placeholder="Digite o nome completo"
                        value="<?php echo $editando ? htmlspecialchars($usuario_editando['nome']) : ''; ?>"
                    >
                </div>
                
                <!-- Campo: Email -->
                <div class="form-group">
                    <label for="email">E-mail *</label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        required 
                        placeholder="exemplo@email.com"
                        value="<?php echo $editando ? htmlspecialchars($usuario_editando['email']) : ''; ?>"
                    >
                </div>
                
                <!-- Campo: Telefone -->
                <div class="form-group">
                    <label for="telefone">Telefone</label>
                    <input 
                        type="tel" 
                        id="telefone" 
                        name="telefone" 
                        placeholder="(00) 00000-0000"
                        value="<?php echo $editando ? htmlspecialchars($usuario_editando['telefone']) : ''; ?>"
                    >
                </div>
                
                <!-- Campo: Data de Nascimento -->
                <div class="form-group">
                    <label for="data_nascimento">Data de Nascimento</label>
                    <input 
                        type="date" 
                        id="data_nascimento" 
                        name="data_nascimento"
                        value="<?php echo $editando ? $usuario_editando['data_nascimento'] : ''; ?>"
                    >
                </div>
                
                <!-- Botões de ação -->
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">
                        <?php echo $editando ? 'Atualizar Usuário' : 'Cadastrar Usuário'; ?>
                    </button>
                    
                    <!-- Se estiver editando, mostra botão para cancelar -->
                    <?php if ($editando): ?>
                        <a href="index.php" class="btn btn-secondary">Cancelar Edição</a>
                    <?php endif; ?>
                </div>
            </form>
        </div>
        
        <!-- ========================================== -->
        <!-- SEÇÃO DA TABELA DE LISTAGEM -->
        <!-- ========================================== -->
        <div class="table-section">
            <h2>Usuários Cadastrados</h2>
            
            <div class="table-responsive">
                <?php if (count($usuarios) > 0): ?>
                    <!-- Tabela com todos os usuários -->
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nome</th>
                                <th>E-mail</th>
                                <th>Telefone</th>
                                <th>Data Nascimento</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            /**
                             * Loop para exibir cada usuário cadastrado
                             * htmlspecialchars() previne XSS (Cross-Site Scripting)
                             */
                            foreach ($usuarios as $usuario): ?>
                                <tr>
                                    <td><?php echo $usuario['id']; ?></td>
                                    <td><?php echo htmlspecialchars($usuario['nome']); ?></td>
                                    <td><?php echo htmlspecialchars($usuario['email']); ?></td>
                                    <td><?php echo htmlspecialchars($usuario['telefone']); ?></td>
                                    <td>
                                        <?php 
                                        // Formata a data para o padrão brasileiro (dd/mm/yyyy)
                                        echo $usuario['data_nascimento'] ? 
                                            date('d/m/Y', strtotime($usuario['data_nascimento'])) : 
                                            '-'; 
                                        ?>
                                    </td>
                                    <td>
                                        <!-- Botão para editar -->
                                        <a href="?acao=editar&id=<?php echo $usuario['id']; ?>" 
                                           class="btn btn-warning btn-small">
                                            Editar
                                        </a>
                                        
                                        <!-- Botão para excluir com confirmação JavaScript -->
                                        <a href="?acao=excluir&id=<?php echo $usuario['id']; ?>" 
                                           class="btn btn-danger btn-small"
                                           onclick="return confirm('Tem certeza que deseja excluir este usuário?')">
                                            Excluir
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <!-- Mensagem quando não há usuários cadastrados -->
                    <div class="no-data">
                        <p>Nenhum usuário cadastrado ainda.</p>
                        <p>Utilize o formulário acima para adicionar o primeiro usuário.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <script>
    /**
     * JavaScript para melhorar a experiência do usuário
     */
    
    // Remove a mensagem automaticamente após 5 segundos
    setTimeout(function() {
        var mensagem = document.querySelector('.mensagem');
        if (mensagem) {
            mensagem.style.transition = 'opacity 0.5s';
            mensagem.style.opacity = '0';
            setTimeout(function() {
                mensagem.remove();
            }, 500);
        }
    }, 5000);
    
    // Máscara simples para telefone
    document.getElementById('telefone').addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        if (value.length <= 11) {
            value = value.replace(/^(\d{2})(\d)/g, '($1) $2');
            value = value.replace(/(\d)(\d{4})$/, '$1-$2');
        }
        e.target.value = value;
    });
    </script>
</body>
</html>
