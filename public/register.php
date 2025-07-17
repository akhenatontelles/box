<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - Capivara Armazenamento</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/style.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="text-center my-3">
                    <button id="theme-toggler" class="btn btn-sm"><i class="fas fa-moon"></i></button>
                </div>
                <div class="card mt-3">
                    <div class="card-header">
                        <h4>Registro de Novo Usuário</h4>
                    </div>
                    <div class="card-body">
                        <form action="register_handler.php" method="POST">
                            <div class="mb-3">
                                <label for="username" class="form-label">Nome de Usuário</label>
                                <input type="text" class="form-control" id="username" name="username" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Senha</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Registrar</button>
                        </form>
                    </div>
                    <div class="card-footer text-muted">
                        Já tem uma conta? <a href="login.php">Faça login</a>
                    </div>
                </div>
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
