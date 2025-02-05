<div class="breadcrumbs">
    <div class="breadcrumbs-inner">
        <div class="row m-0">
            <div class="col-sm-4">
                <div class="page-header float-left">
                    <div class="page-title">
                        <h1>Roles</h1>
                    </div>
                </div>
            </div>
            <div class="col-sm-8">
                <div class="page-header float-right">
                    <div class="page-title">
                        <ol class="breadcrumb text-right">
                            <li><a href="<?= base_url(session()->get('controller')) ?>">Home</a></li>
                            <li><a href="<?= base_url(session()->get('controller').'/role') ?>">Roles</a></li>
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
                        <strong class="card-title">All Roles</strong>
                        <a href="<?= base_url(session()->get('controller').'/add-role') ?>" class="pull-right btn btn-primary"><i class="fa fa-plus"></i> Add New Role</a>
                    </div>
                    <div class="card-body">
                        <table id="bootstrap-data-table" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>S.No</th>
                                    <th>Role Name</th>
                                    <th>Created At</th>
                                    <th>Updated At</th>
                                    <th>Edit / Delete</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($roles)) : ?>
                                    <?php foreach ($roles as $id => $role) : ?>
                                        <tr>
                                            <td><?= esc($id + 1) ?></td>
                                            <td><?= ucwords(str_replace('_',' ',esc($role['name']))) ?></td>
                                            <td><?= esc($role['created_at']) ?></td>
                                            <td><?= esc($role['updated_at']) ?></td>
                                            <td class="text-center text-nowrap">
                                                <a href="<?= base_url(session()->get('controller').'/edit-role/' . $role['id']) ?>"  class="btn btn-secondary btn-sm" ><i class="fa fa-pencil"></i> Edit</a>
                                                <a href="javascript:void(0);" class="btn btn-danger btn-sm" 
                                                    hx-post="<?= base_url(session()->get('controller').'/delete-role/' . $role['id']) ?>" 
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
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div><!-- .animated -->
</div><!-- .content -->