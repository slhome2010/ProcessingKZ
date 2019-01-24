<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
	  <div class="pull-right">
        <button type="submit" form="form-processingkz" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a>
	  </div>
	  <h1><img src="view/image/payment.png" alt="" /> <?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>

 <div class="container-fluid">
    <?php if ($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
	<div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-processingkz" class="form-horizontal">
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-total"><span data-toggle="tooltip" title="<?php echo $help_total; ?>"><?php echo $entry_total; ?></span></label>
            <div class="col-sm-10">
              <input type="text" name="processingkz_total" value="<?php echo $processingkz_total; ?>" placeholder="<?php echo $entry_total; ?>" id="input-total" class="form-control" />
            </div>
          </div>
		  <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-shop_id"><?php echo $entry_shop_id; ?></label>
            <div class="col-sm-10">
              <input type="text" name="processingkz_shop_id" value="<?php echo $processingkz_shop_id; ?>" placeholder="<?php echo $entry_shop_id; ?>" id="input-shop_id" class="form-control" />
              <?php if ($error_shop_id) { ?>
              <div class="text-danger"><?php echo $error_shop_id  ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-check_status"><?php echo $entry_check_status; ?></label>
            <div class="col-sm-10">
              <select name="processingkz_check_status_id" id="input-check_status" class="form-control">
                <?php foreach ($check_statuses as $check_status) { ?>
                <?php if ($check_status['order_status_id'] == $processingkz_check_status_id) { ?>
                <option value="<?php echo $check_status['order_status_id']; ?>" selected="selected"><?php echo $check_status['name']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $check_status['order_status_id']; ?>"><?php echo $check_status['name']; ?></option>
                <?php } ?>
                <?php } ?>
              </select>
            </div>
          </div>
		  <div class="form-group">
            <label class="col-sm-2 control-label" for="input-declined_status"><?php echo $entry_declined_status; ?></label>
            <div class="col-sm-10">
              <select name="processingkz_declined_status_id" id="input-declined_status" class="form-control">
                <?php foreach ($declined_statuses as $declined_status) { ?>
                <?php if ($declined_status['order_status_id'] == $processingkz_declined_status_id) { ?>
                <option value="<?php echo $declined_status['order_status_id']; ?>" selected="selected"><?php echo $declined_status['name']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $declined_status['order_status_id']; ?>"><?php echo $declined_status['name']; ?></option>
                <?php } ?>
                <?php } ?>
              </select>
            </div>
          </div>
		  <div class="form-group">
            <label class="col-sm-2 control-label" for="input-canceled_status"><?php echo $entry_canceled_status; ?></label>
            <div class="col-sm-10">
              <select name="processingkz_canceled_status_id" id="input-canceled_status" class="form-control">
                <?php foreach ($canceled_statuses as $canceled_status) { ?>
                <?php if ($canceled_status['order_status_id'] == $processingkz_canceled_status_id) { ?>
                <option value="<?php echo $canceled_status['order_status_id']; ?>" selected="selected"><?php echo $canceled_status['name']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $canceled_status['order_status_id']; ?>"><?php echo $canceled_status['name']; ?></option>
                <?php } ?>
                <?php } ?>
              </select>
            </div>
          </div>
		  <div class="form-group">
            <label class="col-sm-2 control-label" for="input-order_status"><?php echo $entry_order_status; ?></label>
            <div class="col-sm-10">
              <select name="processingkz_order_status_id" id="input-order_status" class="form-control">
                <?php foreach ($order_statuses as $order_status) { ?>
                <?php if ($order_status['order_status_id'] == $processingkz_order_status_id) { ?>
                <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                <?php } ?>
                <?php } ?>
              </select>
            </div>
          </div>
		  <div class="form-group">
            <label class="col-sm-2 control-label" for="input-comission_status"><?php echo $entry_comission_status; ?></label>
            <div class="col-sm-10">
              <select name="processingkz_comission_status_id" id="input-comission_status" class="form-control">
                <?php foreach ($comission_statuses as $comission_status) { ?>
                <?php if ($comission_status['comission_status_id'] == $processingkz_comission_status_id) { ?>
                <option value="<?php echo $comission_status['comission_status_id']; ?>" selected="selected"><?php echo $comission_status['name']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $comission_status['comission_status_id']; ?>"><?php echo $comission_status['name']; ?></option>
                <?php } ?>
                <?php } ?>
              </select>
            </div>
          </div>
           <div class="form-group">
            <label class="col-sm-2 control-label" for="input-visa_big_total"><?php echo $entry_visa_big_total; ?></label>
            <div class="col-sm-10">
              <input type="text" name="processingkz_visa_big_total" value="<?php echo $processingkz_visa_big_total; ?>" placeholder="<?php echo $entry_visa_big_total; ?>" id="input-visa_big_total" class="form-control" />              
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-apikey"><?php echo $entry_apikey; ?></label>
            <div class="col-sm-10">
              <input type="text" name="processingkz_apikey" value="<?php echo $processingkz_apikey; ?>" placeholder="<?php echo $entry_apikey; ?>" id="input-apikey" class="form-control" />              
            </div>
          </div>
		  <div class="form-group">
            <label class="col-sm-2 control-label" for="input-geo_zone"><?php echo $entry_geo_zone; ?></label>
            <div class="col-sm-10">
              <select name="processingkz_geo_zone_id" id="input-geo_zone" class="form-control">
			    <option value="0"><?php echo $text_all_zones; ?></option>
                <?php foreach ($geo_zones as $geo_zone) { ?>
                <?php if ($geo_zone['geo_zone_id'] == $processingkz_geo_zone_id) { ?>
                <option value="<?php echo $geo_zone['geo_zone_id']; ?>" selected="selected"><?php echo $geo_zone['name']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $geo_zone['geo_zone_id']; ?>"><?php echo $geo_zone['name']; ?></option>
                <?php } ?>
                <?php } ?>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
            <div class="col-sm-10">
              <select name="processingkz_status" id="input-status" class="form-control">
                <?php if ($processingkz_status) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select>
            </div>
          </div>
		  <div class="form-group">
            <label class="col-sm-2 control-label" for="input-sort-order"><?php echo $entry_sort_order; ?></label>
            <div class="col-sm-10">
              <input type="text" name="processingkz_sort_order" value="<?php echo $processingkz_sort_order; ?>" placeholder="<?php echo $entry_sort_order; ?>" id="input-sort-order" class="form-control" />
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<?php echo $footer; ?> 