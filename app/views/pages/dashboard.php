<?php
// A lógica do controller para buscar dados já teria sido executada
// e os dados estariam disponíveis na variável $data
$items = $data['items'];
$breadcrumbs = $data['breadcrumbs'];
$current_folder_id = $data['current_folder_id'];
$isImpersonating = isset($_SESSION['admin_id']);

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
    <link href="<?php echo URL_ROOT; ?>/assets/style.css" rel="stylesheet">
</head>
<body>
    <!-- Navbar ... -->

    <div class="container mt-4">
        <!-- Alerta de Personificação e Breadcrumbs ... -->

        <table class="table table-hover">
            <!-- ... thead ... -->
            <tbody id="file-list">
                <?php foreach($items as $item): ?>
                <tr class="file-item"
                    data-id="<?php echo $item->id; ?>"
                    data-name="<?php echo htmlspecialchars($item->name); ?>"
                    data-type="<?php echo $item->type; ?>"
                    data-mime="<?php echo $item->mime_type; ?>">
                    <!-- ... Td's com nome, tipo, etc. ... -->
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Context Menu (HTML) -->
    <div id="context-menu">
        <ul class="list-group">
            <li class="list-group-item" id="ctx-open"><i class="fas fa-folder-open me-2"></i>Abrir</li>
            <li class="list-group-item" id="ctx-preview"><i class="fas fa-eye me-2"></i>Visualizar</li>
            <li class="list-group-item" id="ctx-download"><i class="fas fa-download me-2"></i>Baixar</li>
            <li class="list-group-item" id="ctx-rename"><i class="fas fa-edit me-2"></i>Renomear</li>
            <li class="list-group-item" id="ctx-share"><i class="fas fa-share-alt me-2"></i>Compartilhar</li>
            <li class="list-group-item text-danger" id="ctx-delete"><i class="fas fa-trash me-2"></i>Excluir</li>
        </ul>
    </div>

    <!-- Modais (com action URLs atualizadas) -->
    <div class="modal fade" id="renameModal"><div class="modal-dialog"><div class="modal-content"><form action="<?php echo URL_ROOT; ?>/files/rename" method="POST"> ... </form></div></div></div>
    <div class="modal fade" id="deleteModal"><div class="modal-dialog"><div class="modal-content"><form action="<?php echo URL_ROOT; ?>/files/delete" method="POST"> ... </form></div></div></div>
    <!-- Outros modais -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Lógica do Tema ...

        const contextMenu = document.getElementById('context-menu');
        const fileList = document.getElementById('file-list');
        let currentItem = null;

        fileList.addEventListener('contextmenu', (e) => {
            const targetItem = e.target.closest('.file-item');
            if (targetItem) {
                e.preventDefault();
                currentItem = targetItem;

                // Mostrar/ocultar opções com base no tipo de item
                const type = currentItem.dataset.type;
                const mime = currentItem.dataset.mime;
                document.getElementById('ctx-open').style.display = type === 'folder' ? 'block' : 'none';
                document.getElementById('ctx-download').style.display = type === 'file' ? 'block' : 'none';
                document.getElementById('ctx-preview').style.display = type === 'file' && isPreviewable(mime) ? 'block' : 'none';

                contextMenu.style.display = 'block';
                contextMenu.style.left = e.pageX + 'px';
                contextMenu.style.top = e.pageY + 'px';
            }
        });

        // Ocultar menu de contexto ao clicar em qualquer outro lugar
        window.addEventListener('click', () => {
            contextMenu.style.display = 'none';
            currentItem = null;
        });

        // Lógica para as opções do menu
        document.getElementById('ctx-open').addEventListener('click', () => {
            if (currentItem) window.location.href = `<?php echo URL_ROOT; ?>/pages/dashboard/${currentItem.dataset.id}`;
        });
        document.getElementById('ctx-download').addEventListener('click', () => {
            if (currentItem) window.location.href = `<?php echo URL_ROOT; ?>/files/download/${currentItem.dataset.id}`;
        });
        document.getElementById('ctx-rename').addEventListener('click', () => {
             if (currentItem) {
                // Acionar o modal de renomear (lógica já existente)
                document.getElementById('renameItemId').value = currentItem.dataset.id;
                document.getElementById('newItemName').value = currentItem.dataset.name;
                new bootstrap.Modal(document.getElementById('renameModal')).show();
            }
        });
        document.getElementById('ctx-delete').addEventListener('click', () => {
            if (currentItem) {
                 // Acionar o modal de exclusão (lógica já existente)
                document.getElementById('deleteItemId').value = currentItem.dataset.id;
                document.getElementById('deleteItemName').textContent = currentItem.dataset.name;
                new bootstrap.Modal(document.getElementById('deleteModal')).show();
            }
        });

        function isPreviewable(mime) {
            return ['image/jpeg', 'image/png', 'image/gif', 'application/pdf', 'text/plain'].includes(mime);
        }

    </script>
</body>
</html>
