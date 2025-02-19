/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */


/* global base_url */

var rw_variable_test_url = '';

function rw_variable_test_search() {
    $('#rw_variable_test').datagrid('reload', $('#rw_variable_test_search_form').serializeObject());
}

function rw_variable_test_input_form(type, title, row) {
    if ($('#rw_variable_test_dialog')) {
        $('#bodydata').append("<div id='rw_variable_test_dialog'></div>");
    }

    $('#rw_variable_test_dialog').dialog({
        title: title,
        width: 400,
        height: 'auto',
        href: base_url + 'rw_variable_test/input',
        modal: true,
        resizable: true,
        top: 60,
        buttons: [{
                text: 'Save',
                iconCls: 'icon-save',
                handler: function () {
                    rw_variable_test_save();
                }
            }, {
                text: 'Close',
                iconCls: 'icon-remove',
                handler: function () {
                    $('#rw_variable_test_dialog').dialog('close');
                }
            }],
        onLoad: function () {
            $(this).dialog('center');
            if (type === 'edit') {
                $('#rw_variable_test_input_form').form('load', row);
            } else {
                $('#rw_variable_test_input_form').form('clear');
            }
        }
    });
}

function rw_variable_test_add() {
    rw_variable_test_input_form('add', 'ADD Variable Inspection', null);
    rw_variable_test_url = base_url + 'rw_variable_test/save';
}

function rw_variable_test_edit() {
    var row = $('#rw_variable_test').datagrid('getSelected');
    if (row !== null) {
        rw_variable_test_input_form('edit', 'EDIT Variable Inspection', row);
        rw_variable_test_url = base_url + 'rw_variable_test/update/' + row.id;
    } else {
        $.messager.alert('No Currency Selected', 'Please Select Currency', 'warning');
    }
}

function rw_variable_test_save() {
    $('#rw_variable_test_input_form').form('submit', {
        url: rw_variable_test_url,
        onSubmit: function () {
            return $(this).form('validate');
        },
        success: function (content) {
            var result = eval('(' + content + ')');
            if (result.success) {
                $('#rw_variable_test').datagrid('reload');
                $('#rw_variable_test_dialog').dialog('close');
            } else {
                $.messager.alert('Error', result.msg, 'error');
            }
        }
    });
}

function rw_variable_test_delete() {
    var row = $('#rw_variable_test').datagrid('getSelected');
    if (row !== null) {
        $.messager.confirm('Confirm', 'Are you sure to remove this data?', function (r) {
            if (r) {
                $.post(base_url + 'rw_variable_test/delete', {
                    id: row.id
                }, function (result) {
                    if (result.success) {
                        $('#rw_variable_test').datagrid('reload');
                    } else {
                        $.messager.alert('Error', result.msg, 'error');
                    }
                }, 'json');
            }
        });
    } else {
        $.messager.alert('No Currency Selected', 'Please Select Currency', 'warning');
    }
}