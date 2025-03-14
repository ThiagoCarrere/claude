<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Gerenciamento de Usuários</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <!-- Toastify CSS -->
    <link href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #4361ee;
            --secondary-color: #3f37c9;
            --accent-color: #4895ef;
            --success-color: #4cc9f0;
            --warning-color: #f72585;
            --light-color: #f8f9fa;
            --dark-color: #212529;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f0f2f5;
            color: #333;
            margin: 0;
            padding: 0;
        }
        
        .container {
            padding-top: 2rem;
            padding-bottom: 2rem;
        }
        
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            margin-bottom: 2rem;
        }
        
        .card-header {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            border-bottom: none;
            padding: 1.5rem 2rem;
        }
        
        .card-body {
            padding: 2rem;
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            padding: 0.6rem 1.5rem;
            border-radius: 50px;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(67, 97, 238, 0.3);
        }
        
        .btn-light {
            background-color: white;
            color: var(--primary-color);
            border: none;
            padding: 0.6rem 1.5rem;
            border-radius: 50px;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .btn-light:hover {
            background-color: #f0f0f0;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255, 255, 255, 0.3);
        }
        
        .btn-danger {
            background-color: var(--warning-color);
            border-color: var(--warning-color);
            padding: 0.6rem 1.5rem;
            border-radius: 50px;
            font-weight: 500;
        }
        
        .btn-danger:hover {
            background-color: #d61f69;
            border-color: #d61f69;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(247, 37, 133, 0.3);
        }
        
        .btn-secondary {
            background-color: #6c757d;
            border-color: #6c757d;
            padding: 0.6rem 1.5rem;
            border-radius: 50px;
            font-weight: 500;
        }
        
        .btn-secondary:hover {
            background-color: #5c636a;
            border-color: #5c636a;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(108, 117, 125, 0.3);
        }
        
        .btn-sm {
            padding: 0.4rem 1rem;
            font-size: 0.875rem;
        }
        
        .table {
            border-collapse: separate;
            border-spacing: 0;
            width: 100%;
        }
        
        .table th {
            background-color: #f8f9fa;
            color: #333;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.5px;
            padding: 1rem;
        }
        
        .table td {
            vertical-align: middle;
            padding: 1rem;
            font-size: 0.9rem;
        }
        
        .table tbody tr {
            transition: all 0.3s ease;
        }
        
        .table tbody tr:hover {
            background-color: rgba(67, 97, 238, 0.05);
        }
        
        .btn-group {
            gap: 0.5rem;
            display: flex;
        }
        
        .form-control {
            border-radius: 8px;
            padding: 0.75rem 1rem;
            border: 1px solid #ced4da;
            transition: all 0.3s ease;
        }
        
        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.25rem rgba(67, 97, 238, 0.25);
        }
        
        .form-label {
            font-weight: 500;
            color: #495057;
            margin-bottom: 0.5rem;
        }
        
        .modal-content {
            border-radius: 15px;
            border: none;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        
        .modal-header {
            padding: 1.5rem 2rem;
            border-bottom: 1px solid #e9ecef;
        }
        
        .modal-body {
            padding: 2rem;
        }
        
        .modal-footer {
            padding: 1.5rem 2rem;
            border-top: 1px solid #e9ecef;
        }
        
        .modal-title {
            font-weight: 600;
            color: #333;
        }
        
        .header-title {
            font-size: 1.5rem;
            font-weight: 600;
            margin: 0;
        }
        
        .header-action {
            display: flex;
            align-items: center;
        }
        
        .header-icon {
            margin-right: 0.5rem;
        }
        
        .dataTables_wrapper .dataTables_length, 
        .dataTables_wrapper .dataTables_filter, 
        .dataTables_wrapper .dataTables_info, 
        .dataTables_wrapper .dataTables_processing, 
        .dataTables_wrapper .dataTables_paginate {
            color: #495057;
            margin-bottom: 1rem;
        }
        
        .dataTables_wrapper .dataTables_paginate .paginate_button.current, 
        .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
            background: var(--primary-color);
            color: white !important;
            border: 1px solid var(--primary-color);
            border-radius: 50px;
        }
        
        .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
            background: #f0f0f0;
            color: var(--primary-color) !important;
            border: 1px solid #f0f0f0;
            border-radius: 50px;
        }
        
        .dataTables_wrapper .dataTables_paginate .paginate_button {
            border-radius: 50px;
            margin: 0 3px;
        }
        
        .btn-editar {
            background-color: var(--primary-color);
            color: white;
        }
        
        .btn-excluir {
            background-color: var(--warning-color);
            color: white;
        }
        
        .page-title {
            color: var(--primary-color);
            font-weight: 700;
            margin-bottom: 1.5rem;
        }
        
        .footer {
            text-align: center;
            padding: 1.5rem 0;
            color: #6c757d;
            font-size: 0.9rem;
        }
        
        /* Toastify personalizado */
        .Toastify__toast {
            border-radius: 8px !important;
        }
    </style>
</head>
<body>
    <div class="container py-5">
        <h1 class="page-title text-center mb-4">Sistema de Gerenciamento de Usuários</h1>
        
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="header-title">Cadastro de Usuários</h3>
                <button class="btn btn-light" id="btnNovoUsuario">
                    <i class="bi bi-plus-circle header-icon"></i> Novo Usuário
                </button>
            </div>
            <div class="card-body">
                <table id="tabelaUsuarios" class="table table-striped table-hover" style="width:100%">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nome</th>
                            <th>Idade</th>
                            <th class="text-center">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Dados serão carregados via AJAX -->
                    </tbody>
                </table>
            </div>
        </div>
        
        <div class="footer">
            <p>&copy; 2025 Sistema de Gerenciamento de Usuários. Todos os direitos reservados.</p>
        </div>
    </div>

    <!-- Modal para Adicionar/Editar Usuário -->
    <div class="modal fade" id="usuarioModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Novo Usuário</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <div class="modal-body">
                    <form id="formUsuario">
                        <input type="hidden" id="userId">
                        <div class="mb-3">
                            <label for="nome" class="form-label">Nome</label>
                            <input type="text" class="form-control" id="nome" required>
                        </div>
                        <div class="mb-3">
                            <label for="idade" class="form-label">Idade</label>
                            <input type="number" class="form-control" id="idade" min="0" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" id="btnSalvar">
                        <i class="bi bi-save"></i> Salvar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Confirmação para Excluir -->
    <div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">Confirmar Exclusão</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-4">
                        <i class="bi bi-exclamation-triangle text-danger" style="font-size: 3rem;"></i>
                    </div>
                    <p class="text-center">Tem certeza que deseja excluir este usuário?</p>
                    <p class="text-center fw-bold" id="deleteUserInfo"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-danger" id="btnConfirmDelete">
                        <i class="bi bi-trash"></i> Excluir
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap, jQuery, DataTables e Toastify JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    
    <!-- Script customizado -->
    <script src="script2.js"></script>
</body>
</html>