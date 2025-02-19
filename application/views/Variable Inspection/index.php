<div id="rw_variable_test_toolbar" style="padding-bottom: 2px;">   
    <form id="rw_variable_test_search_form" onsubmit="return false" style="margin: 0">
        Search : 
        <input type="text" size="20" class="easyui-validatebox" name="q" onkeypress="if (event.keyCode === 13) {
                    rw_variable_test_search();

                }"/>
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-search" plain="true" onclick="rw_variable_test_search()">Find</a>
        <?php
        if (in_array("Add", $action)) {
            ?>
            <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="rw_variable_test_add()"> Add</a>
            <?php
        }if (in_array("Edit", $action)) {
            ?>
            <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="rw_variable_test_edit()"> Edit</a>
            <?php
        }if (in_array("Delete", $action)) {
            ?>
            <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="rw_variable_test_delete()"> Delete</a>
            <?php
        }
        ?>
    </form>
</div>
<table id="rw_variable_test" data-options="
       url:'<?php echo site_url('rw_variable_test/get') ?>',
       method:'post',
       border:true,
       singleSelect:true,
       fit:true,
       title:'Variable Inspection',
       pageSize:30,
       pageList: [30, 50, 70, 90, 110],
       rownumbers:true,
       fitColumns:false,
       remoteSort:true,
       multiSort:true,
       pagination:true,
       toolbar:'#rw_variable_test_toolbar'">
    <thead>
        <tr>
            <th field="id" hidden="true"></th>
            <th field="view_position" width="200" halign="center" sortable="true">Position</th>            
            <th field="description" halign="center" sortable="true">Description</th>        
            <th field="mandatory" width="100" halign="center" sortable="true">Mandatory</th>
        </tr>
    </thead>
</table>
<script rw_variable_test="text/javascript">
    $(function () {
        $('#rw_variable_test').datagrid({
            rowStyler: function(index, row) {
                if (row.mandatory === 'f') {
                    return 'background-color:#ffff00;';
                }
            },
            onSelect: function(index, row) {
                //---
            }
        });
    });
</script>
<script type="text/javascript" src="<?php echo base_url() ?>js/rw_variable_test.js"></script>

