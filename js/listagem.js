document.addEventListener('DOMContentLoaded', async () => {
    const formSenha = document.getElementById('formReserva');

    formSenha.addEventListener('submit', async (e) => {
        e.preventDefault();
        const senha = document.getElementById('senha').value;
        
        const response = await fetch(`listagem.php?senha=${senha}`);
        if (!response.ok) {
            alert('Erro ao carregar reservas ou senha incorreta');
        }
    });

    async function removeExpiredReservations() {
        await fetch('remove-expiradas.php');
    }
    removeExpiredReservations();
});






/* document.addEventListener('DOMContentLoaded', function() {
    const corpoTabela = document.getElementById('corpoTabela');
    let reservas = JSON.parse(localStorage.getItem('reservas')) || [];

    const excluirReservasPassadas = () => {
        const agora = new Date();
        reservas = reservas.filter(reserva => {
            const fimReserva = new Date(`${reserva.dataReserva}T${reserva.horaFim}`);
            return fimReserva >= agora;
        });
        localStorage.setItem('reservar', JSON.stringify(reservas));
    };

    excluirReservasPassadas();

    reservas.sort((a, b) => {
        const dataA = new Date(`${a.dataReserva}T${a.horaInicio}`);
        const dataB = new Date(`${b.dataReserva}T${b.horaInicio}`);
        return dataA - dataB;
    });

    if (reservas.length === 0) {
        corpoTabela.innerHTML = '<tr><td colspan="6">Nenhuma reserva disponível.</td></tr>';
    } else {
        reservas.forEach((reserva, index) => {
            const linha = document.createElement('tr');
            const dataFormatada = reserva.dataReserva.split('-').reverse().join('/');
            linha.innerHTML = `
                <td>${nome}</td>
                <td>${reserva.sala}</td>
                <td>${dataFormatada}</td>
                <td>${reserva.horaInicio}</td>
                <td>${reserva.horaFim}</td>
                <td><button class="btnExcluir" data-index="${index}">Excluir</button></td>
            `;
            corpoTabela.appendChild(linha);
        });

        document.querySelectorAll('.btnExcluir').forEach(btn => {
            btn.addEventListener('click', function() {
                const index = this.getAttribute('data-index');
                reservas.splice(index, 1);
                localStorage.setItem('reserva', JSON.stringify(reservas));
                location.reload();
            });
        });
    }
});
 */