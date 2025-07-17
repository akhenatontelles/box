<?php
// ... (lógica de carregamento de dados) ...
$users = $data['users'];
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <!-- ... (head content) ... -->
    <link href="<?php echo URL_ROOT; ?>/assets/style.css" rel="stylesheet">
</head>
<body>
    <!-- ... (navbar) ... -->
    <div class="container mt-4">
        <!-- ... (cabeçalho e botão de criar usuário) ... -->
        <table class="table table-striped">
            <!-- ... (thead) ... -->
            <tbody>
                <?php foreach($users as $user): ?>
                <tr>
                    <!-- ... (tds com dados do usuário) ... -->
                    <td>
                        <a href="<?php echo URL_ROOT; ?>/admin/impersonate/<?php echo $user->id; ?>" class="btn btn-sm btn-info" title="Gerenciar Arquivos"><i class="fas fa-folder-open"></i></a>
                        <button class="btn btn-sm btn-warning editUserBtn" data-id="<?php echo $user->id; ?>" data-username="<?php echo htmlspecialchars($user->username); ?>" data-email="<?php echo htmlspecialchars($user->email); ?>" data-role="<?php echo $user->role; ?>"><i class="fas fa-edit"></i></button>
                        <button class="btn btn-sm btn-danger deleteUserBtn" data-id="<?php echo $user->id; ?>" data-username="<?php echo htmlspecialchars($user->username); ?>"><i class="fas fa-trash"></i></button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- ... (Modal Criar Usuário) ... -->

    <!-- Modal Excluir Usuário -->
    <div class="modal fade" id="deleteUserModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="deleteUserForm" action="" method="POST">
                    <div class="modal-header"><h5 class="modal-title">Excluir Usuário</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                    <div class="modal-body">
                        <p>Você tem certeza que deseja excluir o usuário <strong id="deleteUsername"></strong>?</p>
                        <p class="text-danger">Atenção: Todos os arquivos deste usuário também serão permanentemente excluídos. Esta ação não pode ser desfeita.</p>
                    </div>
                    <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button><button type="submit" class="btn btn-danger">Excluir</button></div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Editar Usuário (a ser implementado) -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // ... (Theme Toggler) ...

        // Lógica para o modal de exclusão
        const deleteUserModal = new bootstrap.Modal(document.getElementById('deleteUserModal'));
        document.querySelectorAll('.deleteUserBtn').forEach(button => {
            button.addEventListener('click', function () {
                const userId = this.dataset.id;
                const username = this.dataset.username;
                document.getElementById('deleteUsername').textContent = username;
                const form = document.getElementById('deleteUserForm');
                form.action = `<?php echo URL_ROOT; ?>/users/deleteUser/${userId}`;
                deleteUserModal.show();
            });
        });
    </script>
</body>
</html>
