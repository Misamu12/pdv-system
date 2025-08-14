<?php
require_once 'config/database.php';
require_once 'includes/auth.php';

$database = new Database();
$db = $database->getConnection();
$auth = new Auth($db);

$error = '';

if ($_POST) {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    
    if ($auth->login($email, $password)) {
        header("Location: index.php");
        exit();
    } else {
        $error = 'Email ou mot de passe incorrect';
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - Système PDV</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="bg-muted">
    <div class="flex items-center justify-center min-h-screen">
        <div class="card" style="width: 400px;">
            <div class="card-header text-center">
                <h1 class="text-2xl font-bold">Système PDV</h1>
                <p class="text-muted-foreground">Connectez-vous à votre compte</p>
            </div>
            <div class="card-content">
                <?php if ($error): ?>
                    <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
                <?php endif; ?>
                
                <form method="POST">
                    <div class="mb-4">
                        <label class="block text-sm font-medium mb-2">Email</label>
                        <input type="email" name="email" class="input" required>
                    </div>
                    <div class="mb-6">
                        <label class="block text-sm font-medium mb-2">Mot de passe</label>
                        <input type="password" name="password" class="input" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-full">Se connecter</button>
                </form>
                
                <div class="mt-6 p-4 bg-muted rounded">
                    <h3 class="font-semibold mb-2">Comptes de test:</h3>
                    <div class="text-sm space-y-1">
                        <div><strong>Admin:</strong> admin@pdv.com / motdepasse123</div>
                        <div><strong>Manager:</strong> manager@pdv.com / motdepasse123</div>
                        <div><strong>Caissier:</strong> cashier@pdv.com / motdepasse123</div>
                        <div><strong>Client:</strong> client@pdv.com / motdepasse123</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
