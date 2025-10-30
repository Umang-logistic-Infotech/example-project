<link rel="stylesheet" href="//cdn.datatables.net/2.3.4/css/dataTables.dataTables.min.css">

<body text="gray">
    <div class="container mx-auto" style="display:block; margin:auto;max-height:100px">
        <select id="gender">
            <option value="All">All</option>
            <option value="Male">Male</option>
            <option value="Female">Female</option>
        </select>
        <table id="userTable">
            <thead>
                <th> Id </th>
                <th> Name </th>
                <th> Email </th>
                <th> Date Of Birth </th>
                <th> Age </th>
                <th> C.Age </th>
                <th> Percentage </th>
                <th> Result </th>
                <th> Gender </th>
                <th> User Type </th>
                <th> Actions </th>
            </thead>
        </table>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="//cdn.datatables.net/2.3.4/js/dataTables.min.js"></script>

    <script>
        $(document).ready(function() {
            $(document).ready(function() {
                var table = $('#userTable').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: "{{ route('getUsers1') }}",
                        data: function(d) {
                            d.gender = $('#gender').val();
                        }
                    },
                    columns: [{
                            data: 'id'
                        },
                        {
                            data: 'name'
                        },
                        {
                            data: 'email'
                        },
                        {
                            data: 'date_of_birth'
                        },
                        {
                            data: 'age'
                        },
                        {
                            data: 'age',
                            render: function(data, type, row) {
                                const dob = new Date(row.date_of_birth);
                                const today = new Date();
                                let age = today.getFullYear() - dob.getFullYear();
                                const month = today.getMonth() - dob.getMonth();
                                if (month < 0 || (month === 0 && today.getDate() < dob
                                        .getDate())) {
                                    age -= 1;
                                }
                                return age;
                            },
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'percentage'
                        },
                        {
                            data: 'percentage',
                            render: function(data) {
                                if (data <= 35) {
                                    return '<div class="text-danger">Fail</div>';
                                } else {
                                    return '<div class="text-success">Pass</div>';
                                }
                            },
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'gender'
                        },
                        {
                            data: 'userType'
                        },
                        {
                            data: null,
                            render: function(data, type, row) {
                                return '<button class="btn btn-primary btn-sm edit" data-id="' +
                                    row.id + '">Edit</button>' +
                                    '<button class="btn btn-danger btn-sm delete" data-id="' +
                                    row.id + '">Delete</button>';
                            },
                            orderable: false,
                            searchable: false
                        }
                    ],
                    createdRow: function(row, data, dataIndex) {
                        if (dataIndex % 2 === 0) {
                            $(row).addClass('text-success');
                        } else {
                            $(row).addClass('text-info-emphasis');
                        }
                    },
                });


                $('#gender').change(function() {
                    table.ajax.reload();
                });
            });


            $(document).on('click', '.delete', function() {
                var id = $(this).data('id');
                if (confirm('Are you sure you want to delete this item?')) {
                    console.log('Deleted id : ', id);

                    $.ajax({
                        url: 'delete/' + id,
                        method: 'DELETE',
                        data: {
                            _token: $('meta[name="csrf-token"]').attr('content'),
                        },
                        success: function(response) {
                            alert(response.message);
                            $('#datatable').DataTable().ajax.reload();
                        },
                        error: function() {
                            alert('Error deleting the row!');
                        }
                    });
                }
            });
        });
    </script>
</body>
