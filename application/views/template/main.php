<!DOCTYPE html>
<html>
<head>
	<title>DEMO</title>
	<!-- Material Design fonts -->
	<link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Roboto:300,400,500,700">
	<link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/icon?family=Material+Icons">

	<!-- Bootstrap -->
	<link rel="stylesheet" type="text/css" href="<?=base_url("media")?>/css/bootstrap.min.css">

	<!-- Bootstrap Material Design -->
	<link rel="stylesheet" type="text/css" href="<?=base_url("media")?>/css/bootstrap-material-design.css">
	<link rel="stylesheet" type="text/css" href="<?=base_url("media")?>/css/ripples.min.css">

	<link href="<?=base_url("media")?>/css/snackbar.min.css" rel="stylesheet">

	<!-- Datatable css -->
	<link rel="stylesheet" type="text/css" href="<?=base_url("media")?>/css/dataTables.bootstrap.min.css">

	<!-- JQuery -->
	<script src="<?=base_url("media")?>/js/datatable/jquery.js" type="text/javascript"></script>

	<!-- Datatable js -->
	<script src="<?=base_url("media")?>/js/datatable/jquery.dataTables.min.js" type="text/javascript"></script>
	<script src="<?=base_url("media")?>/js/datatable/dataTables.bootstrap.min.js" type="text/javascript"></script>

	<script src="<?=base_url("media")?>/js/material.min.js" type="text/javascript"></script>
	<script src="<?=base_url("media")?>/js/ripples.min.js" type="text/javascript"></script>
	<script src="<?=base_url("media")?>/js/snackbar.min.js" type="text/javascript"></script>

	<script src="<?=base_url("media")?>/js/bootstrap/javascript/transition.js" type="text/javascript"></script>
	<script src="<?=base_url("media")?>/js/bootstrap/javascript/collapse.js" type="text/javascript"></script>
	<script src="<?=base_url("media")?>/js/bootstrap/javascript/modal.js" type="text/javascript"></script>

	<script type="text/javascript">
		var main_url = "<?=base_url()?>";
	</script>
	<script type="text/javascript">
		var oTable = null;
		$(function() {
			$.material.init();
			getData();
			$("#btn-add-person").click(function(e) {
				e.preventDefault();
				resetForm();
				$("#person-form").modal("show");
				oTable.page("last").draw(false);
			});
			$("#btnSavePerson").click(function(e) {
				e.preventDefault();
				var data = {
					person_id: (($("#inputPersonId").val() == undefined) ? null : $("#inputPersonId").val()),
					name: $("#inputName").val(),
					email: $("#inputEmail").val(),
					phone: $("#inputPhone").val(),
					address: $("#inputAddress").val()
				}
				$.post(main_url + "Home/savePerson", data, function(result) {
					if (result.status == "OK" && result.data) {
						oTable.destroy();
						resetForm();
						getData(function() {
							$("#person-form").modal('toggle');
							oTable.page("last").draw('page');
						});
					}
				}, "json");
			});
			$("#btnDeletePerson").click(function(e) {
				e.preventDefault();
				var person_id = $("#person_id_delete").val();
				$.post(main_url + "Home/deletePerson", {person_id: person_id}, function(result) {
					if (result.status) {
						oTable.destroy();
						getData(function() {
							$("#confirmation-dialog").modal('toggle');
							oTable.page("last").draw('page');
						});
					}
				}, "json");
			});
		})

		function getData(callback) {
			var url = main_url + "Home/getPerson";
			oTable = $("#person-table").DataTable({
				"retrieve": true,
				"bAutoWidth": true,
				"bProcessing": true,
				"bServerSide": false,
				"sAjaxSource": url,
				"columns": [
					{ "title": "PersonID", "data": "person_id", "visible": false },
					{ "title": "Name", "data": "name" },
					{ "title": "Email", "data": "email" },
					{ "title": "Phone", "data": "phone" },
					{ "title": "Address", "data": "address" },
					{ 
						"title": "Action",
						"className": 'iActionClass inline',
						"sortable": false,
						"data": "person_id",
						"render": function ( data, type, full, meta ) { 
							return "<a class='btn btn-raised btn-info btnEdit'  data-id="+data+"><i class='glyphicon glyphicon-pencil'></i></a> <a class='btn btn-raised btn-danger btnDelete' data-id="+data+"><i class='glyphicon glyphicon-remove'></i></a></td>"
						}
					}
				],
				"fnDrawCallback": function() {
					$(".btnEdit").click(function(e) {
						e.preventDefault();
						var person_id = $(this).attr("data-id");
						$.post(main_url + "Home/getPerson", {person_id: person_id}, function(data) {
							if (data.status == "OK") {
								if (data.data.length > 0) {
									data = data.data[0];
									setForm(data.person_id, data.name, data.email, data.phone, data.address);
									$("#person-form").modal("show");
								}
							}
						}, "json");
					});

					$(".btnDelete").click(function(e) {
						e.preventDefault();
						var person_id = $(this).attr("data-id");
						$("#person_id_delete").val(person_id);
						$("#confirmation-dialog").modal("show");
					});
				},
				"pagingType": "full_numbers"
			}).page("last").draw(false);
			if (typeof callback == 'function')
				callback();
		}

		function setForm(person_id, name, email, phone, address) {
			$("#inputPersonId").val(person_id);
			$("#inputName").val(name);
			$("#inputEmail").val(email);
			$("#inputPhone").val(phone);
			$("#inputAddress").val(address);
		}

		function resetForm() {
			$("#inputPersonId").val("");
			$("#inputName").val("");
			$("#inputEmail").val("");
			$("#inputPhone").val("");
			$("#inputAddress").val("");
		}
	</script>
