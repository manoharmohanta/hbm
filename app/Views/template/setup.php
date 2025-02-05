<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Setup <?= WEBSITE_NAME ?></title>
    <link rel="shortcut icon" href="<?= base_url('public/') ?>images/favicon.png" type="image/x-icon">
    <!-- Tailwind CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <!-- HTMX CDN -->
    <script src="https://unpkg.com/htmx.org@1.9.0"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">
    <div class="text-center">
        <h1 class="text-4xl font-bold text-gray-800 mb-4">Welcome to <?= WEBSITE_NAME ?></h1>
        <p class="text-gray-600 mb-8">This is a simple single-page setup with a centered button and HTMX support.</p>
        
        <!-- Centered Button with HTMX -->
        <button 
            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition duration-300 ease-in-out transform hover:scale-105"
            hx-post="<?= base_url() ?>setup/setupDatabase" 
            hx-headers='{"X-CSRF-TOKEN": "<?= csrf_hash() ?>"}'
            hx-trigger="click" 
            hx-target="#response" 
            hx-swap="innerHTML"
        >
            Start Setup
        </button>

        <!-- Response Area -->
        <div id="response" class="mt-4 text-gray-700"></div>
    </div>
</body>
</html>