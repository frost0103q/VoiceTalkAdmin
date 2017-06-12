@extends('layouts.main')

@section('scripts')
@parent

@stop

@section('content')

<div class="main-content">
        <div class="breadcrumbs" id="breadcrumbs">
            <script type="text/javascript">
                try {
                    ace.settings.check('breadcrumbs', 'fixed')
                } catch (e) {
                }
            </script>

            <ul class="breadcrumb">
                <li>
                    <i class="icon-home home-icon"></i>
                    <a href="#">Home</a>
                </li>
                <li class="active">Comment List</li>
            </ul><!-- .breadcrumb -->

        </div>

        <div class="page-content">
            <div class="page-header">
                <h1>
                    Comment List
                </h1>
            </div><!-- /.page-header -->

            <div class="row">
                <div class="col-xs-12">
                    <!-- PAGE CONTENT BEGINS -->

                    <table id="grid-table"></table>

                    <div id="grid-pager"></div>

                    <script type="text/javascript">
                        var $path_base = "/";//this will be used in gritter alerts containing images
                    </script>

                    <!-- /row -->

                    <!-- PAGE CONTENT ENDS -->
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.page-content -->
    </div><!-- /.main-content -->


    <script type="text/javascript">
    
        $(function () {
            $(document).ready(function () {
                $("div#sidebar.sidebar ul.nav.nav-list li").each(function () {
                    $(this).removeClass("active");
                });

                $("div#sidebar.sidebar ul.nav.nav-list li:eq(2)").addClass("active");
            });
       
	
	        var grid_selector = "#grid-table";
	        var pager_selector = "#grid-pager";
	
	        jQuery(function ($) {
	            var grid_selector = "#grid-table";
	            var pager_selector = "#grid-pager";
	
	            jQuery(grid_selector).jqGrid({
	                datatype: "local",
	                url: "/api/getRantCommentList?rows="+<?php echo $params['rows'];?>+"&page="+<?php echo $params['page'];?>,
	                datatype: "json",
	                height: 350,
	                colNames: ['Id', 'Content', 'Rant', 'User', 'Status'],
	                colModel: [
	                    {name: 'f_id', index: 'id', hidden: true, width: 0, sortable: false, editable: false, invisible: true},
	                    {name: 'f_content', index: 'content', width: 150, editable: true, editoptions:{size:"20",maxlength:"30"}},
	                    {name: 'f_rant_id', index: 'rant', width: 100, editable: true, editoptions:{size:"20",maxlength:"30"}},
	                    {name: 'f_user_id', index: 'user', hidden: false, editable: true, invisible: true, editoptions:{size:"20",maxlength:"30"}},
	                    {name: 'f_status', index: 'status', width: 90, editable: false}
	                ],
	                viewrecords: true,
	                rowNum: 10,
	                rowList: [10, 20, 30],
	                pager: pager_selector,
	                altRows: true,
	                multiselect: true,
	                multiboxonly: true,
	
	                loadComplete: function () {
	                    var table = this;
	                    setTimeout(function () {
	                        styleCheckbox(table);
	                        updateActionIcons(table);
	                        updatePagerIcons(table);
	                        enableTooltips(table);
	                    }, 0);
	                },
	
	                afterSubmit: function (result) {
	                    if ($.trim(result) == "success")
	                        location.reload();
	                    else {
	                        alert("Delete Fail");
	                        location.reload();
	                    }
	                },
	
	
	                editurl: "/api/rantcomment", //nothing is saved
	                caption: "Comment List",
	                autowidth: true
	
	            });
	
	            //navButtons
	            jQuery(grid_selector).jqGrid('navGrid', pager_selector,
	                {
		            	edit: false,
						editicon : 'icon-pencil blue',
						add: false,
						addicon : 'icon-plus-sign purple',
						del: true,
						delicon : 'icon-trash red',
						search: false,
						searchicon : 'icon-search orange',
						refresh: false,
						refreshicon : 'icon-refresh green',
						view: false,
						viewicon : 'icon-zoom-in grey'
	                },
	                {closeOnEscape: true}, /* allow the view dialog to be closed when user press ESC key*/
					{
						//edit record form
						//closeAfterEdit: true,
						recreateForm: true,
						beforeShowForm : function(e) {
							var form = $(e[0]);
							form.closest('.ui-jqdialog').find('.ui-jqdialog-titlebar').wrapInner('<div class="widget-header" />')
							style_edit_form(form);
						}
					},
					{
						//new record form
						closeAfterAdd: true,
						recreateForm: true,
						viewPagerButtons: false,
						beforeShowForm : function(e) {
							var form = $(e[0]);
							form.closest('.ui-jqdialog').find('.ui-jqdialog-titlebar').wrapInner('<div class="widget-header" />')
							style_edit_form(form);
						}
					},
					{
						//delete record form
						recreateForm: true,
						beforeShowForm : function(e) {
							var form = $(e[0]);
							if(form.data('styled')) return false;
							
							form.closest('.ui-jqdialog').find('.ui-jqdialog-titlebar').wrapInner('<div class="widget-header" />')
							style_delete_form(form);
							
							form.data('styled', true);
						},
						onClick : function(e) {
							alert(1);
						}
					}
	            );

	            function style_edit_form(form) {
					//enable datepicker on "sdate" field and switches for "stock" field
					//form.find('input[name=sdate]').datepicker({format:'yyyy-mm-dd' , autoclose:true})
					//	.end().find('input[name=stock]')
					//		  .addClass('ace ace-switch ace-switch-5').wrap('<label class="inline" />').after('<span class="lbl"></span>');

			
					//update buttons classes
					var buttons = form.next().find('.EditButton .fm-button');
					buttons.addClass('btn btn-sm').find('[class*="-icon"]').remove();//ui-icon, s-icon
					buttons.eq(0).addClass('btn-primary').prepend('<i class="icon-ok"></i>');
					buttons.eq(1).prepend('<i class="icon-remove"></i>')
					
					buttons = form.next().find('.navButton a');
					buttons.find('.ui-icon').remove();
					buttons.eq(0).append('<i class="icon-chevron-left"></i>');
					buttons.eq(1).append('<i class="icon-chevron-right"></i>');		
				}
			
				function style_delete_form(form) {
					var buttons = form.next().find('.EditButton .fm-button');
					buttons.addClass('btn btn-sm').find('[class*="-icon"]').remove();//ui-icon, s-icon
					buttons.eq(0).addClass('btn-danger').prepend('<i class="icon-trash"></i>');
					buttons.eq(1).prepend('<i class="icon-remove"></i>')
				}
				
	
	            //it causes some flicker when reloading or navigating grid
	            //it may be possible to have some custom formatter to do this as the grid is being created to prevent this
	            //or go back to default browser checkbox styles for the grid
	            function styleCheckbox(table) {
	
	                $(table).find('input:checkbox').addClass('ace')
	                    .wrap('<label />')
	                    .after('<span class="lbl align-top" />')
	
	
	                $('.ui-jqgrid-labels th[id*="_cb"]:first-child')
	                    .find('input.cbox[type=checkbox]').addClass('ace')
	                    .wrap('<label />').after('<span class="lbl align-top" />');
	
	            }

	            function style_search_filters(form) {
					form.find('.delete-rule').val('X');
					form.find('.add-rule').addClass('btn btn-xs btn-primary');
					form.find('.add-group').addClass('btn btn-xs btn-success');
					form.find('.delete-group').addClass('btn btn-xs btn-danger');
				}
				function style_search_form(form) {
					var dialog = form.closest('.ui-jqdialog');
					var buttons = dialog.find('.EditTable')
					buttons.find('.EditButton a[id*="_reset"]').addClass('btn btn-sm btn-info').find('.ui-icon').attr('class', 'icon-retweet');
					buttons.find('.EditButton a[id*="_query"]').addClass('btn btn-sm btn-inverse').find('.ui-icon').attr('class', 'icon-comment-alt');
					buttons.find('.EditButton a[id*="_search"]').addClass('btn btn-sm btn-purple').find('.ui-icon').attr('class', 'icon-search');
				}
				
				function beforeDeleteCallback(e) {
					var form = $(e[0]);
					if(form.data('styled')) return false;
					
					form.closest('.ui-jqdialog').find('.ui-jqdialog-titlebar').wrapInner('<div class="widget-header" />')
					style_delete_form(form);
					
					form.data('styled', true);
				}
				
				function beforeEditCallback(e) {
					var form = $(e[0]);
					form.closest('.ui-jqdialog').find('.ui-jqdialog-titlebar').wrapInner('<div class="widget-header" />')
					style_edit_form(form);
				}
	
	            //unlike navButtons icons, action icons in rows seem to be hard-coded
	            //you can change them like this in here if you want
	            function updateActionIcons(table) {
	
	                var replacement =
	                {
	                    'ui-icon-pencil': 'icon-pencil blue',
	                    'ui-icon-trash': 'icon-trash red',
	                    'ui-icon-disk': 'icon-ok green',
	                    'ui-icon-cancel': 'icon-remove red'
	                };
	                $(table).find('.ui-pg-div span.ui-icon').each(function () {
	                    var icon = $(this);
	                    var $class = $.trim(icon.attr('class').replace('ui-icon', ''));
	                    if ($class in replacement) icon.attr('class', 'ui-icon ' + replacement[$class]);
	                })
	            }
	
	            //replace icons with FontAwesome icons like above
	            function updatePagerIcons(table) {
	                var replacement =
	                {
	                    'ui-icon-seek-first': 'icon-double-angle-left bigger-140',
	                    'ui-icon-seek-prev': 'icon-angle-left bigger-140',
	                    'ui-icon-seek-next': 'icon-angle-right bigger-140',
	                    'ui-icon-seek-end': 'icon-double-angle-right bigger-140'
	                };
	                $('.ui-pg-table:not(.navtable) > tbody > tr > .ui-pg-button > .ui-icon').each(function () {
	                    var icon = $(this);
	                    var $class = $.trim(icon.attr('class').replace('ui-icon', ''));
	
	                    if ($class in replacement) icon.attr('class', 'ui-icon ' + replacement[$class]);
	                })
	            }
	
	            function enableTooltips(table) {
	                $('.navtable .ui-pg-button').tooltip({container: 'body'});
	                $(table).find('.ui-pg-div').tooltip({container: 'body'});
	            }
	
	
	        });
        });
    </script>

@stop