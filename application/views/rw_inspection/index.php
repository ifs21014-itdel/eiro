<div class="easyui-layout" fit="true">
    <div region="center" 
         title="RW Need To Inspect" 
         href="<?php echo site_url('rw_inspection/rw_inspection_detail') ?>">
    </div>
    <div region="north" 
         title="RW Inspection List"
         style="height: 50%" 
         split="true"
         href="<?php echo site_url('rw_inspection/view') ?>">
    </div>

</div>
<script type="text/javascript" src="<?php echo base_url() ?>js/rw_inspection.js"></script>