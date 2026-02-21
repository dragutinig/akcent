<?php
$scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'] ?? 'akcent.rs';
$requestUri = $_SERVER['REQUEST_URI'] ?? '/';
$currentUrl = $scheme . '://' . $host . $requestUri;
$pagePath = parse_url($requestUri, PHP_URL_PATH) ?: '/';
$pageName = basename($pagePath ?: 'index.php');

$defaultTitle = 'Akcent Nameštaj po meri';
$defaultDescription = 'Nameštaj po meri u Beogradu i Pančevu: kuhinje, plakari, kupatilski elementi, 3D projektovanje i montaža.';

$seoTitle = $seoTitle ?? $defaultTitle;
$seoDescription = $seoDescription ?? $defaultDescription;
$seoImage = $seoImage ?? 'https://akcent.rs/img/akcent-namestaj-logo.png';

$pageType = 'WebPage';
if ($pageName === 'index.php' || $pagePath === '/') {
    $pageType = 'WebSite';
} elseif ($pageName === 'kontakt.php') {
    $pageType = 'ContactPage';
} elseif (in_array($pageName, ['proizvodi.php', 'kuhinje-po-meri.php', 'plakari-po-meri.php'], true)) {
    $pageType = 'CollectionPage';
}
?>
<meta name="robots" content="index,follow,max-image-preview:large,max-snippet:-1,max-video-preview:-1">
<meta name="googlebot" content="index,follow,max-image-preview:large,max-snippet:-1,max-video-preview:-1">
<meta property="og:locale" content="sr_RS">
<meta property="og:type" content="website">
<meta property="og:title" content="<?php echo htmlspecialchars($seoTitle, ENT_QUOTES, 'UTF-8'); ?>">
<meta property="og:description" content="<?php echo htmlspecialchars($seoDescription, ENT_QUOTES, 'UTF-8'); ?>">
<meta property="og:url" content="<?php echo htmlspecialchars($currentUrl, ENT_QUOTES, 'UTF-8'); ?>">
<meta property="og:site_name" content="Akcent Nameštaj">
<meta property="og:image" content="<?php echo htmlspecialchars($seoImage, ENT_QUOTES, 'UTF-8'); ?>">
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="<?php echo htmlspecialchars($seoTitle, ENT_QUOTES, 'UTF-8'); ?>">
<meta name="twitter:description" content="<?php echo htmlspecialchars($seoDescription, ENT_QUOTES, 'UTF-8'); ?>">
<meta name="twitter:image" content="<?php echo htmlspecialchars($seoImage, ENT_QUOTES, 'UTF-8'); ?>">
<link rel="alternate" hreflang="sr-RS" href="<?php echo htmlspecialchars($currentUrl, ENT_QUOTES, 'UTF-8'); ?>">

<script type="application/ld+json">
<?php echo json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'Organization',
    'name' => 'Akcent Nameštaj',
    'url' => 'https://akcent.rs',
    'logo' => 'https://akcent.rs/img/akcent-namestaj-logo.png',
    'telephone' => '0616485508',
    'email' => 'akcentnamestaj@gmail.com',
    'sameAs' => [
        'https://www.instagram.com/akcentnamestaj/',
        'https://www.facebook.com/akcentnamestaj',
        'https://www.pinterest.com/akcentnamestaj/'
    ]
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT); ?>
</script>

<script type="application/ld+json">
<?php echo json_encode([
    '@context' => 'https://schema.org',
    '@type' => $pageType,
    'name' => $seoTitle,
    'description' => $seoDescription,
    'url' => $currentUrl,
    'inLanguage' => 'sr-RS',
    'isPartOf' => [
        '@type' => 'WebSite',
        'name' => 'Akcent Nameštaj',
        'url' => 'https://akcent.rs'
    ]
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT); ?>
</script>
