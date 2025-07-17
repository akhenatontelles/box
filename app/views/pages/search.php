<?php
require_once '../app/config.php';
require_once '../app/core/session_helper.php';
require_once '../app/models/File.php';

if(!isLoggedIn()){ header("location: login.php"); exit(); }

$query = isset($_GET['query']) ? trim($_GET['query']) : '';
$results = [];

if(!empty($query)){
    $fileModel = new File();
    $results = $fileModel->searchFiles($_SESSION['user_id'], $query);
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultados da Busca - <?php echo SITE_NAME; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="assets/style.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="dashboard.php"><?php echo SITE_NAME; ?></a>
            <form class="d-flex" action="search.php" method="GET"><input class="form-control me-2" type="search" name="query" placeholder="Buscar..." value="<?php echo htmlspecialchars($query); ?>"><button class="btn btn-outline-success" type="submit">Buscar</button></form>
            <div class="collapse navbar-collapse"><ul class="navbar-nav ms-auto align-items-center"><li class="nav-item"><button id="theme-toggler" class="btn btn-sm"><i class="fas fa-moon"></i></button></li><?php if($_SESSION['user_role'] == 'admin'): ?><li class="nav-item"><a class="nav-link" href="admin.php">Admin</a></li><?php endif; ?><li class="nav-item"><span class="navbar-text me-3">Bem-vindo, <?php echo $_SESSION['user_name']; ?>!</span></li><li class="nav-item"><a class="btn btn-danger" href="logout.php">Sair</a></li></ul></div>
        </div>
    </nav>

    <div class="container mt-4">
        <h2>Resultados para "<?php echo htmlspecialchars($query); ?>"</h2>
        <a href="dashboard.php" class="btn btn-secondary mb-4">Voltar</a>
        <table class="table table-hover">
            <thead><tr><th>Nome</th><th>Tipo</th><th>Data</th><th>Ações</th></tr></thead>
            <tbody>
                <?php foreach($results as $item): ?>
                <tr>
                    <td>
                        <?php if($item->type == 'folder'): ?><i class="fas fa-folder text-warning me-2"></i><a href="dashboard.php?folder=<?php echo $item->id; ?>"><?php echo htmlspecialchars($item->name); ?></a>
                        <?php else: ?><i class="fas fa-file text-secondary me-2"></i><a href="dashboard.php?folder=<?php echo $item->parent_id; ?>"><?php echo htmlspecialchars($item->name); ?></a><?php endif; ?>
                    </td>
                    <td><?php echo !empty($item->mime_type) ? $item->mime_type : ucfirst($item->type); ?></td>
                    <td><?php echo date('d/m/Y', strtotime($item->created_at)); ?></td>
                    <td>
                        <?php if($item->type == 'file'): ?><a href="download_handler.php?file_id=<?php echo $item->id; ?>" class="btn btn-sm btn-info"><i class="fas fa-download"></i></a><?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if(empty($results)): ?><tr><td colspan="4" class="text-center">Nenhum resultado.</td></tr><?php endif; ?>
            </tbody>
        </table>
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
