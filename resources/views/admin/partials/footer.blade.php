<!-- jQuery -->
<script src="/admin-assets/js/jquery.min.js"></script>

<!-- Bootstrap Core JavaScript -->
<script src="/admin-assets/js/bootstrap.min.js"></script>

<!-- Metis Menu Plugin JavaScript -->
<script src="/admin-assets/js/metisMenu.min.js"></script>

<!-- DataTables JavaScript -->
<script src="/admin-assets/js/jquery.dataTables.js"></script>
<script src="/admin-assets/js/dataTables.bootstrap.min.js"></script>
<!-- <script src="/sb-admin/bower_components/datatables/media/js/jquery.dataTables.min.js"></script>-->
<!-- <script src="/sb-admin/bower_components/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.min.js"></script>-->

<!-- Custom Theme JavaScript -->
<script src="/admin-assets/js/sb-admin-2.js"></script>

<!-- icheck JavaScript -->
<script src="/plugin/icheck/icheck.min.js"></script>

<!-- Page-Level Demo Scripts - Tables - Use for reference -->
<style>
td.highlight {
    background-color: whitesmoke !important;
}
.table-bordered thead tr td {
	border-bottom-width: 0px;
	// text-align: center;
}
div.dataTables_length {
	padding-left: 15px;
	padding-top: 4px;
	color: #777;
}
div.dataTables_info {
	padding-top: 8px;
	color: #777;
}
.JColResizer {
	 // display:none;
	 // margin:0,4px,0,4px !important;
}
</style>
<script>
	$('input').iCheck({
		checkboxClass: 'icheckbox_square-blue',
		radioClass: 'iradio_square-red',
		increaseArea: '20%' // optional
	});
    $(document).ready(function() {
        var table = $('#dataTables-example').DataTable({
			//排序列
			"columnDefs": [{
				"orderable": false,//禁用排序
				"targets": [0, 6]//指定的列
			}],
			"order": [5, 'desc'],
			"dom":
				"<'row'<'col-sm-6'><'col-sm-6'>r>"+
				"t"+
				"<'row'<'pull-left'l><'pull-left'i><'col-sm-6 pull-right'p>>",
			"pagingType": "full_numbers",
			"lengthMenu": [[8, 25, 50, 100], [8, 25, 50, 100]],
			"language": {
				"processing" : "<img src='/images/loading.gif'>",
				"lengthMenu": "每页 _MENU_ 条 ",
				"zeroRecords": "没有找到记录",
				"info": "，共 _PAGES_ 页，共 _TOTAL_ 条",
				"infoEmpty": "无记录",
				"infoFiltered": "(从 _MAX_ 条记录过滤)",
				"search":"搜索：",
				"loadingRecords": "载入中...",
				"paginate":{
					"first":"首页",
					"previous":"上一页",
					"next":"下一页",
					"last":"尾页"
				}
			},
			"processing": true,
			"serverSide": true,

			//保存状态
			'stateSave': true,
            "responsive": true,
			"ajax": {
				"url": "/admin/user/lists",
				"type": 'POST',
				// "data":{"name":123},
				"dataType": 'jsonp',
				"headers": {
					'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
				},
			},
			"columns": [
				{
					"data": "id",
					"fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {
						$(nTd).html("<input type='checkbox' id='" + sData + "' class='checkbox' name='ids' value='" + sData + "'>");
					}
				},
				{"data": "username"},
				{"data": "email"},
				{"data": "phone"},
				{"data": "created_at"},
				{"data": "updated_at"},
				{
					"data": "id",
					"fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {
						$(nTd).html("<a href=/admin/user/" + sData + "/edit>编辑</a>" + " " +
							"<a href='javascript:void(0);' onclick='return check_delete(" + sData + ");'>删除</a>");
					}
				},
			],
			//数据显示后回调
			"initComplete": initComplete
		});

	function initComplete() {
		$('input').iCheck({
			checkboxClass: 'icheckbox_square-blue',
			radioClass: 'iradio_square-red',
			increaseArea: '20%' // optional
		});
		//行列高亮
		var lastIdx = null;
		$('#dataTables-example tbody')
		.on( 'mouseover', 'td', function () {
			var colIdx = table.cell(this).index().column;
			if ( colIdx !== lastIdx ) {
				$( table.cells().nodes() ).removeClass( 'highlight' );
				$( table.column( colIdx ).nodes() ).addClass( 'highlight' );
			}
		} )
		.on( 'mouseleave', function () {
			$( table.cells().nodes() ).removeClass( 'highlight' );
		})
		table.on( 'draw.dt', function () {
			$('input').iCheck({
				checkboxClass: 'icheckbox_square-blue',
				radioClass: 'iradio_square-red',
				increaseArea: '20%' // optional
			});
			$('#checkAll').on('ifChecked', function(event){
				$('input').iCheck('check');
			});
			$('#checkAll').on('ifUnchecked', function(event){
				$('input').iCheck('uncheck');
			});
			$('#checkAll').iCheck('uncheck');
		} );
		//全选全不选
		$('#checkAll').on('ifChecked', function(event){
			$('input').iCheck('check');
		});
		$('#checkAll').on('ifUnchecked', function(event){
			$('input').iCheck('uncheck');
		});
		$("#search").on( 'keyup', function () {
            table.search( this.value )
			.draw();
		});
	}
});
function check_delete(id) {
	var ids =[];
	if(!id) {
        $('input[name="ids"]:checked').each(function(){ ids.push($(this).val()); });
	} else {
		ids.push(id);
	}
	$('#selected_ids').attr('value', ids);
	if(ids == '') {
		$('#no_selected_modal').modal('show');
	} else {
		$('#confirm_delete_modal').modal('show');
	}
}
function submit_delete() {
	var ids = $('#selected_ids').val();
	$('#confirm_delete_modal').modal('hide');
	$.ajax({
		url: '/admin/user/delete',
		type: 'POST',
		data: {'ids': ids},
		dataType: 'jsonp',
		headers: {
			'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
		},
		success: function(data) {
			var table = $('#dataTables-example').DataTable();
			table.draw(false);//保持分页
		},
		error: function() {
			$('#delete_failed').modal('show');
		},
	});
		showTips('操作成功', 10, 1.5);

}
function showTips( tips, height, time ){
	var windowWidth = document.documentElement.clientWidth;
	var width = 200;
	var tipsDiv = '<div class="tipsClass">' + tips + '</div>div>';

	$( 'body' ).append( tipsDiv );
	$( 'div.tipsClass' ).css({
		'z-index':9999,
		'top' : height + 'px',
		'width': width + 'px',
		'height': '30px',
		'left' : ( windowWidth / 2 ) - ( width / 2 ) + 'px',
		'position' : 'fixed',
		'padding' : '3px 5px',
		'background': '#8FBC8F',
		'font-size' : 14 + 'px',
		'margin' : '0 auto',
		'text-align': 'center',
		'color' : '#fff',
		'opacity' : '0.8'
	}).show();
	setTimeout( function(){$( 'div.tipsClass' ).fadeOut();}, ( time * 1000 ) );
}
</script>

