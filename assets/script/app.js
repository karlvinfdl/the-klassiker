// Import du CSS
import "../styles/app.css";

// Import des images pour Webpack (assure leur présence dans public/build/images/)
import "../images/galerie-11.webp";
import "../images/galerie-12.webp";
import "../images/galerie-13.webp";
import "../images/galerie-14.webp";
import "../images/galerie-15.webp";
import "../images/galerie-16.webp";
import "../images/galerie-17.webp";
import "../images/galerie-18.webp";
import "../images/galerie-19.webp";
import "../images/galerie-20.webp";
import "../images/galerie-21.webp";
import "../images/galerie-22.webp";
import "../images/galerie-23.webp";
import "../images/galerie-24.webp";
import "../images/galerie-25.webp";
import "../images/galerie-26.webp";
import "../images/galerie-27.webp";
import "../images/galerie-28.webp";
import "../images/galerie-29.webp";

// ================== SCRIPT PRINCIPAL ==================

document.addEventListener("DOMContentLoaded", () => {
  // ============ MENU BURGER ============
  const burgerBtn = document.querySelector(".nav-toggle");
  const mainNav = document.getElementById("mainNav");
  const navLinks = document.querySelectorAll(".nav-link");
  const toggleCarteBtn = document.getElementById("toggleCarte");

  function closeMenu() {
    if (!mainNav || !burgerBtn) return;
    mainNav.classList.remove("is-open");
    burgerBtn.classList.remove("burger-open");
    burgerBtn.setAttribute("aria-expanded", "false");
    document.body.classList.remove("no-scroll");
  }

  if (burgerBtn && mainNav) {
    burgerBtn.addEventListener("click", () => {
      const isOpen = mainNav.classList.contains("is-open");
      if (isOpen) {
        // Remettre le bouton à sa place initiale
        document.body.appendChild(toggleCarteBtn);
        closeMenu();
      } else {
        // Déplacer le bouton dans le menu
        mainNav.appendChild(toggleCarteBtn);
        mainNav.classList.add("is-open");
        burgerBtn.classList.add("burger-open");
        burgerBtn.setAttribute("aria-expanded", "true");
        document.body.classList.add("no-scroll");
      }
    });

    navLinks.forEach((link) => {
      link.addEventListener("click", () => {
        if (window.innerWidth <= 1024) {
          closeMenu();
        }
      });
    });
  }

  // ============ ACTIF NAV ============
  const setActiveLink = () => {
    const path = window.location.pathname;
    navLinks.forEach((link) => {
      const href = link.getAttribute("href");
      if (!href) return;
      if (path.endsWith("contact") && href.includes("contact")) {
        link.classList.add("active");
      } else if (path.endsWith("recrutement") && href.includes("recrutement")) {
        link.classList.add("active");
      }
    });
  };
  setActiveLink();

  // ============ VOIR TOUT LE MENU ============
  const hiddenCards = document.querySelectorAll(".food-card-to-hide");
  let carteExpanded = false;

  if (toggleCarteBtn && hiddenCards.length > 0) {
    hiddenCards.forEach((card) => (card.style.display = "none"));

    toggleCarteBtn.addEventListener("click", () => {
      carteExpanded = !carteExpanded;
      hiddenCards.forEach((card) => {
        card.style.display = carteExpanded ? "flex" : "none";
      });
      toggleCarteBtn.textContent = carteExpanded
        ? "Réduire"
        : "Voir tout le menu";
    });
  }

  // ============ GALERIE ============
  const galleryMainEl = document.getElementById("galleryMain");
  const thumbs = document.querySelectorAll(".gallery-thumb");
  const prevBtn = document.getElementById("galleryPrev");
  const nextBtn = document.getElementById("galleryNext");
  const galleryCounter = document.getElementById("galleryCounter");

  // Lightbox
  const lightbox = document.getElementById("galleryLightbox");
  const lightboxImg = document.getElementById("lightboxImg");
  const lightboxClose = document.getElementById("galleryLightboxClose");
  const lightboxBackdrop = document.getElementById("galleryLightboxBackdrop");
  const lightboxPrev = document.getElementById("lightboxPrev");
  const lightboxNext = document.getElementById("lightboxNext");
  const lightboxCounter = document.getElementById("lightboxCounter");

  // Tableau d'images construit dynamiquement depuis le DOM
  const galleryImages = Array.from(thumbs).map((t) => t.dataset.img).filter(Boolean);
  const total = galleryImages.length;
  let currentIndex = 0;
  const SLIDE_DELAY = 5000;
  let autoSlideInterval;

  function updateCounter(index) {
    const label = `${index + 1} / ${total}`;
    if (galleryCounter) galleryCounter.textContent = label;
    if (lightboxCounter) lightboxCounter.textContent = label;
  }

  function setActiveThumb(index) {
    thumbs.forEach((t) => t.classList.remove("is-active"));
    const active = document.querySelector(
      `.gallery-thumb[data-index="${index}"]`,
    );
    if (active) {
      active.classList.add("is-active");
    }
  }

  function updateLightboxImage(index) {
    if (!lightbox || !lightbox.classList.contains("is-open")) return;
    if (lightboxImg && galleryImages[index]) {
      lightboxImg.src = galleryImages[index];
      lightboxImg.alt = `Photo ${index + 1} / ${total} - The Klassiker`;
    }
  }

  function showImage(index) {
    if (!galleryMainEl) return;

    currentIndex = ((index % total) + total) % total;

    galleryMainEl.classList.add("is-fading");

    setTimeout(() => {
      if (galleryImages[currentIndex]) {
        galleryMainEl.src = galleryImages[currentIndex];
        galleryMainEl.alt = `Photo ${currentIndex + 1} / ${total} - The Klassiker`;
      }
      setActiveThumb(currentIndex);
      updateCounter(currentIndex);
      updateLightboxImage(currentIndex);
    }, 200);

    setTimeout(() => {
      galleryMainEl.classList.remove("is-fading");
    }, 400);
  }

  function nextImage() {
    showImage(currentIndex + 1);
  }

  function prevImage() {
    showImage(currentIndex - 1);
  }

  function startAutoSlide() {
    stopAutoSlide();
    autoSlideInterval = setInterval(nextImage, SLIDE_DELAY);
  }

  function stopAutoSlide() {
    if (autoSlideInterval) {
      clearInterval(autoSlideInterval);
      autoSlideInterval = null;
    }
  }

  // ---- Lightbox ----
  function openLightbox() {
    if (!lightbox || !lightboxImg) return;
    if (galleryImages[currentIndex]) {
      lightboxImg.src = galleryImages[currentIndex];
      lightboxImg.alt = `Photo ${currentIndex + 1} / ${total} - The Klassiker`;
    }
    updateCounter(currentIndex);
    lightbox.classList.add("is-open");
    lightbox.setAttribute("aria-hidden", "false");
    document.body.classList.add("no-scroll");
    stopAutoSlide();
  }

  function closeLightboxGallery() {
    if (!lightbox) return;
    lightbox.classList.remove("is-open");
    lightbox.setAttribute("aria-hidden", "true");
    document.body.classList.remove("no-scroll");
    startAutoSlide();
  }

  if (galleryMainEl && thumbs.length > 0) {
    // Clics sur les vignettes
    thumbs.forEach((btn) => {
      btn.addEventListener("click", () => {
        const idx = parseInt(btn.dataset.index, 10);
        if (Number.isNaN(idx)) return;
        showImage(idx);
        startAutoSlide();
      });
    });

    // Flèches galerie
    if (prevBtn) {
      prevBtn.addEventListener("click", () => {
        prevImage();
        startAutoSlide();
      });
    }
    if (nextBtn) {
      nextBtn.addEventListener("click", () => {
        nextImage();
        startAutoSlide();
      });
    }

    // Clic sur l'image principale → ouvre la lightbox
    galleryMainEl.addEventListener("click", openLightbox);

    // Fermer la lightbox
    if (lightboxClose)
      lightboxClose.addEventListener("click", closeLightboxGallery);
    if (lightboxBackdrop)
      lightboxBackdrop.addEventListener("click", closeLightboxGallery);

    // Navigation dans la lightbox
    if (lightboxPrev) {
      lightboxPrev.addEventListener("click", (e) => {
        e.stopPropagation();
        prevImage();
      });
    }
    if (lightboxNext) {
      lightboxNext.addEventListener("click", (e) => {
        e.stopPropagation();
        nextImage();
      });
    }

    // Clavier : Échap = fermer, ←→ = naviguer
    document.addEventListener("keydown", (e) => {
      if (e.key === "Escape") {
        closeLightboxGallery();
      } else if (e.key === "ArrowLeft") {
        prevImage();
        if (!lightbox || !lightbox.classList.contains("is-open"))
          startAutoSlide();
      } else if (e.key === "ArrowRight") {
        nextImage();
        if (!lightbox || !lightbox.classList.contains("is-open"))
          startAutoSlide();
      }
    });

    // Swipe tactile sur la galerie principale
    let touchStartX = 0;
    galleryMainEl.addEventListener(
      "touchstart",
      (e) => {
        touchStartX = e.changedTouches[0].screenX;
      },
      { passive: true },
    );
    galleryMainEl.addEventListener(
      "touchend",
      (e) => {
        const diff = touchStartX - e.changedTouches[0].screenX;
        if (Math.abs(diff) > 50) {
          if (diff > 0) nextImage();
          else prevImage();
          startAutoSlide();
        }
      },
      { passive: true },
    );

    // Swipe tactile dans la lightbox
    if (lightbox) {
      let lbTouchStartX = 0;
      lightbox.addEventListener(
        "touchstart",
        (e) => {
          lbTouchStartX = e.changedTouches[0].screenX;
        },
        { passive: true },
      );
      lightbox.addEventListener(
        "touchend",
        (e) => {
          const diff = lbTouchStartX - e.changedTouches[0].screenX;
          if (Math.abs(diff) > 50) {
            if (diff > 0) nextImage();
            else prevImage();
          }
        },
        { passive: true },
      );
    }

    // Lancement initial
    showImage(0);
    startAutoSlide();
  }

  // ============ ANIMATIONS AU SCROLL ============
  const animated = document.querySelectorAll("[data-animate]");

  if ("IntersectionObserver" in window && animated.length > 0) {
    const observer = new IntersectionObserver(
      (entries) => {
        entries.forEach((entry) => {
          if (entry.isIntersecting) {
            entry.target.classList.add("is-visible");
            observer.unobserve(entry.target);
          }
        });
      },
      { threshold: 0.15 },
    );
    animated.forEach((el) => observer.observe(el));
  } else {
    animated.forEach((el) => el.classList.add("is-visible"));
  }
});
