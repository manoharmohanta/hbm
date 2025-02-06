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
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <strong class="card-title">All Hotels List</strong>
                        <a href="<?= base_url(session()->get('controller').'/add-hotel') ?>" class="btn btn-primary pull-right">
                            <i class="fa fa-plus"></i> Add New Hotel
                        </a>
                    </div>
                    <div class="card-body">
                        <table id="bootstrap-data-table" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>S.No</th>
                                    <th>Hotel Name</th>
                                    <th>Hotel Address</th>
                                    <th>Hotel Phone Number</th>
                                    <th>Hotel Email Id</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($hotels)) : ?>
                                    <?php foreach ($hotels as $index => $hotel) : ?>
                                        <tr>
                                            <td><?= esc($index + 1) ?></td>
                                            <td><?= esc($hotel['name']) ?></td>
                                            <td><?= esc($hotel['address']) ?></td>
                                            <td><?= esc($hotel['phone']) ?></td>
                                            <td><?= esc($hotel['email_id']) ?></td>
                                            <td class="text-center">
                                                <a href="<?= base_url(session()->get('controller').'/edit-hotel/' . $hotel['id']) ?>" class="btn btn-secondary btn-sm">
                                                    <i class="fa fa-pencil"></i> Edit
                                                </a>
                                                <a class="btn btn-danger btn-sm" 
                                                    href="javascript:void(0);" 
                                                    hx-post="<?= base_url(session()->get('controller').'/delete-hotel/' . $hotel['id']) ?>" 
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
                                                        })"
                                                >
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