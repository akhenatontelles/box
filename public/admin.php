<?php
require_once '../app/config.php';
require_once '../app/core/session_helper.php';
require_once '../app/models/User.php';

if(!isLoggedIn() || $_SESSION['user_role'] !== 'admin'){ header("location: dashboard.php"); exit(); }

$userModel = new User();
$users = $userModel->getAllUsers();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - <?php echo SITE_NAME; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="assets/style.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="admin.php">Admin - <?php echo SITE_NAME; ?></a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item"><button id="theme-toggler" class="btn btn-sm btn-outline-light"><i class="fas fa-moon"></i></button></li>
                    <li class="nav-item"><a class="nav-link" href="dashboard.php">Dashboard</a></li>
                    <li class="nav-item"><a class="btn btn-danger" href="logout.php">Sair</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Gerenciamento de Usuários</h2>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createUserModal">
                <i class="fas fa-user-plus me-2"></i>Criar Usuário
            </button>
        </div>

        <table class="table table-striped">
            <thead><tr><th>ID</th><th>Username</th><th>Email</th><th>Role</th><th>Criação</th><th>Ações</th></tr></thead>
            <tbody>
                <?php foreach($users as $user): ?>
                <tr>
                    <th><?php echo $user->id; ?></th>
                    <td><?php echo htmlspecialchars($user->username); ?></td>
                    <td><?php echo htmlspecialchars($user->email); ?></td>
                    <td><span class="badge bg-<?php echo $user->role == 'admin' ? 'success' : 'secondary'; ?>"><?php echo $user->role; ?></span></td>
                    <td><?php echo date('d/m/Y', strtotime($user->created_at)); ?></td>
                    <td>
                        <a href="admin_impersonate_handler.php?user_id=<?php echo $user->id; ?>" class="btn btn-sm btn-info" title="Gerenciar Arquivos"><i class="fas fa-folder-open"></i></a>
                        <button class="btn btn-sm btn-warning" title="Editar (Não implementado)"><i class="fas fa-edit"></i></button>
                        <button class="btn btn-sm btn-danger" title="Excluir (Não implementado)"><i class="fas fa-trash"></i></button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Modal Criar Usuário -->
    <div class="modal fade" id="createUserModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="admin_create_user_handler.php" method="POST">
                    <div class="modal-header"><h5 class="modal-title">Criar Novo Usuário</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                    <div class="modal-body">
                        <div class="mb-3"><label for="username" class="form-label">Nome de Usuário</label><input type="text" class="form-control" name="username" required></div>
                        <div class="mb-3"><label for="email" class="form-label">Email</label><input type="email" class="form-control" name="email" required></div>
                        <div class="mb-3"><label for="password" class="form-label">Senha</label><input type="password" class="form-control" name="password" required></div>
                        <div class="mb-3"><label for="role" class="form-label">Role</label><select class="form-select" name="role"><option value="user">User</option><option value="admin">Admin</option></select></div>
                    </div>
                    <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button><button type="submit" class="btn btn-primary">Criar</button></div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Theme Toggler
        const themeToggler = document.getElementById('theme-toggler');
        const currentTheme = localStorage.getItem('theme');
        const sunIcon = '<i class="fas fa-sun"></i>';
        const moonIcon = '<i class="fas fa-moon"></i>';
        if (currentTheme) {
            document.documentElement.setAttribute('data-bs-theme', currentTheme);
            if (currentTheme === 'dark') themeToggler.innerHTML = sunIcon;
        }
        themeToggler.addEventListener('click', () => {
            let theme = document.documentElement.getAttribute('data-bs-theme');
            if (theme === 'dark') {
                document.documentElement.removeAttribute('data-bs-theme');
                localStorage.removeItem('theme');
                themeToggler.innerHTML = moonIcon;
            } else {
                document.documentElement.setAttribute('data-bs-theme', 'dark');
                localStorage.setItem('theme', 'dark');
                themeToggler.innerHTML = sunIcon;
            }
        });
    </script>
</body>
</html>
