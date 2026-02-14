<link rel="stylesheet" href="../css/lead-form.css">

<div class="lead-card">
  <h2 class="lead-title">Zatražite ponudu za nameštaj po meri</h2>
  <p class="lead-subtitle">Popunite formular i dobićete ponudu u najkraćem roku.</p>

  <div id="toast" class="toast" aria-live="polite" aria-atomic="true"></div>

  <form id="leadForm" class="lead-form" enctype="multipart/form-data" novalidate>
    <div class="field">
      <label for="name">Ime i prezime <span class="req">*</span></label>
      <input id="name" type="text" name="name" placeholder="Unesite ime i prezime" required>
      <div class="error" data-error-for="name"></div>
    </div>

    <div class="field">
      <label for="phone">Telefon <span class="req">*</span></label>
      <input id="phone" type="tel" name="phone" placeholder="npr. 06x/xxx-xxx" required>
      <div class="error" data-error-for="phone"></div>
    </div>

    <div class="field">
      <label for="email">Email adresa <span class="req">*</span></label>
      <input id="email" type="email" name="email" placeholder="npr. ime@gmail.com" required>
      <div class="help">Poslaćemo potvrdu i odgovor na ovu adresu.</div>
      <div class="error" data-error-for="email"></div>
    </div>

    <div class="field">
      <label for="location">Grad <span class="req">*</span></label>
      <select id="location" name="location" required>
        <option value="" selected disabled>Izaberite grad</option>
        <option value="Beograd">Beograd</option>
        <option value="Pančevo">Pančevo</option>
        <option value="Novi Sad">Novi Sad</option>
        <option value="Drugo">Drugo</option>
      </select>
      <div class="error" data-error-for="location"></div>
    </div>

    <div class="field">
      <label>Tip nameštaja <span class="req">*</span></label>

      <input type="hidden" name="type" id="typeHidden" required>

      <div class="multi" id="multiType">
        <button type="button" class="multi-btn" aria-haspopup="listbox" aria-expanded="false">
          <span class="multi-placeholder" id="multiLabel">Izaberite tip (može više)</span>
          <span class="chev" aria-hidden="true">▾</span>
        </button>

        <div class="multi-panel" role="listbox" aria-multiselectable="true">
          <label class="multi-item"><input type="checkbox" value="Kuhinja"> Kuhinja</label>
          <label class="multi-item"><input type="checkbox" value="Plakar"> Plakar</label>
          <label class="multi-item"><input type="checkbox" value="TV komoda"> TV komoda</label>
          <label class="multi-item"><input type="checkbox" value="Trpezarijski sto"> Trpezarijski sto</label>
          <label class="multi-item"><input type="checkbox" value="Radni sto"> Radni sto</label>
          <label class="multi-item"><input type="checkbox" value="Kupatilski elementi"> Kupatilski elementi</label>
          <label class="multi-item"><input type="checkbox" value="Opremanje stana"> Opremanje stana</label>
        </div>

        <div class="chips" id="chips"></div>
      </div>

      <div class="error" data-error-for="type"></div>
    </div>

    <div class="field">
      <label for="dimensions">Dimenzije i kratak opis</label>
      <textarea id="dimensions" name="dimensions" rows="3" placeholder="npr. kuhinja 2.8m, željene boje..."></textarea>
    </div>

    <div class="field">
      <label for="attachment">Priložite sliku/skicu (opciono)</label>

      <div class="file-row">
        <input id="attachment" class="file-input" type="file" name="attachment" accept="image/*,.pdf">
        <label for="attachment" class="file-btn">Priložite fajl</label>
        <span class="file-name" id="fileName">Niste izabrali fajl</span>
      </div>

      <div class="help">
        Priložite bilo kakve skice, planove, dizajnerske predloge ili dokument od arhitekte (slika ili PDF).
      </div>
    </div>

    <div class="field">
      <label for="notes">Napomena</label>
      <textarea id="notes" name="notes" rows="3" placeholder="Imate dodatne zahteve ili pitanja?"></textarea>
    </div>

    <button id="submitBtn" type="submit" class="submit-btn">
      <span class="btn-text">Pošalji upit</span>
      <span class="btn-loader" aria-hidden="true"></span>
    </button>

    <div class="privacy">
      Vaši podaci su sigurni i koriste se isključivo u svrhu izrade ponude.
    </div>

    <div id="formStatus" class="status" aria-live="polite"></div>
  </form>
</div>

<script src="../js/lead-form.js"></script>
