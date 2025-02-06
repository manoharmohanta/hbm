
        <!-- Content -->
        <div class="content">
            <!-- Animated -->
            <div class="animated fadeIn">
                <!-- Widgets  -->
                <div class="row">
                    <div class="col-md-4 mb-4">
                        <div class="card">
                            <div class="card-header">
                                <i class="fa fa-user"></i><strong class="card-title pl-2"><?= ucwords(str_replace('_', ' ',$user['role_name'])) ?> Profile Details</strong>
                            </div>
                            <div class="card-body">
                                <div class="mx-auto d-block">
                                    <img class="rounded-circle mx-auto d-block" src="<?= base_url('public/')?>images/admin.jpg" alt="Card image cap">
                                    <h5 class="text-sm-center mt-2 mb-1"><?= $user['name'] ?> (<?= ucwords(str_replace('_', ' ',$user['role_name'])) ?>)</h5>
                                    <div class="location text-sm-center"><i class="fa fa-envelope"></i> <?= $user['email'] ?></div>
                                    <div class="text-sm-center"><i class="fa fa-phone"></i> <?= $user['phone'] ?></div>
                                    <div class="text-sm-center"><i class="fa fa-calendar"></i> <?= $user['created_at'] ?></div>
                                </div>
                                <hr>
                                <div class="card-text text-sm-center">
                                    <a href="#"><i class="fa fa-facebook pr-1"></i></a>
                                    <a href="#"><i class="fa fa-twitter pr-1"></i></a>
                                    <a href="#"><i class="fa fa-linkedin pr-1"></i></a>
                                    <a href="#"><i class="fa fa-pinterest pr-1"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-8 mb-4">
                        <div class="card">
                            <div class="card-header"><strong>User Details</strong><small> Update</small></div>
                            <div class="card-body card-block">
                                <form hx-post="<?= base_url(session()->get('controller').'/profile') ?>" hx-trigger="click[event.target.matches('button')]"
                                        hx-on::after-request="handleResponse(event)"
                                        hx-swap="none">
                                    <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">
                                    <div class="form-group">
                                        <label for="name" class=" form-control-label">Full Name (As per passport)</label>
                                        <input type="text" name="name" placeholder="Enter your name" class="form-control" value="<?= $user['name'] ?>">
                                    </div>
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label for="email" class=" form-control-label">Email Id</label>
                                                <input type="email" name="email" placeholder="Enter your email" class="form-control" value="<?= $user['email'] ?> " disabled>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label for="phone" class=" form-control-label">Phone Number</label>
                                                <input type="tel" name="phone" placeholder="Enter your phone number" class="form-control" value="<?= $user['phone'] ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="password" class=" form-control-label">Update Password</label>
                                        <input type="password" name="password" placeholder="Enter new password" class="form-control">
                                    </div>
                                    <button type="submit" class="btn btn-primary btn-flat pull-right m-b-30 m-t-30"  onclick="this.disabled = true; this.innerText = 'Updateing...';">Update Details</button>
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
        <!-- /.content -->
        