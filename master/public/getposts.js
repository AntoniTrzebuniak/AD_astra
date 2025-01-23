document.getElementById('new-post-form').addEventListener('submit', async function(event) {
    event.preventDefault();

    const username = document.getElementById('username').value;
    const content = document.getElementById('post-content').value;

    if (username && content) {
        const newPost = {
            username,
            content,
            time: new Date().toLocaleString()
        };

        try {
            const response = await fetch('/api/posts', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(newPost),
            });

            if (response.ok) {
                const savedPost = await response.json();

                const postElement = document.createElement('div');
                postElement.classList.add('post');
                postElement.innerHTML = `
                    <div class="post-author">
                        <strong>${savedPost.username}</strong>
                        <span class="post-time">${savedPost.time}</span>
                    </div>
                    <p>${savedPost.content}</p>
                `;

                document.querySelector('.discussion').appendChild(postElement);

                // Clear form fields
                document.getElementById('username').value = '';
                document.getElementById('post-content').value = '';
            }
        } catch (error) {
            console.error('Błąd przy dodawaniu wpisu:', error);
        }
    }
    });
    document.addEventListener('DOMContentLoaded', function () {
// Pobierz zapisane wpisy z serwera
fetch('/api/posts')
    .then(response => {
        if (!response.ok) {
            throw new Error('Błąd podczas pobierania wpisów');
        }
        return response.json(); // Parsowanie odpowiedzi jako JSON
    })
    .then(posts => {
        const discussionSection = document.querySelector('.posts-container');

        // Wyświetl każdy wpis
        posts.forEach(post => {
            const postElement = document.createElement('div');
            postElement.classList.add('post');
            postElement.innerHTML = `
                <div class="post-author">
                    <strong>${post.username}</strong>
                    <span class="post-time">${post.time}</span>
                </div>
                <p>${post.content}</p>
            `;
            discussionSection.appendChild(postElement);
        });
    })
    .catch(error => {
        console.error('Błąd:', error.message);
    });
});