document.addEventListener("DOMContentLoaded", () => {
    const navLinks = document.querySelectorAll(".nav-bar ul li a");

    navLinks.forEach(link => {
        link.addEventListener("click", () => {
            const id = link.id;

            // Lógica de mostrar/ocultar el submenú de modelos
            if (id === "modelos") {
                event.preventDefault();
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

            // Oculta la imagen u otro contenido visible
            const modelImage = document.querySelector("#modelImage");
            if (modelImage) {
                modelImage.style.display = "none";
            }
        });
    });

    const bars = document.querySelector(".bars");
    bars.onclick = function() {
        const navBar = document.querySelector(".nav-bar");
        navBar.classList.toggle("active");
    };

    // Inicializar la selección predeterminada al cargar la página
    selectedColor = '1'; // Define el valor predeterminado para esta página
    updateRinesVisibility();
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

    // Actualiza la clase de las etiquetas
    const allRadioButtons = document.querySelectorAll(".radio-button");
    allRadioButtons.forEach(button => button.classList.remove("selected"));

    // Añade la clase "selected" al botón correspondiente
    const selectedButton = document.querySelector(`[onclick="showImage('${imageSrc}', '${url}')"]`);
    selectedButton.classList.add("selected");
}

document.getElementById('modelSelect').addEventListener('change', function() {
    var selectedModel = this.value;
    var section = document.querySelector('.two'); // Referencia a la sección .two

    // Eliminar todas las clases de fondo posibles
    section.classList.remove('background-han', 'background-tang', 'background-yuan', 'background-seal', 'background-dolphin', 'background-dolphinMini', 'background-song');
    
    // Añadir la clase correspondiente al modelo seleccionado
    switch (selectedModel) {
        case 'Han':
            section.classList.add('background-han');
            break;
        case 'Tang':
            section.classList.add('background-tang');
            break;
        case 'Yuan':
            section.classList.add('background-yuan');
            break;
        case 'Seal':
            section.classList.add('background-seal');
            break;
        case 'Dolphin':
            section.classList.add('background-dolphin');
            break;
        case 'DolphinMini':
            section.classList.add('background-dolphinMini');
            break;
        case 'Song':
            section.classList.add('background-song');
            break;
        // Añade más casos según sea necesario
    }
});

document.getElementById('carForm').addEventListener('submit', function(event) {
    event.preventDefault(); // Evita que el formulario se envíe y la página se recargue

    // Aquí puedes añadir cualquier validación o lógica de envío adicional

    // Muestra el mensaje de confirmación
    document.getElementById('confirmationMessage').style.display = 'block';

    // Opcional: Limpiar el formulario después de enviar
    this.reset();

    // Opcional: Si quieres simular un envío y luego mostrar el mensaje
    setTimeout(() => {
        alert('Cita agendada correctamente.'); // Puedes usar alert o cambiar el contenido de un div
    }, 500); // Retrasa la alerta por 500 ms para simular el tiempo de procesamiento
});

function showColors() {
    document.getElementById('colorOptions').style.display = 'flex';
    document.getElementById('interiorOptions').style.display = 'none';
    document.getElementById('rinesOptions').style.display = 'none';
}

function showInterior() {
    document.getElementById('colorOptions').style.display = 'none';
    document.getElementById('interiorOptions').style.display = 'flex';
    document.getElementById('rinesOptions').style.display = 'none';
}

function showRines() {
    document.getElementById('colorOptions').style.display = 'none';
    document.getElementById('interiorOptions').style.display = 'none';
    document.getElementById('rinesOptions').style.display = 'flex';
    updateRinesVisibility(); // Actualiza la visibilidad de los rines según el color seleccionado
}

function changeCarImage(imagePath) {
    const section = document.querySelector('.section');
    section.style.backgroundImage = `url(${imagePath})`;
}

function selectColor(color, imagePath) {
    selectedColor = color;
    changeCarImage(imagePath);
    updateRinesVisibility(); // Actualiza la visibilidad de los rines según el nuevo color seleccionado
}

function updateRinesVisibility() {
    const rinesButtons = document.querySelectorAll('#rinesOptions button');
    rinesButtons.forEach(button => {
        button.style.display = button.getAttribute('data-color') === selectedColor ? 'block' : 'none';
    });
}
