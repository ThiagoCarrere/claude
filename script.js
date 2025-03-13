// Variáveis globais
let tabela;
let usuarioAtual = null;
let deleteUserId = null;

// Função para mostrar mensagens com Toastify
function showToast(message, success = true) {
    Toastify({
        text: message,
        duration: 3000,
        gravity: "top",
        position: "right",
        backgroundColor: success ? "#4CAF50" : "#F44336",
        stopOnFocus: true,
    }).showToast();
}

// Função para carregar os dados na tabela
function carregarDados() {
    $.ajax({
        url: 'api.php?operation=read',
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                if (tabela) {
                    tabela.destroy();
                }
                
                tabela = $('#tabelaUsuarios').DataTable({
                    data: response.data,
                    columns: [
                        { data: 'id' },
                        { data: 'nome' },
                        { data: 'idade' },
                        { 
                            data: null,
                            render: function(data, type, row) {
                                return `
                                    <div class="btn-group">
                                        <button class="btn btn-sm btn-primary btn-editar" data-id="${row.id}">
                                            Editar
                                        </button>
                                        <button class="btn btn-sm btn-danger btn-excluir" data-id="${row.id}" data-nome="${row.nome}">
                                            Excluir
                                        </button>
                                    </div>
                                `;
                            }
                        }
                    ],
                    language: {
                        url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/pt-BR.json'
                    },
                    responsive: true
                });
            } else {
                showToast(response.message, false);
            }
        },
        error: function(xhr, status, error) {
            showToast("Erro ao carregar dados: " + error, false);
        }
    });
}

// Função para limpar o formulário
function limparFormulario() {
    $('#userId').val('');
    $('#nome').val('');
    $('#idade').val('');
    usuarioAtual = null;
}

// Função para carregar um usuário para edição
function carregarUsuario(id) {
    $.ajax({
        url: `api.php?operation=get&id=${id}`,
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                usuarioAtual = response.data;
                $('#userId').val(usuarioAtual.id);
                $('#nome').val(usuarioAtual.nome);
                $('#idade').val(usuarioAtual.idade);
                $('#modalTitle').text('Editar Usuário');
                $('#usuarioModal').modal('show');
            } else {
                showToast(response.message, false);
            }
        },
        error: function(xhr, status, error) {
            showToast("Erro ao carregar usuário: " + error, false);
        }
    });
}

// Função para salvar (adicionar ou atualizar) um usuário
function salvarUsuario() {
    const id = $('#userId').val();
    const nome = $('#nome').val();
    const idade = $('#idade').val();
    
    if (!nome || !idade) {
        showToast("Por favor, preencha todos os campos obrigatórios", false);
        return;
    }
    
    const data = {
        nome: nome,
        idade: parseInt(idade)
    };
    
    let operation = 'create';
    
    if (id) {
        operation = 'update';
        data.id = id;
    }
    
    $.ajax({
        url: `api.php?operation=${operation}`,
        type: 'POST',
        dataType: 'json',
        contentType: 'application/json',
        data: JSON.stringify(data),
        success: function(response) {
            if (response.success) {
                showToast(response.message);
                $('#usuarioModal').modal('hide');
                carregarDados();
            } else {
                showToast(response.message, false);
            }
        },
        error: function(xhr, status, error) {
            showToast("Erro ao salvar usuário: " + error, false);
        }
    });
}

// Função para excluir um usuário
function excluirUsuario(id) {
    $.ajax({
        url: `api.php?operation=delete`,
        type: 'POST',
        dataType: 'json',
        contentType: 'application/json',
        data: JSON.stringify({ id: id }),
        success: function(response) {
            if (response.success) {
                showToast(response.message);
                carregarDados();
            } else {
                showToast(response.message, false);
            }
        },
        error: function(xhr, status, error) {
            showToast("Erro ao excluir usuário: " + error, false);
        }
    });
}

// Inicialização e eventos
$(document).ready(function() {
    // Carregar dados iniciais
    carregarDados();
    
    // Abrir modal para novo usuário
    $('#btnNovoUsuario').click(function() {
        limparFormulario();
        $('#modalTitle').text('Novo Usuário');
        $('#usuarioModal').modal('show');
    });
    
    // Evento de clique no botão de editar
    $(document).on('click', '.btn-editar', function() {
        const id = $(this).data('id');
        carregarUsuario(id);
    });
    
    // Evento de clique no botão de excluir
    $(document).on('click', '.btn-excluir', function() {
        deleteUserId = $(this).data('id');
        const nome = $(this).data('nome');
        $('#deleteUserInfo').text(`Usuário: ${nome}`);
        $('#confirmDeleteModal').modal('show');
    });
    
    // Evento de clique no botão de confirmação de exclusão
    $('#btnConfirmDelete').click(function() {
        if (deleteUserId) {
            excluirUsuario(deleteUserId);
            $('#confirmDeleteModal').modal('hide');
            deleteUserId = null;
        }
    });
    
    // Evento de clique no botão de salvar
    $('#btnSalvar').click(function() {
        salvarUsuario();
    });
    
    // Permitir envio do formulário pressionando Enter
    $('#formUsuario').on('keypress', function(e) {
        if (e.which === 13) {
            e.preventDefault();
            salvarUsuario();
        }
    });
});