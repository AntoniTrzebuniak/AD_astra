const urlParams = new URLSearchParams(window.location.search);
const discussionId = urlParams.get('discussionId');
document.addEventListener("DOMContentLoaded", function () {
    const articles = document.querySelectorAll(".article-box");
    const modal = document.getElementById("modal");
    const modalTitle = document.getElementById("modal-title");
    const modalBody = document.getElementById("modal-body");
    const closeButton = document.querySelector(".close-button");

    // Obsługa kliknięcia w artykuł
    articles.forEach(article => {
        article.addEventListener("click", () => {
            
            const articleId = article.getAttribute("data-article-id");

            // Pobierz szablon z treścią artykułu
            const template = document.getElementById(`article-${articleId}`);
            if (template) {
                
                modalBody.innerHTML = template.innerHTML; // Wstaw zawartość szablonu
                modal.classList.remove("hidden"); // Pokaż modal
            } else {
                console.error(`Nie znaleziono szablonu dla artykułu o ID: ${articleId}`);
            }
        });
    });

    // Obsługa zamknięcia modala
    closeButton.addEventListener("click", () => {
        modal.classList.add("hidden");
    });

    // Zamknięcie modala po kliknięciu poza treścią
    modal.addEventListener("click", (e) => {
        if (e.target === modal) {
            modal.classList.add("hidden");
        }
    });
});


document.addEventListener("DOMContentLoaded", function () {
    const menu = document.querySelector(".main-menu");
    const header = document.querySelector(".main-header");

    // Funkcja sprawdzająca pozycję scrolla
    window.addEventListener("scroll", function () {
        if (window.scrollY > header.offsetHeight) {
            // Jeśli przewinięto wystarczająco, aby header zniknął
            menu.classList.add("scrolled"); // Dodajemy klasę, która przyczepia menu do góry
        } else {
            // Jeśli header jest widoczny, usuwamy klasę
            menu.classList.remove("scrolled");
        }
    });
    function adjustHeaderText() {
        const headerText = header.querySelector("h1");
        if (window.innerWidth <= 700) {
            headerText.style.fontSize = Math.max(16, window.innerWidth / 25) + "px";
        } else {
            headerText.style.fontSize = ""; // Reset to default size for larger screens
        }
    }
    adjustHeaderText();
    window.addEventListener("resize", adjustHeaderText);
});

document.addEventListener("DOMContentLoaded", () => {
    // Pobierz identyfikator artykułu z adresu URL
    const hash = window.location.hash.substring(1);
    const articleTemplate = document.querySelector(`#${hash}`);

    if (articleTemplate) {
        // Znajdź kontener na artykuły i wstaw treść z template
        const articlesPage = document.querySelector(".articles-page");
        articlesPage.innerHTML = ""; // Wyczyść zawartość
        articlesPage.appendChild(articleTemplate.content.cloneNode(true));
    } else {
        // Jeśli brak identyfikatora, wyświetl komunikat lub domyślne treści
        const articlesPage = document.querySelector(".articles-page");
        articlesPage.innerHTML = "<h1>Wybierz artykuł z menu powyżej.</h1>";
    }
});

