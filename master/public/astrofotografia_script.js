document.addEventListener("DOMContentLoaded", function () {
    const galleryItems = document.querySelectorAll(".gallery-item img");
    const lightbox = document.getElementById("lightbox");
    const lightboxImg = document.getElementById("lightbox-img");
    const lightboxCaption = document.getElementById("lightbox-caption");
    const closeButton = document.querySelector(".close-button");

    galleryItems.forEach(item => {
        item.addEventListener("click", () => {
            lightboxImg.src = item.src;
            lightboxCaption.textContent = item.alt;
            lightbox.classList.remove("hidden");
        });
    });

    closeButton.addEventListener("click", () => {
        lightbox.classList.add("hidden");
    });

    lightbox.addEventListener("click", (e) => {
        if (e.target === lightbox) {
            lightbox.classList.add("hidden");
        }
    });
});
