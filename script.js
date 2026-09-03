/* ============================================================
   INSPIRALL · Trazabilidad Fico Crispy Blend 100 g
   Dynamic lot data · QR · scroll reveals · interactions
   ------------------------------------------------------------
   The lot is driven by URL params so a single page serves every
   QR code. Example:
     ?lote=FCB-2025-0731&sede=Sullana,%20Piura&hash=0x8f...&vegan=1
   Recognised params:
     lote, sede, inoc, cosecha, hash, registro, vegan,
     doc_registro, doc_vegano, registro_url
   ============================================================ */
(function () {
  "use strict";

  var params = new URLSearchParams(window.location.search);
  var get = function (k) { var v = params.get(k); return v && v.trim() ? v.trim() : null; };

  /* ---- Config / defaults (fallback when no QR param present) ---- */
  var cfg = {
    lote:     get("lote")     || "FCB-2025-0001",
    sede:     get("sede")     || null,
    inoc:     get("inoc")     || null,
    cosecha:  get("cosecha")  || null,
    hash:     get("hash")     || null,
    registro: get("registro") || "M5801426N / NAUTSS",
    vegan:    get("vegan"),
    docRegistro: get("doc_registro"),
    docVegano:   get("doc_vegano"),
    registroUrl: get("registro_url")
  };

  /* ---- Helpers ---- */
  function setAll(attr, value) {
    if (value == null) return;
    document.querySelectorAll("[" + attr + "]").forEach(function (el) { el.textContent = value; });
  }

  /* ---- 1. Lot code everywhere ---- */
  setAll("data-lote", cfg.lote);
  var mini = document.querySelector("[data-lote-mini]");
  if (mini) mini.textContent = "LOTE " + cfg.lote;

  /* ---- 2. Lot-conditional fields (only overwrite when provided) ---- */
  if (cfg.sede)    setAll("data-sede", cfg.sede);
  if (cfg.inoc)    setAll("data-fecha-inoculacion", cfg.inoc);
  if (cfg.cosecha) setAll("data-fecha-cosecha", cfg.cosecha);
  setAll("data-registro", cfg.registro);

  /* ---- 3. Blockchain hash (never fabricated) ---- */
  if (cfg.hash) setAll("data-hash", cfg.hash);
  // else: markup keeps "Registro blockchain pendiente de integración"

  /* ---- 4. Canonical lot URL (shown + encoded in QR) ---- */
  var loteUrl = window.location.origin + window.location.pathname + "?lote=" + encodeURIComponent(cfg.lote);
  setAll("data-lote-url", loteUrl);

  /* ---- 5. QR codes ---- */
  // Rendered via a QR image service against the canonical lot URL.
  // Swap `buildQr` for a self-hosted/offline generator if preferred.
  function buildQr(target, size) {
    if (!target) return;
    var src = "https://api.qrserver.com/v1/create-qr-code/?size=" + size + "x" + size +
              "&margin=0&color=082542&bgcolor=ffffff&data=" + encodeURIComponent(loteUrl);
    var img = new Image();
    img.alt = "Código QR del lote " + cfg.lote;
    img.decoding = "async";
    img.loading = "lazy";
    img.src = src;
    img.onerror = function () {
      target.innerHTML = '<span class="mono" style="font-size:.6rem;color:#6a7f90;padding:.5rem;text-align:center">QR ' + cfg.lote + '</span>';
    };
    target.appendChild(img);
  }
  buildQr(document.querySelector("[data-qr]"), 300);
  buildQr(document.querySelector("[data-qr-mini]"), 180);

  /* ---- 6. Vegan badge (only with explicit backing) ---- */
  if (cfg.vegan === "1" || cfg.vegan === "true") {
    var badge = document.querySelector("[data-vegan-badge]");
    if (badge) badge.hidden = false;
  }

  /* ---- 7. Document links (registro / vegano / ver registro) ---- */
  function wireDoc(sel, url) {
    var el = document.querySelector(sel);
    if (!el) return;
    if (url) { el.setAttribute("href", url); el.setAttribute("target", "_blank"); el.setAttribute("rel", "noopener"); }
    else {
      el.setAttribute("aria-disabled", "true");
      el.addEventListener("click", function (e) {
        e.preventDefault();
        el.textContent = "Documento pendiente";
      });
    }
  }
  wireDoc('[data-doc="registro-sanitario"]', cfg.docRegistro);
  wireDoc('[data-doc="vegano"]', cfg.docVegano);
  wireDoc('[data-ver-registro]', cfg.registroUrl);

  /* ---- 8. Sticky topbar state ---- */
  var topbar = document.getElementById("topbar");
  var onScroll = function () {
    if (topbar) topbar.classList.toggle("is-scrolled", window.scrollY > 12);
  };
  onScroll();
  window.addEventListener("scroll", onScroll, { passive: true });

  /* ---- 9. Smooth scroll for in-page CTAs ---- */
  document.querySelectorAll('a[href^="#"]').forEach(function (a) {
    a.addEventListener("click", function (e) {
      var id = a.getAttribute("href");
      if (id.length < 2) return;
      var target = document.querySelector(id);
      if (!target) return;
      e.preventDefault();
      target.scrollIntoView({ behavior: "smooth", block: "start" });
    });
  });

  /* ---- 10. Reveal on scroll (+ staggered cascade) — bulletproof ---- */
  var reduce = window.matchMedia("(prefers-reduced-motion: reduce)").matches;
  function markIn(el) {
    if (el.classList.contains("is-in")) return;
    if (el.classList.contains("stagger")) {
      var kids = el.children;
      for (var i = 0; i < kids.length; i++) kids[i].style.transitionDelay = (i * 80) + "ms";
    }
    el.classList.add("is-in");
  }
  var animated = Array.prototype.slice.call(document.querySelectorAll(".reveal, .stagger"));
  if (reduce || !("IntersectionObserver" in window)) {
    animated.forEach(markIn);
  } else {
    var io = new IntersectionObserver(function (entries) {
      entries.forEach(function (en) {
        if (en.isIntersecting || en.boundingClientRect.top < window.innerHeight) {
          markIn(en.target); io.unobserve(en.target);
        }
      });
    }, { threshold: 0.12, rootMargin: "0px 0px -6% 0px" });
    animated.forEach(function (el) { io.observe(el); });

    // Fallback sweep: reveal anything at/above the fold on scroll or resize.
    // Covers very fast flings and deep-link jumps the observer can miss.
    var pending = animated, ticking = false;
    function sweep() {
      ticking = false;
      pending = pending.filter(function (el) {
        if (el.classList.contains("is-in")) return false;
        if (el.getBoundingClientRect().top < window.innerHeight * 0.92) { markIn(el); io.unobserve(el); return false; }
        return true;
      });
    }
    function onMove() { if (!ticking) { ticking = true; requestAnimationFrame(sweep); } }
    window.addEventListener("scroll", onMove, { passive: true });
    window.addEventListener("resize", onMove);
    onMove(); // initial pass (covers deep-link / anchor loads)
  }

  /* ---- 10b. Active timeline node while scrolling ("you are here") ---- */
  if (!reduce && "IntersectionObserver" in window) {
    var nodeIo = new IntersectionObserver(function (entries) {
      entries.forEach(function (en) {
        var node = en.target.querySelector(".stage__node");
        if (node) node.classList.toggle("is-current", en.isIntersecting);
      });
    }, { rootMargin: "-45% 0px -45% 0px" });
    document.querySelectorAll(".stage").forEach(function (s) { nodeIo.observe(s); });
  }

  /* ---- 11. Count-up for GHI figures ---- */
  function animateCount(el) {
    var end = parseFloat(el.getAttribute("data-count"));
    var dec = parseInt(el.getAttribute("data-dec") || "0", 10);
    if (isNaN(end)) return;
    var dur = 1200, start = null;
    function fmt(n) { return n.toLocaleString("es-PE", { minimumFractionDigits: dec, maximumFractionDigits: dec }); }
    function step(ts) {
      if (start === null) start = ts;
      var p = Math.min((ts - start) / dur, 1);
      var eased = 1 - Math.pow(1 - p, 3);
      el.textContent = fmt(end * eased);
      if (p < 1) requestAnimationFrame(step);
      else el.textContent = fmt(end);
    }
    requestAnimationFrame(step);
  }
  var counts = document.querySelectorAll(".count");
  if (reduce || !("IntersectionObserver" in window)) {
    // leave static values in markup
  } else {
    var cio = new IntersectionObserver(function (entries) {
      entries.forEach(function (en) {
        if (en.isIntersecting) { animateCount(en.target); cio.unobserve(en.target); }
      });
    }, { threshold: 0.6 });
    counts.forEach(function (el) { cio.observe(el); });
  }

  /* ---- 12. Map markers ⇄ zone cards ---- */
  document.querySelectorAll(".marker").forEach(function (m) {
    var zone = m.getAttribute("data-zone");
    var card = document.getElementById("zone-" + zone);
    function focusZone() {
      document.querySelectorAll(".marker").forEach(function (x) { x.classList.remove("is-active"); });
      document.querySelectorAll(".zone").forEach(function (x) { x.classList.remove("is-active"); });
      m.classList.add("is-active");
      if (card) {
        card.classList.add("is-active");
        card.scrollIntoView({ behavior: reduce ? "auto" : "smooth", block: "nearest" });
      }
    }
    m.addEventListener("click", focusZone);
    m.addEventListener("mouseenter", function () { if (card) card.classList.add("is-active"); });
    m.addEventListener("mouseleave", function () { if (card && !m.classList.contains("is-active")) card.classList.remove("is-active"); });
  });

  /* ---- 13. Year (footer, if a [data-year] node exists) ---- */
  setAll("data-year", String(new Date().getFullYear()));

  /* ---- 14. Subtle hero parallax on the decorative glow (reduced-motion safe) ---- */
  if (!reduce) {
    var heroSun = document.querySelector(".hero__sun");
    if (heroSun) {
      var tickingP = false;
      var parallax = function () {
        tickingP = false;
        heroSun.style.transform = "translateY(" + (window.scrollY * 0.16) + "px)";
      };
      window.addEventListener("scroll", function () {
        if (!tickingP) { tickingP = true; requestAnimationFrame(parallax); }
      }, { passive: true });
    }
  }
})();
