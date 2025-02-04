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
                                    <li><a href="<?= base_url(session()->get('controller').'/user') ?>">User</a></li>
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

                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <strong class="card-title">All Users list</strong>
                                <a href="<?= base_url(session()->get('controller').'/add-user') ?>" class="pull-right btn btn-primary"><i class="fa fa-plus"></i> Add New User</a>
                            </div>
                            <div class="card-body">
                                <table id="bootstrap-data-table<?= (sizeof($users)>0) ? '' : '-no-data-table' ?>" class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>S.No</th>
                                            <th>User Name</th>
                                            <th>User Role</th>
                                            <th>User Phone Number</th>
                                            <th>User Email Id</th>
                                            <th>Edit / Delete</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($users)) : ?>
                                            <?php foreach ($users as $id => $user) : ?>
                                                <tr>
                                                    <td><?= esc($id+1) ?></td>
                                                    <td><?= esc($user['name']) ?></td>
                                                    <td><?= esc($user['role_name']) ?></td>
                                                    <td><?= esc($user['phone']) ?></td>
                                                    <td><?= esc($user['email']) ?></td>
                                                    <td class="text-center text-nowrap">
                                                        <a href="<?= base_url(session()->get('controller').'/edit-user/' . $user['id']) ?>"  class="btn btn-secondary btn-sm" ><i class="fa fa-pencil"></i> Edit</a>
                                                        <a href="javascript:void(0);" class="btn btn-danger btn-sm" 
                                                            hx-post="<?= base_url(session()->get('controller').'/delete-user/' . $user['id']) ?>" 
                                                            hx-headers='{"X-CSRF-TOKEN": "<?= csrf_hash() ?>"}'
                                                            hx-trigger="confirmed"
                                                            hx-on::after-request="handleResponse(event)"
                                                            hx-swap="none"
                                                            onClick="Swal.fire({
                                                                    title: 'Are you sure?',
                                                                    text: 'You won\'t be able to revert this!',
                                                                    icon: 'warning',
                                                                    showCancelButton: true,
                                                                    confirmButtonColor: '#3085d6',
                                                                    cancelButtonColor: '#d33',
                                                                    confirmButtonText: 'Yes, delete it!'
                                                                }).then((result) => {
                                                                    if (result.isConfirmed) {
                                                                        htmx.trigger(this, 'confirmed');  
                                                                    }
                                                                })">
                                                            <i class="fa fa-trash"></i> Delete
                                                        </a>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else : ?>
                                            <tr>
                                                <td colspan="6" class="text-center">No users found.</td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!-- .animated -->
        </div><!-- .content -->
        