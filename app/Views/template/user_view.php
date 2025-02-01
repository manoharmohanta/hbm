<div class="breadcrumbs">
            <div class="breadcrumbs-inner">
                <div class="row m-0">
                    <div class="col-sm-4">
                        <div class="page-header float-left">
                            <div class="page-title">
                                <h1>Employee</h1>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-8">
                        <div class="page-header float-right">
                            <div class="page-title">
                                <ol class="breadcrumb text-right">
                                    <li><a href="<?= base_url('hotel') ?>">Home</a></li>
                                    <li><a href="<?= base_url('hotel/employee') ?>">Employee</a></li>
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
                                <strong class="card-title">All Employee list</strong>
                                <a href="<?= base_url('hotel/add-employee') ?>" class="pull-right btn btn-primary"><i class="fa fa-plus"></i> Add New Hotel</a>
                            </div>
                            <div class="card-body">
                                <table id="bootstrap-data-table<?= (sizeof($hotels)>0) ? '' : '-no-data-table' ?>" class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>S.No</th>
                                            <th>Hotel Name</th>
                                            <th>Hotel Address</th>
                                            <th>Hotel Phone Number</th>
                                            <th>Hotel Email Id</th>
                                            <th>Edit / Delete</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($hotels)) : ?>
                                            <?php foreach ($hotels as $id => $hotel) : ?>
                                                <tr>
                                                    <td><?= esc($id+1) ?></td>
                                                    <td><?= esc($hotel['name']) ?></td>
                                                    <td><?= esc($hotel['address']) ?></td>
                                                    <td><?= esc($hotel['phone']) ?></td>
                                                    <td><?= esc($hotel['email_id']) ?></td>
                                                    <td class="text-center text-nowrap">
                                                        <a href="<?= base_url('hotel/edit/' . $hotel['id']) ?>"><i class="fa fa-pencil"></i></a> |
                                                        <a href="<?= base_url('hotel/delete/' . $hotel['id']) ?>" onclick="return confirm('Are you sure?');"><i class="fa fa-trash-o"></i></a>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else : ?>
                                            <tr>
                                                <td colspan="6" class="text-center">No hotels found.</td>
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
        