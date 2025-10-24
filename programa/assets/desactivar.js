const desactivar = document.getElementById('desactivar');
const id = document.getElementById('id');
console.log(desactivar.value == 'desactivar');

async function cambiarEstado() {
    let seDesactiva = desactivar.value === 'desactivar' ? 0 : 1;

    await fetch('http://prueba_controller.test/?controller=ComerciosController&accion=desactivar', {
        method: 'POST', 
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            id_anuncio: ADD_ID,
            desactivar: seDesactiva
        })
    });

    location.reload();
}

desactivar.addEventListener('click', cambiarEstado);