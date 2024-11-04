







/* const express = require('express');
const { Pool } = require('pg');
const bodyParser = require('body-parser');
const app = express();
const port = process.env.PORT || 3000;

app.use(bodyParser.json());
app.use(express.static('public'));

// Configuração do banco de dados PostgreSQL
const pool = new Pool({
    connectionString: process.env.DATABASE_URL,
    ssl: { rejectUnauthorized: false }
});

// Cria tabela se não existir
pool.query(`
    CREATE TABLE IF NOT EXISTS reservas (
        id SERIAL PRIMARY KEY,
        nome TEXT,
        dataReserva DATE,
        horaInicio TIME,
        horaFim TIME,
        sala TEXT
    )
`, (err) => {
    if (err) {
        console.error("Erro ao criar tabela:", err);
    }
});

// Endpoint para adicionar uma reserva
app.post('/api/reservas', async (req, res) => {
    const { nome, dataReserva, horaInicio, horaFim, sala } = req.body;

    try {
        // Verificar conflito de horário
        const result = await pool.query(
            `SELECT * FROM reservas WHERE dataReserva = $1 AND sala = $2 AND 
            ((horaInicio BETWEEN $3 AND $4) OR (horaFim BETWEEN $3 AND $4))`,
            [dataReserva, sala, horaInicio, horaFim]
        );

        if (result.rows.length > 0) {
            return res.status(400).json({ error: 'Conflito de horário' });
        }

        // Adicionar reserva
        await pool.query(
            `INSERT INTO reservas (nome, dataReserva, horaInicio, horaFim, sala) VALUES ($1, $2, $3, $4, $5)`,
            [nome, dataReserva, horaInicio, horaFim, sala]
        );
        res.status(201).json({ message: 'Reserva adicionada com sucesso' });

    } catch (error) {
        console.error('Erro ao adicionar reserva:', error);
        res.status(500).json({ error: 'Erro no servidor' });
    }
});

// Endpoint para obter reservas
app.get('/api/reservas', async (req, res) => {
    const { dataReserva } = req.query;

    try {
        const result = await pool.query(
            `SELECT * FROM reservas WHERE dataReserva = $1 ORDER BY horaInicio`,
            [dataReserva]
        );
        res.json(result.rows);

    } catch (error) {
        console.error('Erro ao buscar reservas:', error);
        res.status(500).json({ error: 'Erro no servidor' });
    }
});

// Exclusão de reservas passadas (executado a cada 1 minuto)
setInterval(async () => {
    try {
        const now = new Date();
        await pool.query(
            `DELETE FROM reservas WHERE dataReserva || 'T' || horaFim < $1`,
            [now.toISOString()]
        );
        console.log("Reservas passadas excluídas.");

    } catch (error) {
        console.error("Erro ao excluir reservas passadas:", error);
    }
}, 60000);

app.listen(port, () => {
    console.log(`Servidor rodando em http://localhost:${port}`);
});
 */