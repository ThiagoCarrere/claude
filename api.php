<?php
require_once 'config.php';
header('Content-Type: application/json');

// Função para validar os dados
function validarDados($data) {
    $errors = [];
    
    if (empty($data['nome'])) {
        $errors[] = "O nome é obrigatório";
    }
    
    if (!isset($data['idade']) || !is_numeric($data['idade']) || $data['idade'] < 0) {
        $errors[] = "A idade deve ser um número válido";
    }
    
    return $errors;
}

// Verificar o tipo de operação
$operation = isset($_GET['operation']) ? $_GET['operation'] : '';

switch ($operation) {
    // Listar todos os usuários
    case 'read':
        try {
            $stmt = $pdo->prepare("SELECT * FROM tb_usuario ORDER BY nome");
            $stmt->execute();
            $users = $stmt->fetchAll();
            echo json_encode(['success' => true, 'data' => $users]);
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'message' => 'Erro ao listar usuários: ' . $e->getMessage()]);
        }
        break;
    
    // Obter um usuário específico
    case 'get':
        try {
            $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
            $stmt = $pdo->prepare("SELECT * FROM tb_usuario WHERE id = ?");
            $stmt->execute([$id]);
            $user = $stmt->fetch();
            
            if ($user) {
                echo json_encode(['success' => true, 'data' => $user]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Usuário não encontrado']);
            }
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'message' => 'Erro ao buscar usuário: ' . $e->getMessage()]);
        }
        break;
    
    // Adicionar novo usuário
    case 'create':
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            $errors = validarDados($data);
            
            if (!empty($errors)) {
                echo json_encode(['success' => false, 'message' => implode(", ", $errors)]);
                break;
            }
            
            $stmt = $pdo->prepare("INSERT INTO tb_usuario (nome, idade) VALUES (?, ?)");
            $result = $stmt->execute([$data['nome'], $data['idade']]);
            
            if ($result) {
                echo json_encode(['success' => true, 'message' => 'Usuário adicionado com sucesso']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Erro ao adicionar usuário']);
            }
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'message' => 'Erro ao adicionar usuário: ' . $e->getMessage()]);
        }
        break;
    
    // Atualizar usuário existente
    case 'update':
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            $errors = validarDados($data);
            
            if (!isset($data['id']) || empty($data['id'])) {
                $errors[] = "ID do usuário é obrigatório";
            }
            
            if (!empty($errors)) {
                echo json_encode(['success' => false, 'message' => implode(", ", $errors)]);
                break;
            }
            
            $stmt = $pdo->prepare("UPDATE tb_usuario SET nome = ?, idade = ? WHERE id = ?");
            $result = $stmt->execute([$data['nome'], $data['idade'], $data['id']]);
            
            if ($result) {
                echo json_encode(['success' => true, 'message' => 'Usuário atualizado com sucesso']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Erro ao atualizar usuário']);
            }
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'message' => 'Erro ao atualizar usuário: ' . $e->getMessage()]);
        }
        break;
    
    // Excluir usuário
    case 'delete':
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            
            if (!isset($data['id']) || empty($data['id'])) {
                echo json_encode(['success' => false, 'message' => 'ID do usuário é obrigatório']);
                break;
            }
            
            $stmt = $pdo->prepare("DELETE FROM tb_usuario WHERE id = ?");
            $result = $stmt->execute([$data['id']]);
            
            if ($result) {
                echo json_encode(['success' => true, 'message' => 'Usuário excluído com sucesso']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Erro ao excluir usuário']);
            }
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'message' => 'Erro ao excluir usuário: ' . $e->getMessage()]);
        }
        break;
    
    default:
        echo json_encode(['success' => false, 'message' => 'Operação inválida']);
        break;
}