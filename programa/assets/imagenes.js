function ponerImagenes() {
    imagenesExistentes.forEach(imagen => {
        const img = document.createElement('img');
        img.src = imagen.ruta;
        //guardar como dato su id
        img.dataset.id = imagen.id;

        //Añadir estilos a las imagenes chiquitas
        imgStyles(img);

        img.addEventListener('click', () => {
            const id = img.dataset.id;
            imagenesBorradas.push(id);
            console.log(imagenesBorradas);

            //eliminar del array
            imagenesExistentes = imagenesExistentes.filter(i => i.id != id);
            console.log(imagenesExistentes);
            //eliminar del DOM
            img.remove();
        });

        preview.appendChild(img);
    });

    //Limpia el input para poder volver a seleccionar las mismas imágenes después
    fileInput.value = '';
}

function addImage() {
    //coge imagenes del input file
    const nuevosArchivos = Array.from(fileInput.files);

    nuevosArchivos.forEach(file => {

        imagenesNuevas.push(file);
        console.log(imagenesNuevas);

        //API para leer archivos
        const reader = new FileReader();

        reader.onload = e => {
            const imagen = document.createElement('img');
            imagen.src = e.target.result;

            //Añadir estilos a las imagenes chiquitas
            imgStyles(imagen);

            imagen.addEventListener('click', () => {
                //Reescribe el array imagenes nuevas quitando la imagen seleccionada
                imagenesNuevas = imagenesNuevas.filter(f => f !== file);
                imagen.remove();
            });

            preview.appendChild(imagen);
        };
        //mostrar la imagen sin necesidad de subirla al servidor
        reader.readAsDataURL(file);
    });

    //Limpia el input para poder volver a seleccionar las mismas imágenes después
    fileInput.value = '';
}

function imgStyles(img) {
    img.classList.add('miniatura');
    img.title = "Haz clic para eliminar";
    img.style.width = '120px';
    img.style.height = '120px';
    img.style.objectFit = 'cover';
    img.style.borderRadius = '8px';
    img.style.margin = '5px';
}

function antesDeEnviar(e) {
    //prevenir que el formulario se envie hasta que los datos terminen de procesar
    e.preventDefault();
    //deshabilitar el boton de enviar para prevenir multiples peticiones
    btnEnviar.disabled = true;

    //Hacer que el input tenga solo los datos guardados
    //ULa API hace que el .items sea una lista de archivos que se puede añadir a 
    //un input file, para mandarlo a php
    const dataTransfer = new DataTransfer();
    imagenesNuevas.forEach(file => dataTransfer.items.add(file));
    fileInput.files = dataTransfer.files;
    fileInput.name = 'imagenes[]';

    //Crear un input oculto para enviar el id y la ruta de las imagenes que se borran
    //para borrar tanto de la bbdd por id como del servidor por la ruta
    let hiddenInput = document.querySelector('input[name="imagenesBorradas"]');
    if (!hiddenInput) {
        hiddenInput = document.createElement('input');
        hiddenInput.type = 'hidden';
        hiddenInput.name = 'imagenesBorradas';
        document.querySelector('form').appendChild(hiddenInput);
    }
    hiddenInput.value = JSON.stringify(imagenesBorradas);
    console.log(imagenesNuevas);
    console.log(fileInput.files);
    console.log(hiddenInput.value);
    e.target.submit();
}
const fileInput = document.getElementById('inputAñadirImagen');
const preview = document.getElementById('preview');
const addIcon = document.getElementById('boton');
const btnEnviar = document.getElementById('crear')|| document.getElementById('editar');

let imagenesNuevas = [];
let imagenesExistentes = [];
let imagenesBorradas = [];

if (IMAGENES.length !== 0) {
    imagenesExistentes = IMAGENES;
    ponerImagenes();
}

//Hacer que el boton '+' tenga la misma funcion que el input
addIcon.addEventListener('click', () => fileInput.click());

fileInput.addEventListener('change', () => addImage());

//Reconstruir el array de imagenes antes de enviar la info

document.querySelector('form').addEventListener('submit', (e) => antesDeEnviar(e));