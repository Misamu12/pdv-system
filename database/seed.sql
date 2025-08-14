-- Données de test pour le système PDV
USE pdv_system;

-- Insertion des boutiques
INSERT INTO stores (name, address, phone, email) VALUES
('Boutique Centre-Ville', '123 Rue Principale, Paris', '01.23.45.67.89', 'centre@pdv.com'),
('Boutique Banlieue', '456 Avenue des Champs, Lyon', '04.56.78.90.12', 'banlieue@pdv.com');

-- Insertion des utilisateurs (mot de passe: "password123" hashé)
-- Insertion des utilisateurs (mot de passe: "motdepasse123" hashé)
INSERT INTO users (email, password, name, role, store_id) VALUES
('admin@pdv.com', 'motdepasse123', 'Administrateur Système', 'admin', NULL),
('manager@pdv.com', 'motdepasse123', 'Manager Principal', 'manager', 1),
('cashier@pdv.com', 'motdepasse123', 'Caissier Expert', 'cashier', 1),
('client@pdv.com', 'motdepasse123', 'Client VIP', 'client', NULL);

-- Insertion des catégories
INSERT INTO categories (name, description) VALUES
('Électronique', 'Appareils électroniques et accessoires'),
('Vêtements', 'Vêtements et accessoires de mode'),
('Alimentation', 'Produits alimentaires et boissons'),
('Maison', 'Articles pour la maison et décoration');

-- Insertion des produits
INSERT INTO products (name, barcode, category_id, price, cost_price, stock_quantity, min_stock, description) VALUES
('iPhone 15 Pro', '1234567890123', 1, 1199.00, 800.00, 25, 5, 'Smartphone Apple dernière génération'),
('Samsung Galaxy S24', '2345678901234', 1, 999.00, 650.00, 30, 5, 'Smartphone Samsung haut de gamme'),
('T-shirt Coton Bio', '3456789012345', 2, 29.99, 15.00, 100, 20, 'T-shirt en coton biologique'),
('Jean Slim Fit', '4567890123456', 2, 79.99, 40.00, 50, 10, 'Jean coupe slim moderne'),
('Café Premium', '5678901234567', 3, 12.99, 8.00, 200, 50, 'Café arabica premium'),
('Lampe LED Design', '6789012345678', 4, 89.99, 45.00, 15, 5, 'Lampe LED design moderne');

-- Insertion des clients
INSERT INTO customers (name, email, phone, loyalty_points, total_spent) VALUES
('Marie Dupont', 'marie.dupont@email.com', '06.12.34.56.78', 150, 450.00),
('Pierre Martin', 'pierre.martin@email.com', '06.23.45.67.89', 75, 230.00),
('Sophie Bernard', 'sophie.bernard@email.com', '06.34.56.78.90', 200, 680.00);
