<?php
/* Smarty version 3.1.30, created on 2017-03-16 09:19:24
  from "F:\wwwroot\lc\application\modules\admin\views\poolbill.html" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_58c9e81c9a7960_72138384',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '4d4a72849a2204ad8c69766de1f4edefd054ffd9' => 
    array (
      0 => 'F:\\wwwroot\\lc\\application\\modules\\admin\\views\\poolbill.html',
      1 => 1489570868,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_58c9e81c9a7960_72138384 (Smarty_Internal_Template $_smarty_tpl) {
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>资金池流水</title>
    <link href="/admin/css/bootstrap.min.css" rel="stylesheet">
    <!-- jqgrid-->
    <link href="/admin/css/ui.jqgrid.css" rel="stylesheet">
    <link href="/admin/css/animate.min.css" rel="stylesheet">
    <link href="/admin/css/style.min.css" rel="stylesheet">
    <style>
    /*搜索框样式重写*/
    #searchmodfbox_table_list_2{
        position:absolute !important;
        top:302px !important;
        left:603px !important;
    }
    </style>
</head>
<body class="gray-bg" style="overflow:hidden">
<div class="animated fadeInRight">
    <div class="row">
        <div class="col-sm-12">
            <div class="ibox ">
                <div class="ibox-content">
                    <h4 class="m-t">资金池流水</h4>
                    <div class="jqGrid_wrapper">
                        <table id="table_list_2"></table>
                        <div id="pager_list_2"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- 全局js -->
<?php echo '<script'; ?>
 src="/admin/js/jquery-2.1.1.min.js"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
 src="/admin/js/bootstrap.min.js?v=3.4.0"><?php echo '</script'; ?>
>

<!-- jqGrid -->
<?php echo '<script'; ?>
 src="/admin/js/jqGrid/grid.locale-cn.js"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
 src="/admin/js/jqGrid/jquery.jqGrid.min.js"><?php echo '</script'; ?>
>

<!-- 自定义js -->
<?php echo '<script'; ?>
 src="/admin/js/content.min.js"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
>
    $(document).ready(function()
    {
        $.jgrid.defaults.styleUI="Bootstrap";
        $("#table_list_2").jqGrid({
            url:"/admin/home/getpoolbill",
            datatype:"json",
            height:700,
            autowidth:true,
            shrinkToFit:true,
            rowNum:19,
            colNames:["生效序列号","用户","上次余额","发生金额","流水状态","发生时间","生效时间"],
            colModel:
                    [   //默认不可编辑 可搜索 可排序
                        {name:"UpdateSN",index:"UpdateSN",sorttype:"int",key:true,width:50},
                        {name:"UserName",index:"UserName",sortable:false,width:100},
                        {name:"BeforeBalance",index:"BeforeBalance",sortable:false,width:50},
                        {name:"Amount",index:"Amount",sortable:false,width:50},
                        {name:"State",index:"State",sortable:false,formatter:'select',editoptions:{value:"0:等待确认;1:交易完成;-1:交易失败"},width:50},
                        {name:"CreateDate",index:"CreatDate",sorttype:"date",width:100},
                        {name:"UpdateDate",index:"UpdateDate",sorttype:"date",width:100}
                    ],
            pager:"#pager_list_2",
            loadonce:true,
            viewrecords:true
        });
        $("#table_list_2").jqGrid("navGrid","#pager_list_2",{edit:false,add:false,del:false,search:true},{closeAfterEdit:true,reloadAfterSubmit:false});

        $(window).bind("resize",function(){var b=$(".jqGrid_wrapper").width();$("#table_list_2").setGridWidth(b)})
    });
<?php echo '</script'; ?>
>
</body>
</html><?php }
}
