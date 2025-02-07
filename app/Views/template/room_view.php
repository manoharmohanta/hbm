<div class="breadcrumbs">
    <div class="breadcrumbs-inner animated fadeIn">
        <div class="row m-0">
            <div class="col-sm-4">
                <div class="page-header float-left">
                    <div class="page-title">
                        <h1>Rooms</h1>
                    </div>
                </div>
            </div>
            <div class="col-sm-8">
                <div class="page-header float-right">
                    <div class="page-title">
                        <ol class="breadcrumb text-right">
                            <li><a href="<?= base_url(session()->get('controller')) ?>">Home</a></li>
                            <li><a href="<?= base_url(session()->get('controller').'/room') ?>">Rooms</a></li>
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
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="box-title">Select Hotel</h4>
                        <form hx="<?= base_url(session()->get('controller').'/room') ?>" hx-swap="#resultRooms">
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="hotel_id" class="control-label mb-1">Hotels</label>
                                        <select name="hotel_id" id="hotel_id" class="form-control">
                                            <option value="">Please select Hotel</option>
                                            <?php foreach($hotels as $hotel){ ?>
                                            <option value="<?= $hotel['id'] ?>"><?= ucwords($hotel['name']) ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <button type="submit" class="btn btn-primary btn-lg btn-block pull-right mt-3 m-b-30 m-t-30" onclick="this.dataset.originalText = this.innerText; this.disabled = true; this.innerText = 'Searching...';">Search Hotel Rooms</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="card" id="resultRooms">
                    
                </div>
            </div>
        </div>
    </div>
</div>