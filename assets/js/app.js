// Système PDV - JavaScript principal
class PDVApp {
  constructor() {
    this.currentView = "dashboard"
    this.cart = []
    this.currentCustomer = null
    this.init()
  }

  init() {
    this.setupEventListeners()
    this.loadInitialData()
  }

  setupEventListeners() {
    // Navigation entre les vues
    document.addEventListener("click", (e) => {
      if (e.target.classList.contains("nav-tab")) {
        this.switchView(e.target.dataset.view)
      }
    })

    // Gestion du panier (POS)
    document.addEventListener("click", (e) => {
      if (e.target.classList.contains("add-to-cart")) {
        this.addToCart(e.target.dataset.productId)
      }
      if (e.target.classList.contains("remove-from-cart")) {
        this.removeFromCart(e.target.dataset.index)
      }
    })

    // Recherche de produits
    const productSearch = document.getElementById("product-search")
    if (productSearch) {
      productSearch.addEventListener("input", (e) => {
        this.searchProducts(e.target.value)
      })
    }

    // Scanner de code-barres (simulation)
    const barcodeInput = document.getElementById("barcode-input")
    if (barcodeInput) {
      barcodeInput.addEventListener("keypress", (e) => {
        if (e.key === "Enter") {
          this.scanBarcode(e.target.value)
          e.target.value = ""
        }
      })
    }
  }

  switchView(view) {
    // Masquer toutes les vues
    document.querySelectorAll(".view").forEach((v) => {
      v.style.display = "none"
    })

    // Afficher la vue sélectionnée
    const targetView = document.getElementById(view + "-view")
    if (targetView) {
      targetView.style.display = "block"
    }

    // Mettre à jour les onglets
    document.querySelectorAll(".nav-tab").forEach((tab) => {
      tab.classList.remove("active")
    })
    document.querySelector(`[data-view="${view}"]`).classList.add("active")

    this.currentView = view
  }

  async loadInitialData() {
    try {
      // Charger les données initiales selon la vue
      if (this.currentView === "pos") {
        await this.loadProducts()
        await this.loadCustomers()
      }
    } catch (error) {
      console.error("Erreur lors du chargement des données:", error)
    }
  }

  async loadProducts() {
    try {
      const response = await fetch("api/products.php")
      const products = await response.json()
      this.renderProducts(products)
    } catch (error) {
      console.error("Erreur lors du chargement des produits:", error)
    }
  }

  renderProducts(products) {
    const container = document.getElementById("products-grid")
    if (!container) return

    container.innerHTML = products
      .map(
        (product) => `
            <div class="card">
                <div class="card-content">
                    <h3 class="font-semibold">${product.name}</h3>
                    <p class="text-muted-foreground">${product.barcode}</p>
                    <p class="text-lg font-bold">${product.price}€</p>
                    <p class="text-sm">Stock: ${product.stock_quantity}</p>
                    <button class="btn btn-primary add-to-cart" data-product-id="${product.id}">
                        Ajouter au panier
                    </button>
                </div>
            </div>
        `,
      )
      .join("")
  }

  addToCart(productId) {
    // Trouver le produit
    fetch(`api/products.php?id=${productId}`)
      .then((response) => response.json())
      .then((product) => {
        const existingItem = this.cart.find((item) => item.id === product.id)

        if (existingItem) {
          existingItem.quantity += 1
        } else {
          this.cart.push({
            ...product,
            quantity: 1,
          })
        }

        this.updateCartDisplay()
      })
      .catch((error) => {
        console.error("Erreur lors de l'ajout au panier:", error)
      })
  }

  removeFromCart(index) {
    this.cart.splice(index, 1)
    this.updateCartDisplay()
  }

  updateCartDisplay() {
    const cartContainer = document.getElementById("cart-items")
    const totalContainer = document.getElementById("cart-total")

    if (!cartContainer) return

    // Afficher les articles du panier
    cartContainer.innerHTML = this.cart
      .map(
        (item, index) => `
            <div class="flex justify-between items-center p-2 border-b">
                <div>
                    <span class="font-medium">${item.name}</span>
                    <span class="text-muted-foreground">x${item.quantity}</span>
                </div>
                <div class="flex items-center gap-2">
                    <span>${(item.price * item.quantity).toFixed(2)}€</span>
                    <button class="btn btn-destructive btn-sm remove-from-cart" data-index="${index}">
                        ×
                    </button>
                </div>
            </div>
        `,
      )
      .join("")

    // Calculer et afficher le total
    const total = this.cart.reduce((sum, item) => sum + item.price * item.quantity, 0)
    if (totalContainer) {
      totalContainer.textContent = total.toFixed(2) + "€"
    }
  }

