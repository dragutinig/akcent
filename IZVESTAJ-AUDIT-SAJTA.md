# Izveštaj tehničkog pregleda sajta (`akcent`)

## Šta je pregledano
- Svi tekstualni fajlovi u repou (PHP/HTML/CSS/JS/XML/MD/TXT) automatizovanim skeniranjem.
- Posebna pažnja na blog deo (`/blog/php`) i duplirane linkove u listingu postova.

## Hitno (P1) – popravke koje su urađene
1. **Uklonjeni dupli linkovi na blog karticama**
   - Na `blog/php/index.php` i `blog/php/category.php` kartica je imala dva `<a>` elementa ka istoj URL adresi (`post-link` + `read-more`).
   - Ovo je povećavalo šum za crawlere, accessibility alate i analitiku klikova.

2. **Popravljen canonical tag na detalju posta**
   - U `blog/php/post.php` canonical je bio sintaksno neispravan (nedostajao završetak taga).
   - Sada je canonical validan i formira URL iz već parsiranih slug vrednosti.

3. **Search stranica bloga stabilizovana**
   - `blog/php/search.php` je referencirao nepostojeći `db_config.php` i `styles.css`.
   - Prebačeno na postojeću `Database.php` klasu, prepared statements i validan CSS path (`/blog/css/style.css`).

4. **Ispravljeni očigledni 404 interni linkovi**
   - `kontaktirajte.php` ➜ `kontakt.php`.
   - `proizvoid.php` ➜ `proizvodi.php`.

5. **Apple touch icon fallback linkovi usklađeni**
   - Na root stranicama zamena `icon.png` (nije postojao u root-u) sa `favicon.ico` (postoji).

## Visok prioritet (P2) – preporuke za sledeći commit
1. **Ujednačiti blog rute**
   - Trenutno se mešaju `/blog/php` i SEO rute `/blog/{category}/{slug}`.
   - Predlog: jedna javna ulazna tačka (`/blog/`) + 301 redirect sa legacy ruta.

2. **Ojačati sigurnost i kvalitet SQL sloja**
   - Proći kroz admin/blog fajlove i svuda prebaciti string SQL konkatenacije na prepared statements.

3. **Konsolidovati include putanje komponenti**
   - Uvesti centralni helper za putanje (npr. `BASE_URL`) da se izbegnu relativni path edge-case scenariji.

4. **Automatska provera internih linkova u CI-u**
   - Dodati scriptu koja proverava statičke `href/src` reference i prijavljuje 404 pre merge-a.

## Srednji prioritet (P3)
1. **SEO/metadata standardizacija**
   - Ujednačiti title/description/canonical pravila između root sajta i blog dela.

2. **A11y i semantika**
   - Proći kroz CTA i navigaciju (posebno dugmad sa `onclick`) i prebaciti na semantičke anchor elemente gde je moguće.

3. **Dokumentacija**
   - Dodati kratki `README` sa mapom ruta i pravilima za kreiranje novih landing stranica.

## Operativne instrukcije za dalje
1. Pokreni lint:
   - `find . -name "*.php" -not -path "*/vendor/*" -print0 | xargs -0 -n1 php -l`
2. Lokalno proveri kritične stranice:
   - `/`, `/proizvodi.php`, `/kontakt.php`, `/blog/php`, jedan category URL i jedan post URL.
3. U Search Console proveri canonical i coverage nakon deploy-a.
4. Nakon 7 dana uporedi CTR i crawl stats (blog listing + post pages).
