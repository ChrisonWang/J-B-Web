<table id="dg">
    <thead>
    <tr>
        <th field="name">name</th>
    </tr>
    </thead>
</table>
<div id="toolbar">
    &nbsp;&nbsp;
    <span style="vertical-align: middle">城市：</span>
    <select name="city" class="easyui-combobox" data-options="panelHeight:'auto', editable: false" id="search_city">
        <?php foreach ($service_city as $city):?>
        <option value="<?php echo $city['code'];?>"><?php echo $city['name'];?></option>
        <?php endforeach;?>
    </select>
    <span style="vertical-align: middle">项目名：</span>
    <input name="pattern" class="easyui-textbox" id="search_pattern" />
    <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-search" plain="true" onclick="searchKey()">Search</a>
</div>
<script type="text/javascript">
    var city = '<?php echo $c;?>';
    var type = '<?php echo $type;?>';
    var next_type = '<?php echo $next_type;?>';
    var parent_id = '<?php echo $parent_id;?>';

    function searchKey() {
        var c = $('#search_city').combobox('getValue');
        var pattern = $('#search_pattern').textbox('getValue');
        var param = {city: c, type: type, pattern: pattern};
        var url = '<?php echo base_url('admin/home/data')?>/'+city+'/'+type+'?act=list';
        $('#dg').datagrid({
            url: url,
            queryParams: param,
            columns: js_context.fields[c][type] == undefined ? [] : [js_context.fields[c][type]]
        });
        city = c;
    }

    $(function() {

        $('#dg').datagrid({
            url: null,
            toolbar: type == 'building' ? "#toolbar" : null,
            pagination: true,
            pageSize: 50,
            rownumbers: true,
            fitColumns: true,
            fit: true,
            singleSelect: true,
            border: false,
            onDblClickRow: function(index, row) {
                if(js_context.data_types[type] == undefined || next_type == '')
                    return true;

                open_tabs(next_type, '<?php echo base_url('admin/home/data');?>/'+city+'/'+next_type+'/'+row[js_context.data_types[type]]);
            }
        });

        if(type != 'building' && parent_id != '') {
            $('#dg').datagrid({
                columns: js_context.fields[city][type] == undefined ? [] : [js_context.fields[city][type]],
                url: '<?php echo base_url('admin/home/data')?>/'+city+'/'+type+'?act=list',
                queryParams: {city: city, type: type, pattern: parent_id}
            });
        }

    });
</script>
