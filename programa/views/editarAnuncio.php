<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../css/publicarAnuncio.css">
</head>

<body>
    <div id="contenedor">
        <div id="crearAnuncio">
            <form action="index.php?accion=update" method="post" enctype="multipart/form-data">
                <div id="crearAnuncioFormulario" name="editarAnuncio">
                    <p>Editar anuncio</p>
                    <input type="text" name="id" id="id" hidden value="<?= htmlspecialchars($anuncio['id']) ?>" required>
                    <input type="text" name="titulo" id="titulo" placeholder="Título" value="<?= htmlspecialchars($anuncio['titulo']) ?>" required>
                    <input type="number" name="precio" id="precio" placeholder="Precio" value="<?= htmlspecialchars($anuncio['precio']) ?>" step="0.01" required>
                    <input type="text" name="descripcion" id="descripcion" placeholder="Descripción" value="<?= htmlspecialchars($anuncio['descripcion']) ?>" required>
                    <input type="text" name="direccion" id="direccion" placeholder="Dirección" value="<?= htmlspecialchars($anuncio['direccion']) ?>" required>
                </div>

                <label for="categoria">Categoría:</label>
                <select name="categoria" id="categoria">
                    <?php foreach ($categorias as $categoria): ?>
                        <option value="<?= $categoria['id'] ?>" <?= $categoria['id'] == $anuncio['id_categoria'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($categoria['nombre']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <div id="añadir-imagen">
                    <label for="inputAñadirImagen" id="añadir">Añadir fotos</label>
                    <input type="file" id="inputAñadirImagen" name="imagenes[]" accept="image/*" multiple hidden>
                    <button id="boton" type="button">+</button>
                </div>

                <div id="preview"></div>
                <div style="display: flex; gap: 10px;">
                    <button id="editar" type="submit">Editar</button>
            </form>
            <?php if ($anuncio['estado'] == 1): ?>
                <button id="desactivar" type="button" value="desactivar">Desactivar</button>
            <?php else: ?>
                <button id="desactivar" type="button" value="reactivar">Reactivar</button>
            <?php endif; ?>
            <form action="index.php?accion=destroy" method="post" enctype="multipart/form-data">
                <input type="text" name="id" id="id" hidden value="<?= htmlspecialchars($anuncio['id']) ?>" required>
                <button id="editar" type="submit">Borrar</button>
            </form>
        </div>
    </div>

    <div id="imagen">
        <img src="../img/VITORIA-GASTEIZ.jpg" alt="">
    </div>
    </div>
    <script>
        const IMAGENES = <?= json_encode($imagenes) ?>
    </script>
    <script>
        const ADD_ID = <?= $anuncio['id'] ?>;
    </script>
    <script src="assets/imagenes.js"></script>
     <!--
    defer hace que no se ejecute hasta que se utilice
     -->
    <script src="assets/desactivar.js" defer></script>
</body>

</html>