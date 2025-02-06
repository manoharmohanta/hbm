<div class="content">
    <!-- Animated -->
    <div class="animated fadeIn">
        <!-- Widgets  -->
        <div class="row">
            <div class="col-md-12 mb-4">
                <div class="card">
                    <div class="card-header">
                        <strong>Role Details</strong>
                        <small><?= isset($role) ? 'Edit' : 'New' ?></small>
                    </div>
                    <div class="card-body card-block">
                        <!-- Form -->
                        <form 
                            hx-post="<?= isset($role) ? base_url(session()->get('controller') . '/edit_role/' . $role['id']) : base_url(session()->get('controller') . '/add_role') ?>" 
                            hx-trigger="click[event.target.matches('button')]"
                            hx-on::after-request="handleResponse(event)"
                            hx-swap="none"
                        >
                            <!-- CSRF Token -->
                            <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">

                            <!-- Hidden Role ID (for edit) -->
                            <?php if (isset($role)) : ?>
                                <input type="hidden" name="id" value="<?= $role['id'] ?>">
                            <?php endif; ?>

                            <!-- Role Name Field -->
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="name" class="form-control-label">Role Name</label>
                                        <input 
                                            type="text" 
                                            name="name" 
                                            placeholder="Enter role name" 
                                            class="form-control" 
                                            value="<?= isset($role) ? esc($role['name']) : '' ?>"
                                        >
                                    </div>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <button type="submit" 
                                    class="btn btn-primary btn-flat pull-right m-b-30 m-t-30"
                                    onclick="this.dataset.originalText = this.innerText; this.disabled = true; this.innerText = '<?= isset($role) ? 'Updating...' : 'Saving...' ?>';">
                                <?= isset($role) ? 'Update Role' : 'Save Role' ?>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Widgets -->
        <div class="clearfix"></div>
    </div>
    <!-- .animated -->
</div>