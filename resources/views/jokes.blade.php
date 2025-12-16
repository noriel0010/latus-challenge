<!-- I have not failed. I've just found 10,000 ways that won't work. - Thomas Edison -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Random Jokes</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 dark:bg-gray-900 antialiased min-h-screen flex flex-col items-center justify-start py-10">

    <div class="w-full max-w-3xl p-6 bg-white dark:bg-gray-800 rounded-lg shadow-lg">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">Random Programming Jokes</h1>
            <button id="refreshBtn" class="py-1 px-4 bg-red-500 hover:bg-red-600 text-white rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-red-500 transition">
                Refresh
            </button>
        </div>

        <div id="jokesContainer" class="space-y-4">
        </div>
    </div>

    <script>
        const jokesContainer = document.getElementById('jokesContainer');
        const refreshBtn = document.getElementById('refreshBtn');

        async function fetchJokes() {
            jokesContainer.innerHTML = '<p class="text-gray-500 dark:text-gray-300">Loading jokes...</p>';

            try {
                const token = localStorage.getItem('auth_token');
                const res = await fetch('/api/jokes', {
                    headers: {
                        'Accept': 'application/json',
                        'Authorization': token ? `Bearer ${token}` : ''
                    }
                });
                const jokes = await res.json();

                if (!res.ok) {
                    jokesContainer.innerHTML = `<p class="text-red-500">Failed to load jokes.</p>`;
                    return;
                }

                jokesContainer.innerHTML = jokes.map(joke => `
                    <div class="p-4 bg-gray-50 dark:bg-gray-700 rounded-lg shadow-sm">
                        <p class="font-semibold text-gray-900 dark:text-white">${joke.setup}</p>
                        <p class="mt-2 text-gray-600 dark:text-gray-300 italic">${joke.punchline}</p>
                    </div>
                `).join('');

            } catch (err) {
                jokesContainer.innerHTML = `<p class="text-red-500">Network error.</p>`;
            }
        }

        fetchJokes();

        refreshBtn.addEventListener('click', fetchJokes);
    </script>
</body>
</html>

