<link rel="stylesheet" href="//cdn.datatables.net/2.3.4/css/dataTables.dataTables.min.css">

<body text="gray">
    <div class="container mx-auto" style="display:block; margin:auto;max-height:100px">
        <table id="userTable">
            <thead>
                <th> Id </th>
                <th> Name </th>
                <th> Email </th>
                <th> Date Of Birth </th>
                <th> Age </th>
                <th> Percentage </th>
                <th> Gender </th>
                <th> User Type </th>
            </thead>
        </table>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="//cdn.datatables.net/2.3.4/js/dataTables.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#userTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('getUsers1') }}",
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
                        data: 'percentage'
                    },
                    {
                        data: 'gender'
                    },
                    {
                        data: 'userType'
                    },
                ]
            });
        });
    </script>
</body>
