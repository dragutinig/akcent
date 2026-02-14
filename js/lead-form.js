(function () {
  const form = document.getElementById("leadForm");
  const submitBtn = document.getElementById("submitBtn");
  const statusEl = document.getElementById("formStatus");
  const toast = document.getElementById("toast");

  const emailEl = document.getElementById("email");
  const fileInput = document.getElementById("attachment");
  const fileName = document.getElementById("fileName");

  // MULTI
  const multi = document.getElementById("multiType");
  const multiBtn = multi.querySelector(".multi-btn");
  const multiPanel = multi.querySelector(".multi-panel");
  const multiLabel = document.getElementById("multiLabel");
  const chips = document.getElementById("chips");
  const typeHidden = document.getElementById("typeHidden");

  // ✅ tvoj endpoint (stavi tačnu putanju)
  const ENDPOINT_URL = "/form-handler/send-form.php";

  function setToast(type, msg) {
    toast.className = "toast " + (type === "ok" ? "ok" : "err");
    toast.textContent = msg;
    toast.scrollIntoView({ behavior: "smooth", block: "start" });
  }

  function setStatus(type, msg) {
    statusEl.className = "status " + (type === "ok" ? "ok" : "err");
    statusEl.textContent = msg;
    statusEl.style.display = "block";
    statusEl.scrollIntoView({ behavior: "smooth", block: "nearest" });
  }

  function clearStatus() {
    statusEl.style.display = "none";
    statusEl.textContent = "";
    toast.textContent = "";
    toast.className = "toast";
    toast.style.display = "none";
  }

  function setLoading(isLoading) {
    if (isLoading) {
      submitBtn.classList.add("loading");
      submitBtn.disabled = true;
    } else {
      submitBtn.classList.remove("loading");
      submitBtn.disabled = false;
    }
  }

  function setFieldError(name, message) {
    const el = form.querySelector(`[name="${name}"]`);
    const err = form.querySelector(`[data-error-for="${name}"]`);
    if (el) el.classList.add("is-invalid");
    if (err) err.textContent = message || "";
  }

  function clearFieldError(name) {
    const el = form.querySelector(`[name="${name}"]`);
    const err = form.querySelector(`[data-error-for="${name}"]`);
    if (el) el.classList.remove("is-invalid");
    if (err) err.textContent = "";
  }

  function isValidEmail(email) {
    return /^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/.test(String(email).trim());
  }

  function validateForm() {
    let ok = true;

    const requiredFields = ["name", "phone", "email", "location"];

    requiredFields.forEach((f) => {
      clearFieldError(f);
      const el = form.querySelector(`[name="${f}"]`);
      const val = (el?.value || "").trim();

      if (!val) {
        ok = false;
        setFieldError(f, "Ovo polje je obavezno.");
      }
    });

    clearFieldError("email");
    const email = emailEl.value.trim();
    if (!email) {
      ok = false;
      setFieldError("email", "Email je obavezan.");
    } else if (!isValidEmail(email)) {
      ok = false;
      setFieldError("email", "Unesite ispravnu email adresu (npr. ime@gmail.com).");
    }

    // tip nameštaja obavezno
    const typeErr = form.querySelector(`[data-error-for="type"]`);
    if (typeErr) typeErr.textContent = "";
    multiBtn.classList.remove("is-invalid");

    if (!typeHidden.value.trim()) {
      ok = false;
      if (typeErr) typeErr.textContent = "Izaberite bar jedan tip nameštaja.";
      multiBtn.classList.add("is-invalid");
    }

    return ok;
  }

  // FILE label
  fileInput.addEventListener("change", () => {
    const f = fileInput.files && fileInput.files[0];
    fileName.textContent = f ? f.name : "Niste izabrali fajl";
  });

  // MULTI open/close
  function closeMulti() {
    multi.classList.remove("open");
    multiBtn.setAttribute("aria-expanded", "false");
  }
  function openMulti() {
    multi.classList.add("open");
    multiBtn.setAttribute("aria-expanded", "true");
  }

  multiBtn.addEventListener("click", () => {
    if (multi.classList.contains("open")) closeMulti();
    else openMulti();
  });

  document.addEventListener("click", (e) => {
    if (!multi.contains(e.target)) closeMulti();
  });

  // MULTI update hidden + label + chips
  function updateMultiUI() {
    const checked = [...multiPanel.querySelectorAll('input[type="checkbox"]:checked')].map(i => i.value);

    typeHidden.value = checked.join(", ");

    if (checked.length === 0) multiLabel.textContent = "Izaberite tip (može više)";
    else if (checked.length === 1) multiLabel.textContent = checked[0];
    else multiLabel.textContent = `Izabrano: ${checked.length}`;

    chips.innerHTML = "";
    checked.forEach((val) => {
      const chip = document.createElement("span");
      chip.className = "chip";
      chip.innerHTML = `<span>${val}</span><button type="button" aria-label="Ukloni ${val}">×</button>`;
      chip.querySelector("button").addEventListener("click", () => {
        const cb = [...multiPanel.querySelectorAll('input[type="checkbox"]')].find(x => x.value === val);
        if (cb) cb.checked = false;
        updateMultiUI();
      });
      chips.appendChild(chip);
    });

    // kad izabere, skloni error
    const typeErr = form.querySelector(`[data-error-for="type"]`);
    if (typeHidden.value.trim()) {
      if (typeErr) typeErr.textContent = "";
      multiBtn.classList.remove("is-invalid");
    }
  }

  multiPanel.addEventListener("change", updateMultiUI);

  // clear errors while typing
  ["name", "phone", "email", "location"].forEach((f) => {
    const el = form.querySelector(`[name="${f}"]`);
    if (!el) return;
    el.addEventListener("input", () => clearFieldError(f));
    el.addEventListener("change", () => clearFieldError(f));
  });

  form.addEventListener("submit", async (e) => {
    e.preventDefault();
    clearStatus();

    if (!validateForm()) {
      setToast("err", "Molimo proverite označena polja.");
      return;
    }
    // ✅ Client-side limit fajla (8MB) - odmah na telefonu, pre slanja
const f = fileInput.files && fileInput.files[0];
if (f && f.size > 8 * 1024 * 1024) {
  const msg = "Fajl je prevelik (maks 8MB). Molimo izaberite manju sliku ili PDF.";
  setToast("err", msg);
  setStatus("err", msg);
  return;
}


    setLoading(true);

    try {
      const fd = new FormData(form);

      const res = await fetch(ENDPOINT_URL, {
        method: "POST",
        body: fd
      });

      let data = null;
      const ct = res.headers.get("content-type") || "";

      if (ct.includes("application/json")) data = await res.json();
      else data = { success: res.ok, message: (await res.text()) || "" };

      if (res.ok && data && (data.success === true || data.ok === true)) {
        const msg = data.message || "Hvala na upitu! Kontaktiraćemo vas u najkraćem roku.";
        setToast("ok", msg);
        setStatus("ok", msg);

        form.reset();
        fileName.textContent = "Niste izabrali fajl";

        [...multiPanel.querySelectorAll('input[type="checkbox"]')].forEach(cb => cb.checked = false);
        updateMultiUI();
        closeMulti();

        if (typeof gtag === "function") {
          gtag("event", "generate_lead");
        }
      } else {
        const msg = (data && data.message) ? data.message : "Došlo je do greške. Pokušajte ponovo.";
        setToast("err", msg);
        setStatus("err", msg);
      }
    } catch (err) {
      setToast("err", "Greška pri slanju. Pokušajte ponovo.");
      setStatus("err", "Greška pri slanju. Pokušajte ponovo.");
    } finally {
      setLoading(false);
    }
  });

  updateMultiUI();
})();
