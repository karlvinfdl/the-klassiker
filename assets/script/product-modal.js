/**
 * Product Modal — gère le popup produit avec suppléments, boissons, desserts
 */
document.addEventListener("DOMContentLoaded", function () {
  const modal = document.getElementById("productModal");
  if (!modal) return;

  const modalBody = document.getElementById("modalBody");
  const modalClose = document.getElementById("modalClose");
  const modalBackdrop = document.getElementById("modalBackdrop");

  // Charger les données JSON embarquées dans la page
  const dataEl = document.getElementById("modal-data");
  const modalData = dataEl ? JSON.parse(dataEl.textContent) : { supplements: [], drinks: [], desserts: [] };

  // Les URLs sont déjà résolues (avec hash Webpack) depuis les attributs Twig asset()
  function imageUrl(url) {
    if (!url) {
      const fallback = modal ? modal.dataset.fallback : null;
      return fallback || "/build/images/1-.webp";
    }
    return url;
  }

  function priceFormat(price) {
    return parseFloat(price).toFixed(2).replace(".", ",") + " €";
  }

  function buildSupplementsHtml() {
    if (!modalData.supplements.length) return "";
    return `
      <div class="modal-section">
        <h4 class="modal-section-title"><i class="fas fa-plus-circle"></i> Ajoutez des suppléments</h4>
        <div class="modal-supplements-grid">
          ${modalData.supplements.map(s => `
            <label class="supplement-item">
              <input type="checkbox" name="supplements[]" value="${s.id}" data-name="${s.name}" data-price="${s.price}">
              <span class="supplement-name">${s.name}</span>
              <span class="supplement-price">+${priceFormat(s.price)}</span>
            </label>
          `).join("")}
        </div>
      </div>`;
  }

  function buildExtrasHtml(items, title, icon) {
    if (!items.length) return "";
    return `
      <div class="modal-section">
        <h4 class="modal-section-title"><i class="fas fa-${icon}"></i> ${title}</h4>
        <div class="modal-extras-grid">
          ${items.map(item => `
            <div class="modal-extra-item">
              <img src="${imageUrl(item.image)}" alt="${item.name}">
              <div class="modal-extra-info">
                <span class="modal-extra-name">${item.name}</span>
                <span class="modal-extra-price">${priceFormat(item.price)}</span>
              </div>
              <button type="button" class="modal-extra-add" data-dish-id="${item.id}" data-dish-name="${item.name}">
                <i class="fas fa-plus"></i>
              </button>
            </div>
          `).join("")}
        </div>
      </div>`;
  }

  function openProductModal(dishData) {
    const finalImage = imageUrl(dishData.image);

    modalBody.innerHTML = `
      <div class="modal-product-detail">
        <div class="modal-product-image">
          <img src="${finalImage}" alt="${dishData.name}">
        </div>
        <div class="modal-product-info">
          ${dishData.category ? `<span class="modal-product-category">${dishData.category}</span>` : ""}
          <h2 class="modal-product-title">${dishData.name}</h2>
          <p class="modal-product-desc">${dishData.description || ""}</p>
          <div class="modal-product-price" id="modalTotalPrice">${dishData.price} €</div>

          <form action="/commande/ajouter" method="POST" class="modal-product-form" id="modalAddForm">
            <div class="form-group">
              <label class="form-label">Quantité</label>
              <div class="modal-qty-controls">
                <button type="button" class="modal-qty-btn" id="modalQtyMinus"><i class="fas fa-minus"></i></button>
                <input type="number" id="modalQuantity" name="quantity" value="1" min="1" max="10" readonly>
                <button type="button" class="modal-qty-btn" id="modalQtyPlus"><i class="fas fa-plus"></i></button>
              </div>
            </div>
            <div class="form-group">
              <label for="modalInstructions" class="form-label">Instructions spéciales</label>
              <textarea id="modalInstructions" name="special_instructions" rows="2" placeholder="Ex: sans oignons, sauce à part..."></textarea>
            </div>
            <input type="hidden" name="dish_id" value="${dishData.id}">
            <button type="submit" class="modal-btn-add">
              <i class="fas fa-shopping-basket"></i> Ajouter au panier
            </button>
          </form>
        </div>
      </div>

      ${buildSupplementsHtml()}
      ${buildExtrasHtml(modalData.drinks, "Accompagnez votre repas d'une boisson", "glass-water")}
      ${buildExtrasHtml(modalData.desserts, "Accompagnez votre repas d'un dessert", "ice-cream")}
    `;

    // Quantité
    const qtyInput = document.getElementById("modalQuantity");
    document.getElementById("modalQtyMinus").addEventListener("click", () => {
      if (parseInt(qtyInput.value) > 1) { qtyInput.value = parseInt(qtyInput.value) - 1; updateTotal(); }
    });
    document.getElementById("modalQtyPlus").addEventListener("click", () => {
      if (parseInt(qtyInput.value) < 10) { qtyInput.value = parseInt(qtyInput.value) + 1; updateTotal(); }
    });

    // Mise à jour du prix total avec suppléments
    const basePrice = parseFloat(dishData.price.replace(",", "."));
    function updateTotal() {
      let total = basePrice * parseInt(qtyInput.value);
      modalBody.querySelectorAll("input[name='supplements[]']:checked").forEach(cb => {
        total += parseFloat(cb.dataset.price);
      });
      document.getElementById("modalTotalPrice").textContent = total.toFixed(2).replace(".", ",") + " €";
    }
    modalBody.querySelectorAll("input[name='supplements[]']").forEach(cb => {
      cb.addEventListener("change", updateTotal);
    });

    // Soumission formulaire principal + suppléments
    document.getElementById("modalAddForm").addEventListener("submit", function (e) {
      e.preventDefault();
      const checkedSupplements = [...modalBody.querySelectorAll("input[name='supplements[]']:checked")];

      // Ajouter le plat principal
      fetch("/commande/ajouter", { method: "POST", body: new FormData(this) })
        .then(() => {
          // Ajouter chaque supplément comme item séparé
          const supplementPromises = checkedSupplements.map(cb => {
            const fd = new FormData();
            fd.append("dish_id", cb.value);
            fd.append("quantity", "1");
            fd.append("special_instructions", "Supplément: " + cb.dataset.name);
            return fetch("/commande/ajouter", { method: "POST", body: fd });
          });
          return Promise.all(supplementPromises);
        })
        .then(() => {
          closeModal();
          showFlash(dishData.name + " ajouté au panier !");
        });
    });

    // Boutons quick-add boissons/desserts
    modalBody.querySelectorAll(".modal-extra-add").forEach(btn => {
      btn.addEventListener("click", function () {
        const fd = new FormData();
        fd.append("dish_id", this.dataset.dishId);
        fd.append("quantity", "1");
        fetch("/commande/ajouter", { method: "POST", body: fd }).then(() => {
          this.innerHTML = '<i class="fas fa-check"></i>';
          this.style.background = "#29d344";
          showFlash(this.dataset.dishName + " ajouté !");
        });
      });
    });

    modal.classList.add("is-open");
    modal.setAttribute("aria-hidden", "false");
    document.body.classList.add("no-scroll");
  }

  function closeModal() {
    modal.classList.remove("is-open");
    modal.setAttribute("aria-hidden", "true");
    document.body.classList.remove("no-scroll");
  }

  function showFlash(message) {
    const main = document.getElementById("main-content");
    if (!main) return;
    const el = document.createElement("div");
    el.className = "alert alert-success";
    el.textContent = message;
    main.insertBefore(el, main.firstChild);
    setTimeout(() => el.remove(), 3000);
  }

  modalClose.addEventListener("click", closeModal);
  modalBackdrop.addEventListener("click", closeModal);
  document.addEventListener("keydown", e => {
    if (e.key === "Escape" && modal.classList.contains("is-open")) closeModal();
  });

  // Triggers sur les cartes produits
  document.querySelectorAll(".product-modal-trigger").forEach(trigger => {
    trigger.addEventListener("click", function (e) {
      if (e.target.closest(".add-to-cart-btn")) return;
      openProductModal({
        id: this.dataset.dishId,
        name: this.dataset.dishName,
        description: this.dataset.dishDescription,
        price: this.dataset.dishPrice,
        category: this.dataset.dishCategory,
        image: this.dataset.dishImage,
      });
    });
  });
});
