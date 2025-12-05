<?php
require_once 'config/database.php';

$page_title = 'Dépenses';

// Gestion de l'ajout
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add'])) {
    $description = clean_input($_POST['description']);
    $amount = clean_input($_POST['amount']);
    $date = clean_input($_POST['expense_date']);
    $category_id = !empty($_POST['category_id']) ? clean_input($_POST['category_id']) : null;
    
    if (empty($description) || empty($amount) || empty($date)) {
        $error = "Tous les champs sont obligatoires";
    } elseif (!validate_amount($amount)) {
        $error = "Le montant doit être un nombre positif";
    } elseif (!validate_date($date)) {
        $error = "Date invalide";
    } else {
        $stmt = $pdo->prepare("INSERT INTO expenses (description, amount, expense_date, category_id) VALUES (?, ?, ?, ?)");
        if ($stmt->execute([$description, $amount, $date, $category_id])) {
            $success = "Dépense ajoutée avec succès !";
        } else {
            $error = "Erreur lors de l'ajout";
        }
    }
}

// Gestion de la suppression
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM expenses WHERE id = ?");
    if ($stmt->execute([$id])) {
        $success = "Dépense supprimée avec succès !";
    }
}

// Filtres
$where = "1=1";
$params = [];

if (!empty($_GET['category'])) {
    $where .= " AND category_id = ?";
    $params[] = $_GET['category'];
}

if (!empty($_GET['month'])) {
    $where .= " AND DATE_FORMAT(expense_date, '%Y-%m') = ?";
    $params[] = $_GET['month'];
}

// Récupérer les dépenses avec filtres
$sql = "SELECT e.*, c.name as category_name FROM expenses e 
        LEFT JOIN categories c ON e.category_id = c.id 
        WHERE $where ORDER BY expense_date DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$expenses = $stmt->fetchAll();

// Récupérer les catégories de dépenses
$categories = $pdo->query("SELECT * FROM categories WHERE type = 'expense' ORDER BY name")->fetchAll();

include 'includes/header.php';
?>

<div class="content">
    <h2><i class="fas fa-arrow-down"></i> Gestion des Dépenses</h2>
    
    <?php if (isset($success)): ?>
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i> <?= $success ?>
        </div>
    <?php endif; ?>
    
    <?php if (isset($error)): ?>
        <div class="alert alert-error">
            <i class="fas fa-exclamation-circle"></i> <?= $error ?>
        </div>
    <?php endif; ?>

    <form method="POST" style="background: var(--bg-main); padding: 1.5rem; border-radius: var(--radius); margin-bottom: 2rem;">
        <h3 style="margin-bottom: 1rem;"><i class="fas fa-plus-circle"></i> Ajouter une Dépense</h3>
        
        <div class="form-group">
            <label>Description</label>
            <input type="text" name="description" required>
        </div>
        
        <div class="form-group">
            <label>Montant (DH)</label>
            <input type="number" step="0.01" name="amount" required>
        </div>
        
        <div class="form-group">
            <label>Date</label>
            <input type="date" name="expense_date" value="<?= date('Y-m-d') ?>" required>
        </div>
        
        <div class="form-group">
            <label>Catégorie</label>
            <select name="category_id">
                <option value="">-- Aucune --</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <button type="submit" name="add" class="btn btn-success">
            <i class="fas fa-save"></i> Ajouter
        </button>
    </form>

    <!-- Filtres -->
    <form method="GET" class="filters">
        <select name="category" onchange="this.form.submit()">
            <option value="">Toutes les catégories</option>
            <?php foreach ($categories as $cat): ?>
                <option value="<?= $cat['id'] ?>" <?= isset($_GET['category']) && $_GET['category'] == $cat['id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($cat['name']) ?>
                </option>
            <?php endforeach; ?>
        </select>
        
        <input type="month" name="month" value="<?= $_GET['month'] ?? '' ?>" onchange="this.form.submit()">
        
        <?php if (!empty($_GET['category']) || !empty($_GET['month'])): ?>
            <a href="expenses.php" class="btn btn-small">
                <i class="fas fa-redo"></i> Réinitialiser
            </a>
        <?php endif; ?>
    </form>

    <table>
        <thead>
            <tr>
                <th><i class="fas fa-file-alt"></i> Description</th>
                <th><i class="fas fa-tag"></i> Catégorie</th>
                <th><i class="fas fa-money-bill-wave"></i> Montant</th>
                <th><i class="fas fa-calendar"></i> Date</th>
                <th><i class="fas fa-cog"></i> Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($expenses as $expense): ?>
                <tr>
                    <td><?= htmlspecialchars($expense['description']) ?></td>
                    <td><?= $expense['category_name'] ? htmlspecialchars($expense['category_name']) : '-' ?></td>
                    <td style="color: var(--danger); font-weight: bold;">
                        <?= number_format($expense['amount'], 2) ?> DH
                    </td>
                    <td><?= date('d/m/Y', strtotime($expense['expense_date'])) ?></td>
                    <td>
                        <a href="edit_expense.php?id=<?= $expense['id'] ?>" class="btn btn-small">
                            <i class="fas fa-edit"></i> Modifier
                        </a>
                        <a href="?delete=<?= $expense['id'] ?>" 
                           class="btn btn-danger btn-small" 
                           onclick="return confirm('Confirmer la suppression ?')">
                            <i class="fas fa-trash"></i> Supprimer
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include 'includes/footer.php'; ?>