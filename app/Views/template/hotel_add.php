<div class="breadcrumbs">
    <div class="breadcrumbs-inner">
        <div class="row m-0">
            <div class="col-sm-4">
                <div class="page-header float-left">
                    <div class="page-title">
                        <h1>Hotel</h1>
                    </div>
                </div>
            </div>
            <div class="col-sm-8">
                <div class="page-header float-right">
                    <div class="page-title">
                        <ol class="breadcrumb text-right">
                            <li><a href="<?= base_url(session()->get('controller')) ?>">Home</a></li>
                            <li><a href="<?= base_url(session()->get('controller').'/hotel') ?>">Hotel</a></li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="content">
    <div class="animated fadeIn">
        <div class="row">
            <div class="col-md-12 mb-4">
                <div class="card">
                    <div class="card-header">
                        <strong>Hotel Details</strong>
                        <small><?= isset($hotel) ? 'Edit' : 'New' ?></small>
                    </div>
                    <div class="card-body card-block">
                        <form hx-post="<?= isset($hotel) ? base_url(session()->get('controller').'/edit-hotel/'.$hotel['id']) : base_url(session()->get('controller').'/add-hotel') ?>" 
                            hx-target="this"
                            hx-trigger="click[event.target.matches('button')]"
                            hx-on::after-request="handleResponse(event)"
                            hx-swap="none" class="form"
                        >
                            <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">

                            <?php if (isset($hotel)) : ?>
                                <input type="hidden" name="id" value="<?= $hotel['id'] ?>">
                            <?php endif; ?>
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="name">Hotel Name</label>
                                        <input type="text" name="name" class="form-control" value="<?= isset($hotel) ? esc($hotel['name']) : '' ?>" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="phone">Contact Number</label>
                                        <input type="text" name="phone" class="form-control" value="<?= isset($hotel) ? esc($hotel['phone']) : '' ?>" required>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="email_id">Email</label>
                                        <input type="email" name="email_id" class="form-control" value="<?= isset($hotel) ? esc($hotel['email_id']) : '' ?>" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="address">Address</label>
                                        <textarea name="address" class="form-control" required><?= isset($hotel) ? esc($hotel['address']) : '' ?></textarea>
                                    </div>
                                </div>
                            </div>
                            <!-- Submit Button -->
                            <button type="submit" 
                                    class="btn btn-primary btn-flat pull-right m-b-30 m-t-30"
                                    onclick="this.dataset.originalText = this.innerText; this.disabled = true; this.innerText = '<?= isset($hotel) ? 'Updating...' : 'Saving...' ?>';">
                                <?= isset($hotel) ? 'Update Hotel' : 'Save Hotel' ?>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>