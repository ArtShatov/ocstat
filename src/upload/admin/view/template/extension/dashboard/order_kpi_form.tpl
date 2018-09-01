<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-dashboard_order_kpi" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
      <h1><?php echo $heading_title; ?></h1>
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
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-dashboard" class="form-horizontal">
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-width"><?php echo $entry_width; ?></label>
            <div class="col-sm-10">
              <select name="dashboard_order_kpi_width" id="input-width" class="form-control">
                <?php foreach ($columns as $column) { ?>
                <?php if ($column == $dashboard_order_kpi_width) { ?>
                <option value="<?php echo $column; ?>" selected="selected"><?php echo $column; ?></option>
                <?php } else { ?>
                <option value="<?php echo $column; ?>"><?php echo $column; ?></option>
                <?php } ?>
                <?php } ?>
              </select>
            </div>
          </div>


          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-filter"><?php echo $entry_statuses; ?></label>
            <div class="col-sm-10">
              <ul id="order-statuses-list" class="well well-sm">
                <?php foreach ($order_statuses as $order_status) { ?>
                <li>
                  <i class="fa fa-fw fa-sort"></i>
                  <label>
                    <input type="checkbox" name="tt" value="1" <?php if($order_status['active']) { ?> checked <?php } ?> class="js-change-status" />
                    <input type="hidden" name="dashboard_order_kpi_order_statuses[<?php echo $order_status['order_status_id']; ?>]" value="<?php echo $order_status['active'] ? 1 : 0; ?>" />
                    <?php echo $order_status['name']; ?>
                  </label>
                </li>

                <?php } ?>
              </ul>

              <a onclick="$(this).parent().find(':checkbox').prop('checked', true).trigger('change');"><?php echo $text_select_all; ?></a> / <a onclick="$(this).parent().find(':checkbox').prop('checked', false).trigger('change');"><?php echo $text_unselect_all; ?></a>

          </div>
          </div>


          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
            <div class="col-sm-10">
              <select name="dashboard_order_kpi_status" id="input-status" class="form-control">
                <?php if ($dashboard_order_kpi_status) { ?>
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
              <input type="text" name="dashboard_order_kpi_sort_order" value="<?php echo $dashboard_order_kpi_sort_order; ?>" placeholder="<?php echo $entry_sort_order; ?>" id="input-sort-order" class="form-control" />
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
  $(function() {
      $('#order-statuses-list').sortable({
          items:  '> li'
      });

      $('.js-change-status').on('change', function() {
         var $checkbox = $(this);
         var value = $checkbox.is(":checked") ? 1 : 0;
         $checkbox.parent().find('input[type=hidden]').val(value);

      });
  });
</script>

<style>
  #order-statuses-list {
    list-style-type: none;
    height: 250px;
    overflow: auto;
  }
  #order-statuses-list .fa {
    cursor: pointer;
  }
</style>
<?php echo $footer; ?>