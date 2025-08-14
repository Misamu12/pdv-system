<?php
require_once 'config/database.php';
require_once 'includes/auth.php';

$database = new Database();
$db = $database->getConnection();
$auth = new Auth($db);

// Redirection si non connecté
if (!$auth->isLoggedIn()) {
    header("Location: login.php");
    exit();
}

$user = $auth->getCurrentUser();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Système PDV - <?php echo htmlspecialchars($user['name']); ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div id="alerts" style="position: fixed; top: 1rem; right: 1rem; z-index: 1001;"></div>
    
    <header class="bg-primary text-white p-4">
        <div class="container flex justify-between items-center">
            <h1 class="text-2xl font-bold">Système PDV</h1>
            <div class="flex items-center gap-4">
                <span>Bonjour, <?php echo htmlspecialchars($user['name']); ?></span>
                <span class="badge badge-primary"><?php echo ucfirst($user['role']); ?></span>
                <a href="logout.php" class="btn btn-secondary">Déconnexion</a>
            </div>
        </div>
    </header>

    <main class="container py-6">
        <!-- Navigation par onglets -->
        <div class="nav-tabs">
            <?php if ($auth->hasAnyRole(['admin', 'manager'])): ?>
                <button class="nav-tab active" data-view="dashboard">Tableau de bord</button>
            <?php endif; ?>
            
            <?php if ($auth->hasRole('admin')): ?>
                <button class="nav-tab" data-view="admin">Administration</button>
            <?php endif; ?>
            
            <?php if ($auth->hasAnyRole(['admin', 'manager'])): ?>
                <button class="nav-tab" data-view="manager">Management</button>
            <?php endif; ?>
            
            <?php if ($auth->hasAnyRole(['admin', 'manager', 'cashier'])): ?>
                <button class="nav-tab" data-view="pos">Point de Vente</button>
                <button class="nav-tab" data-view="stock">Stock</button>
            <?php endif; ?>
            
            <?php if ($auth->hasRole('client')): ?>
                <button class="nav-tab active" data-view="client">Mon Compte</button>
            <?php endif; ?>
        </div>

        <!-- Vue Tableau de bord -->
        <?php if ($auth->hasAnyRole(['admin', 'manager'])): ?>
        <div id="dashboard-view" class="view">
            <div class="grid grid-cols-4 gap-6 mb-6">
                <div class="card">
                    <div class="card-content">
                        <h3 class="text-lg font-semibold mb-2">Ventes du jour</h3>
                        <p class="text-3xl font-bold text-primary" id="daily-sales">0€</p>
                    </div>
                </div>
                <div class="card">
                    <div class="card-content">
                        <h3 class="text-lg font-semibold mb-2">Transactions</h3>
                        <p class="text-3xl font-bold text-success" id="daily-transactions">0</p>
                    </div>
                </div>
                <div class="card">
                    <div class="card-content">
                        <h3 class="text-lg font-semibold mb-2">Produits vendus</h3>
                        <p class="text-3xl font-bold text-warning" id="products-sold">0</p>
                    </div>
                </div>
                <div class="card">
                    <div class="card-content">
                        <h3 class="text-lg font-semibold mb-2">Stock faible</h3>
                        <p class="text-3xl font-bold text-destructive" id="low-stock">0</p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Dernières ventes</h3>
                    </div>
                    <div class="card-content">
                        <div id="recent-sales">Chargement...</div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Alertes stock</h3>
                    </div>
                    <div class="card-content">
                        <div id="stock-alerts">Chargement...</div>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Vue Administration -->
        <?php if ($auth->hasRole('admin')): ?>
        <div id="admin-view" class="view" style="display: none;">
            <div class="grid grid-cols-3 gap-6 mb-6">
                <div class="card">
                    <div class="card-content text-center">
                        <h3 class="text-lg font-semibold mb-4">Gestion des utilisateurs</h3>
                        <button class="btn btn-primary" data-modal="user-modal">Ajouter un utilisateur</button>
                    </div>
                </div>
                <div class="card">
                    <div class="card-content text-center">
                        <h3 class="text-lg font-semibold mb-4">Gestion des boutiques</h3>
                        <button class="btn btn-primary" data-modal="store-modal">Ajouter une boutique</button>
                    </div>
                </div>
                <div class="card">
                    <div class="card-content text-center">
                        <h3 class="text-lg font-semibold mb-4">Paramètres système</h3>
                        <button class="btn btn-primary">Configurer</button>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Liste des utilisateurs</h3>
                </div>
                <div class="card-content">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Nom</th>
                                <th>Email</th>
                                <th>Rôle</th>
                                <th>Boutique</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="users-table">
                            <!-- Chargé via JavaScript -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Vue Point de Vente -->
        <?php if ($auth->hasAnyRole(['admin', 'manager', 'cashier'])): ?>
        <div id="pos-view" class="view" style="display: none;">
            <div class="grid grid-cols-3 gap-6">
                <!-- Produits -->
                <div class="col-span-2">
                    <div class="card">
                        <div class="card-header">
                            <div class="flex justify-between items-center">
                                <h3 class="card-title">Produits</h3>
                                <div class="flex gap-2">
                                    <input type="text" id="product-search" class="input" placeholder="Rechercher un produit...">
                                    <input type="text" id="barcode-input" class="input" placeholder="Scanner code-barres">
                                </div>
                            </div>
                        </div>
                        <div class="card-content">
                            <div id="products-grid" class="grid grid-cols-3 gap-4">
                                <!-- Chargé via JavaScript -->
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Panier -->
                <div>
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Panier</h3>
                        </div>
                        <div class="card-content">
                            <div id="cart-items" class="mb-4">
                                <!-- Articles du panier -->
                            </div>
                            <div class="border-t pt-4">
                                <div class="flex justify-between items-center mb-4">
                                    <span class="font-semibold">Total:</span>
                                    <span class="text-xl font-bold" id="cart-total">0€</span>
                                </div>
                                <div class="grid grid-cols-2 gap-2">
                                    <button class="btn btn-success" onclick="pdvApp.processSale('cash')">Espèces</button>
                                    <button class="btn btn-primary" onclick="pdvApp.processSale('card')">Carte</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Vue Client -->
        <?php if ($auth->hasRole('client')): ?>
        <div id="client-view" class="view">
            <div class="grid grid-cols-3 gap-6 mb-6">
                <div class="card">
                    <div class="card-content text-center">
                        <h3 class="text-lg font-semibold mb-2">Points de fidélité</h3>
                        <p class="text-3xl font-bold text-primary" id="loyalty-points">0</p>
                    </div>
                </div>
                <div class="card">
                    <div class="card-content text-center">
                        <h3 class="text-lg font-semibold mb-2">Total dépensé</h3>
                        <p class="text-3xl font-bold text-success" id="total-spent">0€</p>
                    </div>
                </div>
                <div class="card">
                    <div class="card-content text-center">
                        <h3 class="text-lg font-semibold mb-2">Achats</h3>
                        <p class="text-3xl font-bold text-warning" id="total-purchases">0</p>
                    </div>
                </div>
            </div>

            <div class="nav-tabs">
                <button class="nav-tab active" data-view="purchases">Mes achats</button>
                <button class="nav-tab" data-view="loyalty">Fidélité</button>
                <button class="nav-tab" data-view="complaints">Réclamations</button>
            </div>

            <div id="purchases-view" class="view">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Historique des achats</h3>
                    </div>
                    <div class="card-content">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Montant</th>
                                    <th>Articles</th>
                                    <th>Boutique</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="purchases-table">
                                <!-- Chargé via JavaScript -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </main>

    <script src="assets/js/app.js"></script>
</body>
</html>
