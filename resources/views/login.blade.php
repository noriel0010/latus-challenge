<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="antialiased bg-gray-100 dark:bg-gray-900 flex items-center justify-center min-h-screen">

    <div class="w-full max-w-md p-8 bg-white dark:bg-gray-800 rounded-lg shadow-lg">
        <h2 class="text-2xl font-semibold text-gray-900 dark:text-white text-center mb-6">Login</h2>

        <form id="loginForm" class="space-y-4">
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
                <input type="email" name="email" id="email" placeholder="you@example.com" required
                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-red-500 focus:ring focus:ring-red-500 focus:ring-opacity-50 dark:bg-gray-700 dark:text-white">
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Password</label>
                <input type="password" name="password" id="password" placeholder="••••••••" required
                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-red-500 focus:ring focus:ring-red-500 focus:ring-opacity-50 dark:bg-gray-700 dark:text-white">
            </div>

            <button type="submit"
                class="w-full py-2 px-4 bg-red-500 hover:bg-red-600 text-white font-semibold rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-1 transition">
                Login
            </button>
        </form>

        <div id="error" class="mt-4 text-sm text-red-500 text-center"></div>
    </div>

    <script>
        const form = document.getElementById('loginForm');
        form.addEventListener('submit', async (e) => {
            e.preventDefault();

            const formData = new FormData(form);
            const data = {
                email: formData.get('email'),
                password: formData.get('password'),
            };

            try {
                const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                const res = await fetch('/api/login', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': token
                    },
                    body: JSON.stringify(data)
                });

                const result = await res.json();

                if (!res.ok) {
                    document.getElementById('error').innerText = result.message || 'Login failed';
                    return;
                }

                localStorage.setItem('auth_token', result.access_token);
                window.location.href = '/jokes';
            } catch (err) {
                document.getElementById('error').innerText = 'Network error';
            }
        });
    </script>
</body>
</html>
