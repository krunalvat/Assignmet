<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Assignment</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css" />
    <link href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css" rel="stylesheet">
    <link href = "https://code.jquery.com/ui/1.10.4/themes/ui-lightness/jquery-ui.css" rel = "stylesheet">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>  
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
    <script src = "https://code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
    
</head>
<body>
    <div class="btn-lg"> 
        <a class="btn btn-success btn-sm" href="javascript:void(0)" id="create">Add Customer</a>
    </div>
    <div class="content">
        <div class="card-body">
            <div class="table-responsive">
                <table  class="table table-striped table-no-bordered table-hover data-table">
                    <thead>
                    <tr>
                        <th>No</th>
                        <th scope="col">First Name</th>
                        <th scope="col">Last Name</th>
                        <th scope="col">Shop Name</th>
                        <th scope="col">City</th>
                        <th scope="col">Birth Date</th>
                        <th scope="col">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

 
    <div class="modal fade" id="ajaxModel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modelHeading"></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="error_box alert alert-danger" style="display: none;"></div>
                    <form id="Form" name="Form" class="form-horizontal" enctype="multipart/form-data">
                       <input type="hidden" name="id" id="id">
            
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Shop</label>
                            <div class="col-sm-12">
                                <select class="form-control" name="shop_id" id="shop_id" required>
                                    <option value="0">Choose Shop...</option>
                                    @forelse ($shops as $shop)
                                    <option value="{{ $shop->id }}">{{ $shop->name }}</option>
                                    @empty
                                    <option value="">No Data</option>
                                    @endforelse 
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="name" class="col-sm-2 control-label">First Name</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" id="first_name" name="first_name" placeholder="Enter First Name" value="" maxlength="50">
                            </div>
                            <div class="alert-message" id="first_name"></div>
                        </div>
         
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Last Name</label>
                            <div class="col-sm-12">
                                <input type="text" id="last_name" name="last_name" placeholder="Enter Last Name" class="form-control">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">Avatar</label>
                            <div class="col-sm-12">
                                <input class="form-control" type="file" id="images" name="images">
                            </div>
                            <div class="col-sm-12">
                                <label id="imgs" class="form-control"></label>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">City</label>
                            <div class="col-sm-12">
                                <input type="text" id="city" name="city" placeholder="Enter City" class="form-control">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">Birth Date</label>
                            <div class="col-sm-12">
                                <input type="text" id="birthdate" name="birthdate" class="form-control">
                            </div>
                        </div>
          
                        <div class="col-sm-offset-2 col-sm-10">
                            <button type="submit" class="btn btn-primary" id="saveBtn" value="create">Save changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    
    
</body>
<script type="text/javascript">
    $(function () 
    {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

      var table = $('.data-table').DataTable({
          processing: true,
          serverSide: true,
          ajax: "{{ route('customers.index') }}",
          columns: [
              {data: 'id', name: 'id'},
              {data: 'first_name', name: 'first_name'},
              {data: 'last_name', name: 'last_name'},
              {data: 'shop_name', name: 'shop_name'},
              {data: 'city', name: 'city'},
              {data: 'birthdate', name: 'birthdate'},
              {data: 'action', name: 'action', orderable: false, searchable: false},
          ]
      });

      $('#create').click(function () {
        $('#saveBtn').val("create");
        $('#id').val('');
        $('#Form').trigger("reset");
        $('#modelHeading').html("Create Customer");
        $('#ajaxModel').modal('show');
      });


      $('body').on('click', '.edit', function () {
        var id = $(this).data('id');
        $('.error_box').hide();
        $.get("{{ route('customers.index') }}" +'/' + id +'/edit', function (data) {
            $('#modelHeading').html("Edit Customer");
            $('#saveBtn').val("edit-user");
            $('#ajaxModel').modal('show');
            $('#id').val(data.id);
            $('#shop_id').val(data.shop_id);
            $('#first_name').val(data.first_name);
            $('#last_name').val(data.last_name);
            $('#imgs').text(data.avatar);
             $('#city').val(data.city);
            $('#birthdate').val(data.birthdate);
        })
      });
    
    $('#saveBtn').click(function (e) {
        e.preventDefault();
        $('.error_box').hide();
        formData = new FormData($('#Form')[0]);
        $(this).html('Sending..');
    
        $.ajax({
          data: formData,
          url: "{{ route('customers.store') }}",
          type: "POST",
          dataType: 'json',
          processData:false,
          contentType:false,
          success: function (data) {
            if(data.hasOwnProperty('errors'))
            {
                let error_obj = data.errors;
                let output_html = "";

                for (var key in error_obj) 
                {
                    if (error_obj.hasOwnProperty(key)) 
                    {
                        output_html += "<br> " + error_obj[key];
                    }
                }
                $('.error_box').html(output_html);
                $('.error_box').show();
            }
            else
            {
                $('#ajaxModel').modal('hide');
            }
          },
          error: function (data) {
              $('#saveBtn').html('Save Changes');
          }
      });
    });

      $('body').on('click', '.delete', function () {
     
        var id = $(this).data("id");
        confirm("Are You sure want to delete !");
    
        $.ajax({
            type: "DELETE",
            url: "{{ route('customers.store') }}"+'/'+id,
            success: function (data) {
                table.draw();
            },
            error: function (data) {
                console.log('Error:', data);
            }
        });

      });
    });
  </script>
  <script>
    $(function() {
       $("#birthdate").datepicker();
    });
 </script>
</html>