  async searchProducts(query) {
    if (query.length < 2) {
      await this.loadProducts()
      return
    }

    try {
      const response = await fetch(`api/products.php?search=${encodeURIComponent(query)}`)
      const products = await response.json()
      this.renderProducts(products)
    } catch (error) {
      console.error("Erreur lors de la recherche:", error)
    }
  }

  async scanBarcode(barcode) {
    try {
      const response = await fetch(`api/products.php?barcode=${barcode}`)
      const product = await response.json()

      if (product) {
        this.addToCart(product.id)
        this.showAlert("Produit ajouté au panier", "success")
      } else {
        this.showAlert("Produit non trouvé", "error")
      }
    } catch (error) {
      console.error("Erreur lors du scan:", error)
      this.showAlert("Erreur lors du scan", "error")
    }
  }

  async processSale(paymentMethod) {
    if (this.cart.length === 0) {
      this.showAlert("Le panier est vide", "error")
      return
    }

    const saleData = {
      customer_id: this.currentCustomer?.id || null,
      items: this.cart,
      payment_method: paymentMethod,
      total: this.cart.reduce((sum, item) => sum + item.price * item.quantity, 0),
    }

    try {
      const response = await fetch("api/sales.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify(saleData),
      })

      const result = await response.json()

      if (result.success) {
        this.showAlert("Vente enregistrée avec succès", "success")
        this.cart = []
        this.currentCustomer = null
        this.updateCartDisplay()
        this.printReceipt(result.sale_id)
      } else {
        this.showAlert("Erreur lors de l'enregistrement", "error")
      }
    } catch (error) {
      console.error("Erreur lors de la vente:", error)
      this.showAlert("Erreur lors de la vente", "error")
    }
  }

  printReceipt(saleId) {
    // Ouvrir une nouvelle fenêtre pour l'impression
    const printWindow = window.open(`print_receipt.php?id=${saleId}`, "_blank")
    printWindow.onload = () => {
      printWindow.print()
    }
  }

  showAlert(message, type = "info") {
    const alertContainer = document.getElementById("alerts")
    if (!alertContainer) return

    const alert = document.createElement("div")
    alert.className = `alert alert-${type}`
    alert.textContent = message

    alertContainer.appendChild(alert)

    // Supprimer l'alerte après 5 secondes
    setTimeout(() => {
      alert.remove()
    }, 5000)
  }

  // Utilitaires pour les modales
  showModal(modalId) {
    const modal = document.getElementById(modalId)
    if (modal) {
      modal.classList.add("show")
    }
  }

  hideModal(modalId) {
    const modal = document.getElementById(modalId)
    if (modal) {
      modal.classList.remove("show")
    }
  }

  // Formatage des nombres
  formatCurrency(amount) {
    return new Intl.NumberFormat("fr-FR", {
      style: "currency",
      currency: "EUR",
    }).format(amount)
  }

  // Formatage des dates
  formatDate(date) {
    return new Intl.DateTimeFormat("fr-FR", {
      year: "numeric",
      month: "long",
      day: "numeric",
      hour: "2-digit",
      minute: "2-digit",
    }).format(new Date(date))
  }
}

// Initialiser l'application
document.addEventListener("DOMContentLoaded", () => {
  window.pdvApp = new PDVApp()
})

// Gestion des modales
document.addEventListener("click", (e) => {
  if (e.target.classList.contains("modal")) {
    e.target.classList.remove("show")
  }

  if (e.target.dataset.modal) {
    window.pdvApp.showModal(e.target.dataset.modal)
  }

  if (e.target.classList.contains("close-modal")) {
    const modal = e.target.closest(".modal")
    if (modal) {
      modal.classList.remove("show")
    }
  }
})

// Gestion des formulaires AJAX
document.addEventListener("submit", async (e) => {
  if (e.target.classList.contains("ajax-form")) {
    e.preventDefault()

    const formData = new FormData(e.target)
    const url = e.target.action

    try {
      const response = await fetch(url, {
        method: "POST",
        body: formData,
      })

      const result = await response.json()

      if (result.success) {
        window.pdvApp.showAlert(result.message || "Opération réussie", "success")
        e.target.reset()

        // Fermer la modale si elle existe
        const modal = e.target.closest(".modal")
        if (modal) {
          modal.classList.remove("show")
        }

        // Recharger les données si nécessaire
        if (result.reload) {
          location.reload()
        }
      } else {
        window.pdvApp.showAlert(result.message || "Erreur lors de l'opération", "error")
      }
    } catch (error) {
      console.error("Erreur AJAX:", error)
      window.pdvApp.showAlert("Erreur de communication", "error")
    }
  }
})
