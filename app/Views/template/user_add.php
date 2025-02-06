<div class="breadcrumbs">
    <div class="breadcrumbs-inner">
        <div class="row m-0">
            <div class="col-sm-4">
                <div class="page-header float-left">
                    <div class="page-title">
                        <h1>Users</h1>
                    </div>
                </div>
            </div>
            <div class="col-sm-8">
                <div class="page-header float-right">
                    <div class="page-title">
                        <ol class="breadcrumb text-right">
                            <li><a href="<?= base_url(session()->get('controller')) ?>">Home</a></li>
                            <li><a href="<?= base_url(session()->get('controller').'/user') ?>">Users</a></li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="content">
    <!-- Animated -->
    <div class="animated fadeIn">
        <!-- Widgets  -->
        <div class="row">
            <div class="col-md-12 mb-4">
                <div class="card">
                    <div class="card-header">
                        <strong>User Details</strong>
                        <small><?= isset($users) ? 'Edit' : 'New' ?></small>
                    </div>
                    <div class="card-body card-block">
                        <!-- Form -->
                        <form 
                            hx-post="<?= isset($users) ? base_url(session()->get('controller') . '/edit_user/' . $users['id']) : base_url(session()->get('controller') . '/add_user') ?>" 
                            hx-trigger="click[event.target.matches('button')]"
                            hx-on::after-request="handleResponse(event)"
                            hx-swap="none"
                        >
                            <!-- CSRF Token -->
                            <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">

                            <!-- Hidden user ID (for edit) -->
                            <?php if (isset($users)) : ?>
                                <input type="hidden" name="id" value="<?= $users['id'] ?>">
                            <?php endif; ?>

                            <!-- User Name Field -->
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="name" class="form-control-label">Full Name(As per Passport)</label>
                                        <input type="text" name="name" placeholder="Enter full name" class="form-control" 
                                            value="<?= isset($users) ? esc($users['name']) : '' ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="name" class="form-control-label">Email</label>
                                        <input type="text" name="email" placeholder="Enter email" class="form-control" 
                                            value="<?= isset($users) ? esc($users['email']) : '' ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="name" class="form-control-label">Phone</label>
                                        <input type="tel" name="phone" placeholder="Enter phone" class="form-control" 
                                            value="<?= isset($users) ? esc($users['phone']) : '' ?>">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="name" class="form-control-label">Hotel Name</label>
                                        <select name="hotel_id" id="select" class="form-control">
                                            <option value="">Please select</option>
                                            <?php 
                                                if(isset($hotels)){
                                                    foreach($hotels as $hotel){
                                                        if(isset($userRelation['hotel_id'])){
                                                            echo '<option  value="'.$hotel['id'].'" '. (($userRelation['hotel_id'] == $hotel['id']) ? 'selected': '') .'>'. $hotel['name'] .'</option>';
                                                        }else{
                                                            echo '<option value="'.$hotel['id'].'">'. $hotel['name'] .'</option>';
                                                        }
                                                    }
                                                }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="name" class="form-control-label">Role Name</label>
                                        <select name="role_id" id="select" class="form-control">
                                            <option value="">Please select</option>
                                            <?php 
                                                if (isset($roles)) {
                                                    foreach ($roles as $role) {
                                                        if(isset($users['role_id'])){
                                                            echo '<option value="' . $role['id'] . '" ' . (($users['role_id'] == $role['id']) ? 'selected' : '') . '>' 
                                                                    . ucwords(str_replace('_', ' ', $role['name'])) 
                                                                    . '</option>';
                                                        }else{
                                                            echo '<option value="'. $role['id'] .'">'. ucwords(str_replace('_', ' ', $role['name']))  .'</option>';
                                                        }
                                                    }
                                                }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="name" class="form-control-label"><?= isset($users) ? 'Update Password' : 'New Password' ?></label>
                                        <input type="password" name="password" placeholder="Enter password" class="form-control" >
                                    </div>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <button type="submit" 
                                    class="btn btn-primary btn-flat pull-right m-b-30 m-t-30"
                                    onclick="this.dataset.originalText = this.innerText; this.disabled = true; this.innerText = '<?= isset($users) ? 'Updating...' : 'Saving...' ?>';">
                                <?= isset($users) ? 'Update User' : 'Save User' ?>
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