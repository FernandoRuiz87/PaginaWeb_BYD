document.addEventListener("DOMContentLoaded", () => {
    const backgroundImages = {
        modelos: 'Images/modelos_fondo.jpg',
        acerca: 'Images/about-BYD-pc.jpg',
        inventario: 'Images/inventario_fondo.jpg'
    };

    const navLinks = document.querySelectorAll(".nav-bar ul li a");

    navLinks.forEach(link => {
        link.addEventListener("click", () => {
            const id = link.id;
            const newBackground = backgroundImages[id];

            const container = document.querySelector(".container");

            if (newBackground) {
                container.style.backgroundImage = `url('${newBackground}')`;
            }

            if (id === "modelos") {
                const modelosMenu = link.nextElementSibling;
                modelosMenu.classList.toggle("show");
            } else {
                const modelosMenu = document.querySelector(".modelosMenu");
                if (modelosMenu) {
                    modelosMenu.classList.remove("show");
                }

                // Contrae la barra para opciones diferentes a "Modelos"
                const navBar = document.querySelector(".nav-bar");
                navBar.classList.remove("active");
            }
        });
    });

    const bars = document.querySelector(".bars");
    bars.onclick = function() {
        const navBar = document.querySelector(".nav-bar");
        navBar.classList.toggle("active");
    };
});

function showImage(imageSrc, url) {
    const modelImage = document.querySelector("#modelImage");
    modelImage.src = imageSrc;
    modelImage.style.display = "block";

    modelImage.onclick = () => {
        window.location.href = url;

        // Contrae la barra cuando se selecciona la imagen
        const navBar = document.querySelector(".nav-bar");
        navBar.classList.remove("active");
    };
}
