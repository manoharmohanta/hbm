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
            hx-on::after-request="handleResponse(event)"
        >
            Start Setup
        </button>

        <!-- Response Area -->
        <div id="response" class="mt-4 text-gray-700"></div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/jquery@2.2.4/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function handleResponse(event) {
            const response = event.detail.xhr.response;

            try {
                const responseData = typeof response === 'string' ? JSON.parse(response) : response;

                // Check the response status
                if (responseData.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: responseData.message,
                    }).then(() => {
                        // Redirect after success
                        window.location.href = responseData.redirectUrl;
                    });

                } else if (responseData.status === 'error') {
                    let errorMessage = 'An unknown error occurred.';

                    // Handle error messages properly
                    if (typeof responseData.message === 'string') {
                        errorMessage = responseData.message;
                    } else if (typeof responseData.message === 'object' && responseData.message !== null) {
                        let errorMessages = '<ul>';
                        Object.entries(responseData.message).forEach(([key, value]) => {
                            errorMessages += `<li><strong>${key}:</strong> ${value}</li>`;
                        });
                        errorMessages += '</ul>';
                        errorMessage = errorMessages;
                    }

                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        html: errorMessage,
                    });

                } else {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Unexpected Response',
                        text: 'Received an unexpected response status.',
                    });
                }

                // Update CSRF token if present
                if (responseData.csrf_token) {
                    let csrfInput = document.querySelector('input[name="<?= csrf_token() ?>"]');
                    if (csrfInput) {
                        csrfInput.value = responseData.csrf_token;
                    }

                    let csrfMeta = document.querySelector('meta[name="csrf-token"]');
                    if (csrfMeta) {
                        csrfMeta.content = responseData.csrf_token;
                    }
                }

            } catch (e) {
                // Handle JSON parse errors or other unexpected issues
                Swal.fire({
                    icon: 'error',
                    title: 'Response Error',
                    text: 'Failed to process the server response. Please try again.',
                });
                console.error('Error parsing response:', e);
            }
        }
    </script>
</body>
</html>