// Realizar una solicitud GET
fetch('consultaEventos.php?action=read', {
    method: 'GET',
})
    .then(response => {
        if (!response.ok) {
            throw new Error('Error en la solicitud GET');
        }
        return response.json();
    })
    .then(data => {
        console.log('Datos obtenidos (GET):', data);
    })
    .catch(error => {
        console.error('Error:', error);
    });

// Realizar una solicitud POST
fetch('registroEventos.php', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
    },
    body: JSON.stringify({
        nombre: 'Juan',
        edad: 30,
    }),
})
    .then(response => {
        if (!response.ok) {
            throw new Error('Error en la solicitud POST');
        }
        return response.json();
    })
    .then(data => {
        console.log('Respuesta del servidor (POST):', data);
    })
    .catch(error => {
        console.error('Error:', error);
    });
