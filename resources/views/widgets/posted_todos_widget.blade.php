<table id="posted_todos_table" class="table dataTable no-footer" role="grid" style="width: 100%">
    <thead>
    <tr role="row">
        <th>Dated</th>
        <th>Project</th>
        <th>Description</th>
        <th>Time Start</th>
        <th>Time End</th>
        <th>Total</th>
    </tr>
    </thead>
</table>

@push('scripts')
    <script>
        function postedTodosDataTable() {
            if (!$.fn.dataTable.isDataTable('#posted_todos_table')) {
                $('#posted_todos_table').DataTable({
                    order: [[0, 'desc']],
                    dom: 'Bfrtipr',
                    ordering: true,
                    pageLength: 25,
                    autoWidth: true,
                    responsive: true,
                    bLengthChange: false,
                    processing: true,
                    serverSide: true,
                    ajax: '{!! route('datatable_posted_todos') !!}',
                    columns: [
                        {data: 'dated', title: 'Dated'},
                        {data: 'project', title: 'Project'},
                        {data: 'description', title: 'Description'},
                        {data: 'time_start', title: 'Time Start'},
                        {data: 'time_end', title: 'Time End'},
                        {data: 'total', title: 'Total'}
                    ]
                });
            }
        }

        postedTodosDataTable();
    </script>
@endpush