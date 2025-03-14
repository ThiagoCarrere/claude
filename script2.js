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
        backgroundColor: success ? "#4361ee" : "#f72585",
        className: "toastify-custom",
        stopOnFocus: true,
        onClick: function() {} // Callback quando o toast é clicado
    }).showToast();
}

// Função para exibir animação de carregamento
function showLoading() {
    return Toastify({
        text: "Carregando...",
        duration: -1, // Mantém o toast até que seja removido manualmente
        gravity: "top",
        position: "center",
        backgroundColor: "#4361ee",
        className: "loading-toast",
        stopOnFocus: false
    }).showToast();
}

// Função para carregar os dados na tabela
function carregarDados() {
    const loading = showLoading();
    
    $.ajax({
        url: 'api.php?operation=read',
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            loading.hideToast();
            
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
                            className: 'text-center',
                            render: function(data, type, row) {
                                return `
                                    <div class="btn-group">
                                        <button class="btn btn-sm btn-editar" data-id="${row.id}">
                                            <i class="bi bi-pencil-square"></i> Editar
                                        </button>
                                        <button class="btn btn-sm btn-excluir" data-id="${row.id}" data-nome="${row.nome}">
                                            <i class="bi bi-trash"></i> Excluir
                                        </button>
                                    </div>
                                `;
                            }
                        }
                    ],
                    language: {
                        url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/pt-BR.json'
                    },
                    responsive: true,
                    dom: '<"row"<"col-sm-6"l><"col-sm-6"f>>rtip',
                    lengthMenu: [[5, 10, 25, 50, -1], [5, 10, 25, 50, "Todos"]],
                    pageLength: 5,
                    order: [[0, 'desc']]
                });
                
                // Adicionar efeito de fade in nas linhas da tabela
                $('#tabelaUsuarios tbody tr').each(function(index) {
                    $(this).css('opacity', '0');
                    $(this).animate({
                        opacity: '1'
                    }, 200 * (index + 1));
                });
                
            } else {
                showToast(response.message, false);
            }
        },
        error: function(xhr, status, error) {
            loading.hideToast();
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
    
    // Remove classes de erro
    $('.is-invalid').removeClass('is-invalid');
    $('.invalid-feedback').remove();
}

// Função para validar o formulário
function validarFormulario() {
    let isValid = true;
    $('.is-invalid').removeClass('is-invalid');
    $('.invalid-feedback').remove();
    
    // Validação do campo nome
    if (!$('#nome').val().trim()) {
        $('#nome').addClass('is-invalid').after('<div class="invalid-feedback">Por favor, informe um nome válido.</div>');
        isValid = false;
    }
    
    // Validação do campo idade
    const idade = $('#idade').val();
    if (!idade || idade < 0) {
        $('#idade').addClass('is-invalid').after('<div class="invalid-feedback">Por favor, informe uma idade válida.</div>');
        isValid = false;
    }
    
    return isValid;
}

// Função para carregar um usuário para edição
function carregarUsuario(id) {
    const loading = showLoading();
    
    $.ajax({
        url: `api.php?operation=get&id=${id}`,
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            loading.hideToast();
            
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
            loading.hideToast();
            showToast("Erro ao carregar usuário: " + error, false);
        }
    });
}

// Função para salvar (adicionar ou atualizar) um usuário
function salvarUsuario() {
    if (!validarFormulario()) {
        return;
    }
    
    const id = $('#userId').val();
    const nome = $('#nome').val();
    const idade = $('#idade').val();
    
    const data = {
        nome: nome,
        idade: parseInt(idade)
    };
    
    let operation = 'create';
    
    if (id) {
        operation = 'update';
        data.id = id;
    }
    
    const loading = showLoading();
    
    $.ajax({
        url: `api.php?operation=${operation}`,
        type: 'POST',
        dataType: 'json',
        contentType: 'application/json',
        data: JSON.stringify(data),
        success: function(response) {
            loading.hideToast();
            
            if (response.success) {
                showToast(response.message);
                $('#usuarioModal').modal('hide');
                carregarDados();
            } else {
                showToast(response.message, false);
            }
        },
        error: function(xhr, status, error) {
            loading.hideToast();
            showToast("Erro ao salvar usuário: " + error, false);
        }
    });
}

// Função para excluir um usuário
function excluirUsuario(id) {
    const loading = showLoading();
    
    $.ajax({
        url: `api.php?operation=delete`,
        type: 'POST',
        dataType: 'json',
        contentType: 'application/json',
        data: JSON.stringify({ id: id }),
        success: function(response) {
            loading.hideToast();
            
            if (response.success) {
                showToast(response.message);
                carregarDados();
            } else {
                showToast(response.message, false);
            }
        },
        error: function(xhr, status, error) {
            loading.hideToast();
            showToast("Erro ao excluir usuário: " + error, false);
        }
    });
}

// Inicialização e eventos
$(document).ready(function() {
    // Mostrar mensagem de boas-vindas
    setTimeout(function() {
        showToast("Bem-vindo ao Sistema de Gerenciamento de Usuários!");
    }, 1000);
    
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
    
    // Animar botões
    $(document).on('mouseenter', '.btn', function() {
        $(this).addClass('pulse-animation');
    }).on('mouseleave', '.btn', function() {
        $(this).removeClass('pulse-animation');
    });
});