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
});