<?php
require_once 'config/database.php';

// Définir le titre de la page
$page_title = 'Dashboard';

// Récupérer le total des revenus
$stmt = $pdo->query("SELECT COALESCE(SUM(amount), 0) as total FROM incomes");
$total_incomes = $stmt->fetch()['total'];

// Récupérer le total des dépenses
$stmt = $pdo->query("SELECT COALESCE(SUM(amount), 0) as total FROM expenses");
$total_expenses = $stmt->fetch()['total'];

// Calculer le solde
$balance = $total_incomes - $total_expenses;

// Revenus du mois en cours
$stmt = $pdo->query("SELECT COALESCE(SUM(amount), 0) as total FROM incomes 
                     WHERE MONTH(income_date) = MONTH(CURRENT_DATE()) 
                     AND YEAR(income_date) = YEAR(CURRENT_DATE())");
$monthly_incomes = $stmt->fetch()['total'];

// Dépenses du mois en cours
$stmt = $pdo->query("SELECT COALESCE(SUM(amount), 0) as total FROM expenses 
                     WHERE MONTH(expense_date) = MONTH(CURRENT_DATE()) 
                     AND YEAR(expense_date) = YEAR(CURRENT_DATE())");
$monthly_expenses = $stmt->fetch()['total'];

// Dernières transactions
$stmt = $pdo->query("
    (SELECT 'income' as type, description, amount, income_date as date FROM incomes ORDER BY income_date DESC LIMIT 5)
    UNION ALL
    (SELECT 'expense' as type, description, amount, expense_date as date FROM expenses ORDER BY expense_date DESC LIMIT 5)
    ORDER BY date DESC
    LIMIT 10
");
$recent_transactions = $stmt->fetchAll();

// Inclure le header
include 'includes/header.php';
?>

<!-- Dashboard Cards -->
<div class="dashboard">
    <div class="card income">
        <h3><i class="fas fa-arrow-up"></i> Total Revenus</h3>
        <div class="amount"><?= number_format($total_incomes, 2) ?> DH</div>
    </div>
    
    <div class="card expense">
        <h3><i class="fas fa-arrow-down"></i> Total Dépenses</h3>
        <div class="amount"><?= number_format($total_expenses, 2) ?> DH</div>
    </div>
    
    <div class="card balance">
        <h3><i class="fas fa-wallet"></i> Solde Actuel</h3>
        <div class="amount"><?= number_format($balance, 2) ?> DH</div>
    </div>
</div>

<div class="dashboard">
    <div class="card income">
        <h3><i class="fas fa-calendar-alt"></i> Revenus ce mois</h3>
        <div class="amount"><?= number_format($monthly_incomes, 2) ?> DH</div>
    </div>
    
    <div class="card expense">
        <h3><i class="fas fa-calendar-alt"></i> Dépenses ce mois</h3>
        <div class="amount"><?= number_format($monthly_expenses, 2) ?> DH</div>
    </div>
    
    <div class="card balance">
        <h3><i class="fas fa-chart-pie"></i> Solde du mois</h3>
        <div class="amount"><?= number_format($monthly_incomes - $monthly_expenses, 2) ?> DH</div>
    </div>
</div>



<?php include 'includes/footer.php'; ?>