<div class="modal fade" id="agc_modal_screensinvalid" data-show='true' data-keyboard='false'>
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        
        <h4 class="modal-title" id="agc_modal_title"><?php echo $this->document->getH1String(); ?></h4>
      </div>
      <div class="modal-body" id="agc_modal_body">
        <p>You cannot continue past this point as some of the previous questions have not been answered. Please go back and answer the required questions.</p>
      </div>
      <div class="modal-footer">
        
        <button type="button" class="btn btn-primary" id="agc_modal_changeflowconfirm_button" onclick="$(location).attr('href', '<?php echo site_url('/flowcontroller/screen/'.$strScreenName); ?>');"><span class="glyphicon glyphicon-refresh"></span> Review</button>
        
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<script type='text/javascript'>
    $('#agc_modal_screensinvalid').modal();
</script>