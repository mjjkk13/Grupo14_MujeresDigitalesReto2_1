// Realizar una solicitud GET
fetch('http://127.0.0.1/Grupo14_MujeresDigitalesReto2/consultaEventos?action=read', {
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
fetch('http://127.0.0.1/Grupo14_MujeresDigitalesReto2/registroEventos.php', {
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
