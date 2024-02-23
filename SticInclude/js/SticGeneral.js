// alert('SticGeneral');    

function sleep(ms) {
    return new Promise(resolve => setTimeout(resolve, ms));
}

// Función que devuelve una promesa para obtener datos de cualquier API
function callCRMEntrypoint(url) {
    // Retorna una nueva promesa
    return new Promise((resolve, reject) => {
        // Hacer la solicitud a la API utilizando fetch
        fetch(url)
            .then(response => {
                // Verificar si la solicitud fue exitosa (código de respuesta 200)
                if (!response.ok) {
                    throw new Error(`Error en la solicitud: ${response.status}`);
                }

                // Parsear la respuesta JSON y resolver la promesa con los datos
                return response.json();
            })
            .then(data => {
                resolve(data); // Resolver la promesa con los datos
            })
            .catch(error => {
                reject(error); // Rechazar la promesa con el error
            });
    });
}


// 
function toggleTimeTrackerRegisterButton() 
{
    var button = document.getElementById('time_tracker_register');

    // Colors modification
    button.classList.toggle('time-tracker-start');
    button.classList.toggle('time-tracker-stop');
    button.blur();
}


// Función que se ejecutará después de que la página se haya cargado completamente
window.onload = function() {
    // Comprobar si hay un registro ya activo o no
    const url = 'http://localhost:8000/sinergiacrm/index.php?entryPoint=timeRegistrationIsStarted';

    callCRMEntrypoint(url)
        .then(data => 
        {
            let button = document.getElementById('time_tracker_register');
            if (data == 1) {
                button.classList.add('time-tracker-start');
                button.classList.remove('time-tracker-stop');
            } else {
                button.classList.remove('time-tracker-start');
                button.classList.add('time-tracker-stop');                                                
            }
            // button.innerHTML = ' ';
        })
        .catch(error => {
            console.error('Error when obtaining if there is a time registration started, or not, on today:', error);
        });

    // debugger;
    // Obtener el botón por su ID
    let button = document.getElementById('time_tracker_register');
    console.log("Clase cargada");
};
