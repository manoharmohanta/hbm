<div class="clearfix"></div>
        <!-- Footer -->
        <footer class="site-footer">
            <div class="footer-inner bg-white">
                <div class="row">
                    <div class="col-sm-6">
                        Copyright &copy; <?= date('Y'). ' '. WEBSITE_NAME ?> 
                    </div>
                    <div class="col-sm-6 text-right">
                        Designed by <a href="<?= base_url() ?>">Sunglade Digital Solutions</a>
                    </div>
                </div>
            </div>
        </footer>
        <!-- /.site-footer -->
    </div>
    <!-- /#right-panel -->

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@2.2.4/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.4/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-match-height@0.7.2/dist/jquery.matchHeight.min.js"></script>
    <script src="<?= base_url('public/') ?>assets/js/main.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/moment@2.22.2/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function handleResponse(event) {
            const response = event.detail.xhr.response;

            try {
                const responseData = JSON.parse(response);

                // Check the response status
                if (responseData.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: responseData.message,
                    }).then(() => {
                        // Redirect to the dashboard after the alert closes
                        window.location.href = responseData.redirectUrl;  // Replace with the correct URL of your dashboard
                    });
                } else if (responseData.status === 'error') {
                    let errorMessage = responseData.message || 'An unknown error occurred.'; // Use the error message if present

                    // If there are errors, show them as a list
                    if (responseData.errors) {
                        let errorMessages = '<ul>';
                        for (const key in responseData.errors) {
                            errorMessages += `<li>${responseData.errors[key]}</li>`;
                        }
                        errorMessages += '</ul>';
                        errorMessage += '<br>' + errorMessages;
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

                // Optionally update the CSRF token if needed
                if (responseData.csrf_token) {
                    document.querySelector('input[name="<?= csrf_token() ?>"]').value = responseData.csrf_token;
                    document.querySelector('meta[name="csrf-token"]').content = response.csrf_token;
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
