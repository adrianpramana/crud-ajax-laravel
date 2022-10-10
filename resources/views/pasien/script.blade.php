<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-u1OknCvxWvY5kfmNBILK2hRnQC3Pr17a+RTT6rIHI7NnikvbZlHgTPOOmMi466C8" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.1.js" integrity="sha256-3zlB5s2uwoUzrXK3BT7AX3FyvojsraNFxCc2vC/7pNI=" crossorigin="anonymous"></script>
    <script src="//cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready( function () {
        $('#myTable').DataTable({
            processing:true,
            serverside:true,
            ajax:"{{ url('pasienAjax') }}",
            columns:[{
                data:'DT_RowIndex',
                name:'DT_RowIndex',
                orderable:false,
                searchable:false
            }, {
                data:'nama',
                name:'Nama'
            },{
                data:'email',
                name:'Email'
            },{
                data:'aksi',
                name:'Aksi'
            }]
        });
        });

        // Global Setup
        $.ajaxSetup({
            headers:{
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // 02. Process Save
        $('body').on('click', '.add-button', function(e) {
            e.preventDefault();
            $('#exampleModal').modal('show');
            $('.save-button').click(function(){
                save();
            });
        });

        // 04. Process EDIT
        $('body').on('click', '.edit-button', function(e) {
            var id = $(this).data('id');
            $.ajax({
                url: 'pasienAjax/' + id + '/edit',
                type: 'GET',
                success: function(response) {
                    $('#exampleModal').modal('show');
                    $('#nama').val(response.result.nama);
                    $('#email').val(response.result.email);
                    console.log(response.result);
                    $('.save-button').click(function(){
                    save(id);
            });
                }
            });
        });

        // 05. Process Delete
        $('body').on('click', '.delete-button', function(e) {
            if (confirm('Yakin mau hapus data ini?') == true) {
                var id = $(this).data('id');
                $.ajax({
                    url: 'pasienAjax/' +id,
                    type: 'DELETE',
                });
                $('#myTable').DataTable().ajax.reload();
            }
        });

        // Fungsi Save & Update
        function save(id = ''){
            if(id == ''){
                var var_url = 'pasienAjax';
                var var_type = 'POST';
            } else {
                var var_url = 'pasienAjax/' + id;
                var var_type = 'PUT';
            }
            $.ajax({
                    url: var_url,
                    type: var_type,
                    data:{
                       nama: $('#nama').val(),
                        email: $('#email').val()
                    },
                    success: function(response) {
                        if(response.errors){
                            console.log(response.errors);
                            $('.alert-danger').removeClass('d-none');
                            $('.alert-danger').html("<ul>");
                            $.each(response.errors, function(key,value){
                                $('.alert-danger').find('ul').append("<li>" + value + "</li>");
                            });
                            $('.alert-danger').append("</ul>");
                        } else {
                            $('.alert-success').removeClass('d-none');
                            $('.alert-success').html(response.success);
                        }
                        $('#myTable').DataTable().ajax.reload();
                    }
                });
        }

    </script>