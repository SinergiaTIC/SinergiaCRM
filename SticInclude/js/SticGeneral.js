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


// Comprobar si hay un registro ya activo o no
function toggleTimeTrackerRegisterButton() 
{
    const url = siteURL + '/index.php?module=stic_Time_Tracker&action=createOrUpdateTodayRegister';
    callCRMEntrypoint(url)
        .then(data => 
        {
            location.reload()
            var button = document.getElementById('time_tracker_register');
            if (data == 0) {
                button.classList.add('time-tracker-start');
                button.classList.remove('time-tracker-stop');
            } else {
                button.classList.remove('time-tracker-start');
                button.classList.add('time-tracker-stop');
            }
        })
        .catch(error => {
            console.error('Error when obtaining if there is a time registration started, or not, on today:', error);
        });
}
