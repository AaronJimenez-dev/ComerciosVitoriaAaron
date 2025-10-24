
    /*FALTA AÑADIR EL CODIGO PHP */
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../css/publicarAnuncio.css">
</head>
<body>
        <?php 
    require = '../views/layout/header.php'
    require = '../views/layout/footer.php'
    session_start();
    ?>
    <div id="contenedor">
        <div id="crearAnuncio">
            <div id="crearAnuncioFormulario" name="editarPerfil">
                <p>Editar Perfil</p>
            <form id="formEditar" action="index.php?controller=usuarios&action=update&id=<?php session_start(); echo $_SESSION['id']?>" method="post"></form>
                <input type="text" name="nombre" id="nombre" placeholder="Nombre" value ="<?php
                 session_start();
                    if (isset($usuario)){
                    echo $usuario['nombre'];
                }?>"> 
                <input type="text" name="apellidos" id="apellidos" placeholder="Apellidos" value="<?php
                    if (isset($usuario)){
                        echo $usuario['apellidos']
                    }
                ?>">
                <input type="text" name="fecha_nacimiento" id="fecha_nacimiento" placeholder="DD/MM/YYYY" value="<?php
                    if (isset($usuario)){
                        echo $usuario['fecha_nacimiento']
                    }
                ?>">
                <input type="text" name="telefono" id="telefono" placeholder="Teléfono" value="<?php
                    if (isset($usuario)){
                        echo $usuario['telefono']
                    }
                ?>">
                <input type="email" name="email" id="email" placeholder="Email" value="<?php
                    if (isset($usuario)){
                        echo $usuario['email']
                    }
                ?>">
            </div>

            <div id="añadir-imagen">
                <label for="inputAñadirImagen" id="añadir">Añadir fotos</label>
                <input type="file" id="inputAñadirImagen" accept="image/*" multiple hidden>
                <button id="boton">+</button>
            </div>

            <div id="preview"></div>

            <div style="display: flex; gap: 10px; margin-top: 20px;">
                <button id="editar">Editar</button>
            </div>
        </div>

        <div id="imagen">
            <img src="../img/VITORIA-GASTEIZ.jpg" alt="">
        </div>
    </div>
                </form>

    <script>
        const fileInput = document.getElementById('inputAñadirImagen');
        const preview = document.getElementById('preview');
        const addIcon = document.getElementById('boton');

        // Imágenes iniciales del perfil
        let imagenes = ["../img/perfil1.jpg"];

        // Mostrar imágenes iniciales
        imagenes.forEach(src => {
            const img = document.createElement('img');
            img.src = src;
            img.classList.add('miniatura');
            img.title = "Haz clic para eliminar";
            img.addEventListener('click', () => {
                imagenes = imagenes.filter(i => i !== img.src);
                img.remove();
            });
            preview.appendChild(img);
        });

        // Subir nuevas imágenes
        addIcon.addEventListener('click', () => fileInput.click());

        fileInput.addEventListener('change', () => {
            const nuevosArchivos = Array.from(fileInput.files);

            nuevosArchivos.forEach(file => {
                const reader = new FileReader();
                reader.onload = e => {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.classList.add('miniatura');
                    img.title = "Haz clic para eliminar";

                    img.addEventListener('click', () => {
                        imagenes = imagenes.filter(i => i !== img.src);
                        img.remove();
                    });

                    imagenes.push(img.src);
                    preview.appendChild(img);
                };
                reader.readAsDataURL(file);
            });

            fileInput.value = '';
        });

        document.getElementById('editar').addEventListener('click', () => {
            const perfilEditado = {
                nombre: document.getElementById('nombre').value,
                apellidos: document.getElementById('apellidos').value,
                fecha_nac: document.getElementById('fecha_nac').value,
                telefono: document.getElementById('telefono').value,
                email: document.getElementById('email').value,
                imagenes: imagenes
            };
            console.log("Perfil editado:", perfilEditado);
            alert("Perfil actualizado correctamente!");
            // AÑADIR codigo para guardar la edicion en la bbdd
        });

        document.getElementById('desactivar').addEventListener('click', () => {
            alert("Cuenta desactivada");
            // AÑADIR codigo para desactivar el perfil
        });

        document.getElementById('borrar').addEventListener('click', () => {
            alert("Cuenta borrada");
            // AÑADIR codigo para borrar el perfil
        });
    </script>
</body>
</html>
