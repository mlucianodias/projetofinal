document.getElementById('formReserva').addEventListener('submit', function (e) {
    e.preventDefault();

    const formData = new FormData(this);
    fetch('processar-dados.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(data => {
        alert(data);
        this.reset();
    })
    .catch(error => console.error('Erro:', error));
});


/* document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('formReserva');

    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        const formData = new FormData(form);

        try {
            const response = await fetch('processar-dados.php', {
                method: 'POST',
                body: formData
            });

            const result = await response.text();
            if (result === 'success') {
                alert('Reserva realizada com sucesso');
                form.reset();
            } else if (result === 'conflito') {
                alert('Horário conflitante!Verifique as reservas existentes e escolha outro horário.');
            } else {
                alert(`Erro: ${result}`);
            }
        } catch (error) {
            console.error('Erro ao processar a reserva:', error);
        }
    });
}); */



/* document.getElementById('formReserva').addEventListener('submit', function(event) {
    event.preventDefault();

    const nome = document.getElementById('nome').value;
    const dataReserva = document.getElementById('dataReserva').value;
    const horaInicio = document.getElementById('horaInicio').value;
    const horaFim = document.getElementById('horaFim').value;
    const sala = document.getElementById('sala').value;

    if (!nome || !dataReserva || !horaInicio || !horaFim || !sala) {
        alert('Todos os campos devem ser preenchidos!');
        return;
    }

    const reservas = JSON.parse(localStorage.getItem('reservas')) || [];
    const conflito = reservas.some(reserva => 
        reserva.dataReserva === dataReserva &&
        reserva.sala === sala &&
        ((horaInicio >= reserva.horaInicio && horaInicio < reserva.horaFim) || 
        (horaFim > reserva.horaInicio && horaFim <= reserva.horaFim))
    );

    if (conflito) {
        alert('Este horário já foi reservado para esta sala. Por favor, scolha outro horário!');
        return;
    }

    const novaReserva = {
        nome,
        dataReserva,
        horaInicio,
        horaFim,
        sala
    };

    reservas.push(novaReserva);
    localStorage.setItem('reservas', JSON.stringify(reservas));

    alert('Sala reservada com sucesso!');
    window.location.href = 'listagem.php';
});
 */