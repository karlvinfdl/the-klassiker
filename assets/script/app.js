// Import du CSS
import "../styles/app.css";

// Import des images pour Webpack
import img1 from "../images/galerie-11.webp";
import img2 from "../images/galerie-12.webp";
import img3 from "../images/galerie-13.webp";
import img4 from "../images/galerie-14.webp";
import img5 from "../images/galerie-15.webp";
import img6 from "../images/galerie-16.webp";
import img7 from "../images/galerie-17.webp";
import img8 from "../images/galerie-18.webp";
import img9 from "../images/galerie-19.webp";
import img10 from "../images/galerie-20.webp";
import img11 from "../images/galerie-21.webp";
import img12 from "../images/galerie-22.webp";
import img13 from "../images/galerie-23.webp";
import img14 from "../images/galerie-24.webp";
import img15 from "../images/galerie-25.webp";
import img16 from "../images/galerie-26.webp";
import img17 from "../images/galerie-27.webp";
import img18 from "../images/galerie-28.webp";
import img19 from "../images/galerie-29.webp";

// Tableau d'images utilisé par la galerie
const galleryImages = [
  img1,
  img2,
  img3,
  img4,
  img5,
  img6,
  img7,
  img8,
  img9,
  img10,
  img11,
  img12,
  img13,
  img14,
  img15,
  img16,
  img17,
  img18,
  img19,
];

// ================== SCRIPT PRINCIPAL ==================

document.addEventListener("DOMContentLoaded", () => {
  // ============ MENU BURGER (simplifié) ============
  const burgerBtn = document.querySelector(".nav-toggle");
  const mainNav = document.getElementById("mainNav");
  const navLinks = document.querySelectorAll(".nav-link");

  function closeMenu() {
    if (!mainNav || !burgerBtn) return;
    mainNav.classList.remove("is-open");
    burgerBtn.classList.remove("burger-open");
    burgerBtn.setAttribute("aria-expanded", "false");
    document.body.classList.remove("no-scroll");
  }

  if (burgerBtn && mainNav) {
    burgerBtn.addEventListener("click", () => {
      const isOpen = !mainNav.classList.contains("is-open");

      mainNav.classList.toggle("is-open", isOpen);
      burgerBtn.classList.toggle("burger-open", isOpen);
      burgerBtn.setAttribute("aria-expanded", isOpen ? "true" : "false");
      document.body.classList.toggle("no-scroll", isOpen);
    });

    // Fermer le menu quand on clique sur un lien (mobile)
    navLinks.forEach((link) => {
      link.addEventListener("click", () => {
        if (window.innerWidth <= 768) {
          closeMenu();
        }
      });
    });
  }

  // ============ ACTIF NAV (simple) ============
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
  const toggleCarteBtn = document.getElementById("toggleCarte");
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

  // ============ GALERIE (thumbs + flèches + lightbox) ============
  const galleryMain = document.getElementById("galleryMain");
  const thumbs = document.querySelectorAll(".gallery-thumb");
  const prevBtn = document.getElementById("galleryPrev");
  const nextBtn = document.getElementById("galleryNext");

  // Lightbox
  const lightbox = document.getElementById("galleryLightbox");
  const lightboxImg = document.getElementById("lightboxImg");
  const lightboxClose = document.getElementById("galleryLightboxClose");
  const lightboxBackdrop = document.getElementById("galleryLightboxBackdrop");

  let currentIndex = 0;
  const SLIDE_DELAY = 5000;
  let autoSlideInterval;

  function setActiveThumb(index) {
    thumbs.forEach((t) => t.classList.remove("is-active"));
    const active = document.querySelector(
      `.gallery-thumb[data-index="${index}"]`,
    );
    if (active) active.classList.add("is-active");
  }

  function showImage(index) {
    if (!galleryMain) return;

    const total = galleryImages.length;
    currentIndex = ((index % total) + total) % total; // boucle infinie

    galleryMain.classList.add("is-fading");

    setTimeout(() => {
      if (galleryImages[currentIndex]) {
        galleryMain.src = galleryImages[currentIndex];
      }
      setActiveThumb(currentIndex);
    }, 250);

    setTimeout(() => {
      galleryMain.classList.remove("is-fading");
    }, 500);
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

  // Lightbox helpers
  function openLightbox() {
    if (!lightbox || !lightboxImg) return;
    if (galleryImages[currentIndex]) {
      lightboxImg.src = galleryImages[currentIndex];
    }
    lightbox.classList.add("is-open");
    document.body.classList.add("no-scroll");
    lightbox.setAttribute("aria-hidden", "false");
  }

  function closeLightboxGallery() {
    if (!lightbox) return;
    lightbox.classList.remove("is-open");
    document.body.classList.remove("no-scroll");
    lightbox.setAttribute("aria-hidden", "true");
  }

  if (galleryMain && thumbs.length > 0) {
    // thumbs clic
    thumbs.forEach((btn) => {
      btn.addEventListener("click", () => {
        const idx = parseInt(btn.dataset.index, 10);
        if (Number.isNaN(idx)) return;
        showImage(idx);
        startAutoSlide();
      });
    });

    // flèches
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

    // clic sur l'image principale => ouvre en grand
    galleryMain.addEventListener("click", openLightbox);

    // lightbox : fermer (croix + fond + ESC)
    if (lightboxClose) {
      lightboxClose.addEventListener("click", closeLightboxGallery);
    }
    if (lightboxBackdrop) {
      lightboxBackdrop.addEventListener("click", closeLightboxGallery);
    }
    document.addEventListener("keydown", (e) => {
      if (e.key === "Escape") {
        closeLightboxGallery();
      }
    });

    // lancement auto
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
      { threshold: 0.2 },
    );

    animated.forEach((el) => observer.observe(el));
  } else {
    animated.forEach((el) => el.classList.add("is-visible"));
  }
});
