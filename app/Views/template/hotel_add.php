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
                                    <li><a href="<?= base_url('hotel') ?>">Home</a></li>
                                    <li><a href="<?= base_url('hotel/hotel') ?>">Hotel</a></li>
                                    <li class="active"><a href="<?= base_url('hotel/add-hotel') ?>">Add New Hotel</a></li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Content -->
        <div class="content">
            <!-- Animated -->
            <div class="animated fadeIn">
                <!-- Widgets  -->
                <div class="row">
                    <div class="col-md-12 mb-4">
                        <div class="card">
                            <div class="card-header"><strong>Hotel Details</strong><small> New</small></div>
                            <div class="card-body card-block">
                                <form hx-post="<?= base_url('hotel/add-hotel') ?>" hx-trigger="click[event.target.matches('button')]"
                                        hx-on::after-request="handleResponse(event)"
                                        hx-swap="none">
                                    <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label for="name" class=" form-control-label">Hotel Name</label>
                                                <input type="text" name="name" placeholder="Enter your name" class="form-control" value="">
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label for="user" class=" form-control-label">Select Hotel Manger Name</label>
                                                <select name="select" id="select" class="form-control">
                                                    <option value="0">Please select</option>
                                                    <option value="1">Option #1</option>
                                                    <option value="2">Option #2</option>
                                                    <option value="3">Option #3</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label for="email" class=" form-control-label">Email Id</label>
                                                <input type="email" name="email" placeholder="Enter your email" class="form-control" value="">
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label for="phone" class=" form-control-label">Phone Number</label>
                                                <input type="tel" name="phone" placeholder="Enter your phone number" class="form-control" value="">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="address" class=" form-control-label">Address</label>
                                        <input type="text" name="address" placeholder="Enter hotel address" class="form-control">
                                    </div>
                                    
                                    <button type="submit" class="btn btn-primary btn-flat pull-right m-b-30 m-t-30">Update Details</button>
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
<script>
    jQuery(document).ready(function() {
        jQuery(".standardSelect").chosen({
            disable_search_threshold: 10,
            no_results_text: "Oops, nothing found!",
            width: "100%"
        });
    });
</script>