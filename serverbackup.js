const express = require('express');
const bodyParser = require('body-parser');
const cors = require('cors');
const fs = require('fs');
const path = require('path'); // Dodane do obsługi ścieżek

const app = express();
const PORT = 3000;

app.use(express.static('public'));
app.use(cors());
app.use(bodyParser.json());

// Simulowana baza danych (plik JSON)
const DATA_FILE = './data.json';

function readData() {
    if (!fs.existsSync(DATA_FILE)) return [];
    const data = fs.readFileSync(DATA_FILE);
    return JSON.parse(data);
}

function writeData(data) {
    fs.writeFileSync(DATA_FILE, JSON.stringify(data, null, 2));
}

// Endpoint: Pobieranie wpisów
const fs = require('fs');
app.get('/api/posts', (req, res) => {
    const posts = readData();
    res.json(posts);
});
/*app.get('/api/posts', (req, res) => {
    fs.readFile('data.json', 'utf-8', (err, data) => {
        if (err) {
            console.error('Błąd odczytu pliku data.json:', err.message);
            res.status(500).send('Nie udało się odczytać danych.');
        } else {
            try {
                const posts = JSON.parse(data); // Parsuj JSON na obiekt
                res.json(posts); // Wyślij dane jako odpowiedź w formacie JSON
            } catch (parseErr) {
                console.error('Błąd parsowania JSON:', parseErr.message);
                res.status(500).send('Nie udało się przetworzyć danych.');
            }
        }
    });
});*/

// Endpoint: Dodawanie nowego wpisu
app.post('/api/posts', (req, res) => {
    const posts = readData();
    const newPost = req.body;
    newPost.id = Date.now();
    posts.push(newPost);
    writeData(posts);
    res.status(201).json(newPost);
});

// Serwowanie plików statycznych (HTML, CSS, JS)
app.use(express.static(path.join(__dirname, 'public')));

// Uruchomienie serwera
app.listen(PORT, () => {
    console.log(`Serwer działa na http://localhost:${PORT}`);
});
