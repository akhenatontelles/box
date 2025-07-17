<?php
require_once '../app/config.php';
require_once '../app/core/session_helper.php';
require_once '../app/models/File.php';

if(!isLoggedIn()){ header("location: login.php"); exit(); }

$fileModel = new File();
$userId = $_SESSION['user_id'];
$current_folder_id = isset($_GET['folder']) ? (int)$_GET['folder'] : null;
$items = $fileModel->getFilesByUserIdAndParent($userId, $current_folder_id);
$breadcrumbs = $fileModel->getFolderPath($current_folder_id, $userId);

function is_previewable($mime_type) {
    $previewable_mimes = ['image/jpeg', 'image/png', 'image/gif', 'application/pdf', 'text/plain'];
    return in_array($mime_type, $previewable_mimes);
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - <?php echo SITE_NAME; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="assets/style.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="dashboard.php"><?php echo SITE_NAME; ?></a>
            <form class="d-flex" action="search.php" method="GET"><input class="form-control me-2" type="search" name="query" placeholder="Buscar arquivos..."><button class="btn btn-outline-success" type="submit">Buscar</button></form>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item"><button id="theme-toggler" class="btn btn-sm"><i class="fas fa-moon"></i></button></li>
                    <?php if($_SESSION['user_role'] == 'admin'): ?><li class="nav-item"><a class="nav-link" href="admin.php">Painel Admin</a></li><?php endif; ?>
                    <li class="nav-item"><span class="navbar-text me-3">Bem-vindo, <?php echo $_SESSION['user_name']; ?>!</span></li>
                    <li class="nav-item"><a class="btn btn-danger" href="logout.php">Sair</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="dashboard.php">Início</a></li><?php foreach ($breadcrumbs as $b): ?><li class="breadcrumb-item"><a href="dashboard.php?folder=<?php echo $b->id; ?>"><?php echo htmlspecialchars($b->name); ?></a></li><?php endforeach; ?></ol></nav>
        <div class="d-flex justify-content-between align-items-center mb-4"><h2>Seus Arquivos</h2><div><button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#newFolderModal"><i class="fas fa-folder-plus me-2"></i>Criar Pasta</button><button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#uploadFileModal"><i class="fas fa-file-upload me-2"></i>Fazer Upload</button></div></div>
        <table class="table table-hover">
            <thead><tr><th>Nome</th><th>Tipo</th><th>Data de Criação</th><th>Ações</th></tr></thead>
            <tbody>
                <?php foreach($items as $item): ?>
                <tr>
                    <td>
                        <?php if($item->type == 'folder'): ?><i class="fas fa-folder text-warning me-2"></i><a href="dashboard.php?folder=<?php echo $item->id; ?>"><?php echo htmlspecialchars($item->name); ?></a>
                        <?php else: ?><i class="fas fa-file text-secondary me-2"></i><?php echo htmlspecialchars($item->name); ?><?php endif; ?>
                    </td>
                    <td><?php echo !empty($item->mime_type) ? $item->mime_type : ucfirst($item->type); ?></td>
                    <td><?php echo date('d/m/Y H:i', strtotime($item->created_at)); ?></td>
                    <td>
                        <button class="btn btn-sm btn-warning renameBtn" data-id="<?php echo $item->id; ?>" data-name="<?php echo htmlspecialchars($item->name); ?>"><i class="fas fa-edit"></i></button>
                        <button class="btn btn-sm btn-danger deleteBtn" data-id="<?php echo $item->id; ?>" data-name="<?php echo htmlspecialchars($item->name); ?>"><i class="fas fa-trash"></i></button>
                        <?php if($item->type == 'file'): ?>
                        <a href="download_handler.php?file_id=<?php echo $item->id; ?>" class="btn btn-sm btn-info"><i class="fas fa-download"></i></a>
                        <?php if(is_previewable($item->mime_type)): ?><a href="preview_handler.php?file_id=<?php echo $item->id; ?>" class="btn btn-sm btn-secondary" target="_blank"><i class="fas fa-eye"></i></a><?php endif; ?>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if(empty($items)): ?><tr><td colspan="4" class="text-center">Nenhum item encontrado.</td></tr><?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Modais -->
    <div class="modal fade" id="renameModal" tabindex="-1"><div class="modal-dialog"><div class="modal-content"><form action="rename_handler.php" method="POST"><div class="modal-header"><h5 class="modal-title">Renomear</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div><div class="modal-body"><input type="hidden" name="item_id" id="renameItemId"><input type="hidden" name="parent_id" value="<?php echo $current_folder_id; ?>"><input type="text" class="form-control" name="newItemName" id="newItemName" required></div><div class="modal-footer"><button type="submit" class="btn btn-primary">Salvar</button></div></form></div></div></div>
    <div class="modal fade" id="deleteModal" tabindex="-1"><div class="modal-dialog"><div class="modal-content"><form action="delete_handler.php" method="POST"><div class="modal-header"><h5 class="modal-title">Excluir</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div><div class="modal-body"><input type="hidden" name="item_id" id="deleteItemId"><input type="hidden" name="parent_id" value="<?php echo $current_folder_id; ?>"><p>Excluir <strong id="deleteItemName"></strong>?</p></div><div class="modal-footer"><button type="submit" class="btn btn-danger">Excluir</button></div></form></div></div></div>
    <div class="modal fade" id="newFolderModal" tabindex="-1"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><h5 class="modal-title">Nova Pasta</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div><div class="modal-body"><form action="create_folder_handler.php" method="POST"><input type="text" class="form-control" name="folderName" required><input type="hidden" name="parent_id" value="<?php echo $current_folder_id; ?>"><button type="submit" class="btn btn-primary mt-2">Criar</button></form></div></div></div></div>
    <div class="modal fade" id="uploadFileModal" tabindex="-1"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><h5 class="modal-title">Upload</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div><div class="modal-body"><form action="upload_handler.php" method="POST" enctype="multipart/form-data"><input type="file" class="form-control" name="filesToUpload[]" multiple required><input type="hidden" name="parent_id" value="<?php echo $current_folder_id; ?>"><button type="submit" class="btn btn-primary mt-2">Enviar</button></form></div></div></div></div>

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

        // Modals Logic
        var renameModal = new bootstrap.Modal(document.getElementById('renameModal'));
        var deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
        document.querySelectorAll('.renameBtn').forEach(b => b.addEventListener('click', e => {
            e.preventDefault();
            document.getElementById('renameItemId').value = b.dataset.id;
            document.getElementById('newItemName').value = b.dataset.name;
            renameModal.show();
        }));
        document.querySelectorAll('.deleteBtn').forEach(b => b.addEventListener('click', e => {
            e.preventDefault();
            document.getElementById('deleteItemId').value = b.dataset.id;
            document.getElementById('deleteItemName').textContent = b.dataset.name;
            deleteModal.show();
        }));
    </script>
</body>
</html>
