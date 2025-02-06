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

    <script src="<?= base_url('public/') ?>assets/js/lib/data-table/datatables.min.js"></script>
    <script src="<?= base_url('public/') ?>assets/js/lib/data-table/dataTables.bootstrap.min.js"></script>
    <script src="<?= base_url('public/') ?>assets/js/lib/data-table/dataTables.buttons.min.js"></script>
    <script src="<?= base_url('public/') ?>assets/js/lib/data-table/buttons.bootstrap.min.js"></script>
    <script src="<?= base_url('public/') ?>assets/js/lib/data-table/jszip.min.js"></script>
    <script src="<?= base_url('public/') ?>assets/js/lib/data-table/vfs_fonts.js"></script>
    <script src="<?= base_url('public/') ?>assets/js/lib/data-table/buttons.html5.min.js"></script>
    <script src="<?= base_url('public/') ?>assets/js/lib/data-table/buttons.print.min.js"></script>
    <script src="<?= base_url('public/') ?>assets/js/lib/data-table/buttons.colVis.min.js"></script>
    <script src="<?= base_url('public/') ?>assets/js/init/datatables-init.js"></script>

    <script>
        function handleResponse(event) {
            const response = event.detail.xhr.response;
            let button = document.querySelector("button[type='submit']");
            let originalButtonText = "Submit"; // Default value
            if (button) {
                let originalButtonText = button.dataset.originalText || button.innerText || "Submit";
            }
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
                        let errorMessages = '<ul class="list-group list-group-flush">';
                        
                        // Merge `message` and `errors` if both exist
                        if (responseData.message && typeof responseData.message === 'object') {
                            Object.entries(responseData.message).forEach(([key, value]) => {
                                errorMessages += `<li class="list-group-item"><strong>${key.toUpperCase()}:</strong> ${value}</li>`;
                            });
                        }
                        if (responseData.errors && typeof responseData.errors === 'object') {
                            Object.entries(responseData.errors).forEach(([key, value]) => {
                                errorMessages += `<li class="list-group-item"><strong>${key.toUpperCase()}:</strong> ${value}</li>`;
                            });
                        }
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
            if (button) {
                // Restore button state
                button.disabled = false;
                button.innerText = originalButtonText;
            }
        }
    </script>
</body>
</html>
