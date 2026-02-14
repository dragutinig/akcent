document.addEventListener("DOMContentLoaded", () => {
    const shareBtn = document.getElementById("shareBtn");
    const fallbackLinks = document.getElementById("fallback-share-links");
    const copyLinkBtn = document.getElementById("copy-link");

    // URL, naslov, opis i slika posta
    const postTitle = document.title; // Naslov posta
    const postURL = window.location.href; // URL posta
    const postDescription = document.querySelector('meta[name="description"]').getAttribute("content"); // Opis
    const postImage = document.querySelector('meta[property="og:image"]').getAttribute("content"); // Slika

    if (navigator.share) {
        // Web Share API
        shareBtn.addEventListener("click", async () => {
            try {
                await navigator.share({
                    title: postTitle,
                    text: `Pogledaj ovaj blog post: ${postTitle}\n${postDescription}`,
                    url: postURL,
                });
                console.log("Post je podeljen!");
            } catch (error) {
                console.error("Deljenje nije uspelo:", error);
            }
        });
    } else {
        // Fallback za deljenje
        shareBtn.style.display = "none";
        fallbackLinks.style.display = "block";

        document.getElementById("share-facebook").href = `https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(postURL)}&quote=${encodeURIComponent(postTitle + ' - ' + postDescription)}`;
        document.getElementById("share-twitter").href = `https://twitter.com/intent/tweet?url=${encodeURIComponent(postURL)}&text=${encodeURIComponent(postTitle)}&via=twitter_handle`;
        document.getElementById("share-linkedin").href = `https://www.linkedin.com/sharing/share-offsite/?url=${encodeURIComponent(postURL)}`;

        // Kopiraj link za Instagram
        copyLinkBtn.addEventListener("click", () => {
            navigator.clipboard.writeText(postURL).then(() => {
                alert("Link je kopiran! Zalepite ga u svoj Instagram bio ili Story.");
            }).catch(err => {
                console.error("Greška pri kopiranju linka: ", err);
            });
        });
    }
});