</head>
<body>
	<div class="container">
		<div>
			<legend><h1>Person</h1></legend>
			<a href="javascript:void(0)" id="btn-add-person" class="btn btn-raised btn-lg">Add</a>
		</div>
		<table id="person-table" class="table table-striped table-hover ">
			<thead>
				<tr>
					<th>#</th>
					<th>Name</th>
					<th>Email</th>
					<th>Phone</th>
					<th>Address</th>
					<th>Action</th>
				</tr>
			</thead>
			<tbody>
			</tbody>
		</table>
	</div>

	<div id="person-form" class="modal">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
					<h4 class="modal-title">Form Person</h4>
				</div>
				<div class="modal-body">
					<form class="form-horizontal">
						<fieldset>
							<input type="hidden" name="inputPersonId" id="inputPersonId" />
							<div class="form-group">
								<label for="inputName" class="col-md-2 control-label">Name</label>
								<div class="col-md-10">
									<input type="text" class="form-control" id="inputName" placeholder="Name" />
								</div>
							</div>
							<div class="form-group">
								<label for="inputEmail" class="col-md-2 control-label">Email</label>
								<div class="col-md-10">
									<input type="email" class="form-control" id="inputEmail" placeholder="Email" />
								</div>
							</div>
							<div class="form-group">
								<label for="inputPhone" class="col-md-2 control-label">Phone</label>
								<div class="col-md-10">
									<input type="text" class="form-control" id="inputPhone" placeholder="Phone" />
								</div>
							</div>
							<div class="form-group">
								<label for="inputAddress" class="col-md-2 control-label">Address</label>
								<div class="col-md-10">
									<textarea class="form-control" rows="3" id="inputAddress"></textarea>
								</div>
							</div>
						</fieldset>
					</form>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					<button type="button" class="btn btn-primary" id="btnSavePerson">Save</button>
				</div>
			</div>
		</div>
	</div>

	<div id="confirmation-dialog" class="modal">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
					<h4 class="modal-title">Confirmation Delete</h4>
				</div>
				<div class="modal-body">
					<input type="hidden" name="person_id_delete" id="person_id_delete">
					Are You Sure Want Delete?
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					<button type="button" class="btn btn-primary" id="btnDeletePerson">Submit</button>
				</div>
			</div>
		</div>
	</div>
</body>
</html>