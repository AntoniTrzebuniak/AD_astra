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
//const DATA_FILE = './data${discussionID}.json';

function getDataFile(discussionID) {
    return `./data${discussionID}.json`;
}
function readData(discussionID) {
    const dataFile = getDataFile(discussionID);
    if (!fs.existsSync(dataFile)) return [];
    const data = fs.readFileSync(dataFile);
    return JSON.parse(data);
}

function writeData(discussionID, data) {
    const dataFile = getDataFile(discussionID);
    fs.writeFileSync(dataFile, JSON.stringify(data, null, 2));
}

// Endpoint: Pobieranie wpisów

app.get('/api/posts/:discussionID', (req, res) => {
    const { discussionID } = req.params;
    const posts = readData();
    res.json(posts);
});


// Endpoint: Dodawanie nowego wpisu
app.post('/api/posts/:discussionID', (req, res) => {
    console.log('Discussion ID:', req.params.discussionID); // Loguj ID
    console.log('New Post Data:', req.body); // Loguj dane nowego wpisu
    const { discussionID } = req.params;
    const posts = readData(discussionID);
    const newPost = req.body;
    newPost.id = Date.now();
    posts.push(newPost);
    writeData(discussionID, posts);
    res.status(201).json(newPost);
});

// Serwowanie plików statycznych (HTML, CSS, JS)
app.use(express.static(path.join(__dirname, 'public')));

// Uruchomienie serwera
app.listen(PORT, () => {
    console.log(`Serwer działa na http://localhost:${PORT}`);
});
