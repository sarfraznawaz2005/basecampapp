@extends('layouts.app')

@section('content')

    <a data-toggle="modal" href="#modal-entry" class="btn btn-success">
        <i class="glyphicon glyphicon-plus-sign"></i> Add New Entry
    </a>
    <hr>

    <ul class="nav nav-tabs" role="tablist">
        <li class="active"><a href="#pending" role="tab" data-toggle="tab">Pending Todos</a></li>
        <li><a href="#posted" role="tab" data-toggle="tab">Posted Todos</a></li>
    </ul>
    <div class="tab-content">
        <div class="active tab-pane fade in" id="pending">
            <br>
            @widget('App.Widgets.PendingTodosWidget')
        </div>
        <div class="tab-pane fade" id="posted">
            <br>
            @widget('App.Widgets.PostedTodosWidget')
        </div>
    </div>

    <div class="modal fade" id="modal-entry">
        <div class="modal-dialog">
            <div class="modal-content">

                <form method="POST" action="{{route('timeentry')}}">
                    {{ csrf_field() }}

                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title">Add New Entry</h4>
                    </div>

                    <div class="modal-body">

                        <div class="form-group{{ $errors->has('project_id') ? ' has-error' : '' }}">
                            <label for="project" class="control-label pull-left">Project</label>

                            <div class="pull-right" style="width:80%">
                                <select class="form-control"
                                        style="width:100%"
                                        required="required"
                                        id="project"
                                        name="project_id">
                                    <option value="">Select</option>

                                    @foreach($projects as $projectId => $name)
                                        <option value="{{$projectId}}">{{$name}}</option>
                                    @endforeach
                                </select>

                                @if (old('project_id'))
                                    @push('scripts')
                                        <script>
                                            $('#project option[value="{{old('project_id')}}"]').attr("selected", "selected");
                                        </script>
                                    @endpush
                                @endif

                            </div>
                            <div class="clearfix"></div>
                        </div>

                        <div class="form-group{{ $errors->has('todolist_id') ? ' has-error' : '' }}">
                            <label for="todolist" class="control-label pull-left">Todolist</label>

                            <div class="pull-right" style="width:80%">
                                <select class="form-control"
                                        style="width:100%"
                                        required="required"
                                        id="todolist"
                                        name="todolist_id">

                                    <option value="">Select</option>

                                    @foreach($todoLists as $todoListId => $name)
                                        <option value="{{$todoListId}}" {{old('todolist_id') && old('todolist_id') == $todoListId ? 'selected' : '' }}>{{$name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="clearfix"></div>
                        </div>

                        <div class="form-group{{ $errors->has('todo_id') ? ' has-error' : '' }}">
                            <label for="todo" class="control-label pull-left">Todo</label>

                            <div class="pull-right" style="width:80%">
                                <select class="form-control"
                                        style="width:100%"
                                        required="required"
                                        id="todo"
                                        name="todo_id">

                                    <option value="">Select</option>

                                    @foreach($todos as $todoId => $name)
                                        <option value="{{$todoId}}" {{old('todo_id') && old('todo_id') == $todoId ? 'selected' : '' }}>{{$name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="clearfix"></div>
                        </div>

                        <hr>

                        <div class="pull-left">
                            <div class="form-group pull-left{{ $errors->has('dated') ? ' has-error' : '' }}">
                                <label for="date" class="control-label">Date</label>

                                <input class="form-control"
                                       required="required"
                                       name="dated"
                                       type="date"
                                       id="date"
                                       value="{{old('dated') ? old('dated') : date('Y-m-d')}}">
                            </div>
                        </div>
                        <div class="pull-right">
                            <div class="form-group pull-left{{ $errors->has('time_start') ? ' has-error' : '' }}">
                                <label for="time_start" class="control-label">Time Start</label>

                                <input class="form-control"
                                       required="required"
                                       name="time_start"
                                       type="time"
                                       value="{{ old('time_start') }}"
                                       id="time_start">
                            </div>

                            <div class="pull-left">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>

                            <div class="form-group pull-left{{ $errors->has('time_end') ? ' has-error' : '' }}">
                                <label for="time_end" class="control-label">Time End</label>

                                <input class="form-control"
                                       required="required"
                                       name="time_end"
                                       type="time"
                                       value="{{ old('time_end') }}"
                                       id="time_end">
                            </div>
                            <div class="clearfix"></div>
                        </div>
                        <div class="clearfix"></div>

                        <div class="form-group{{ $errors->has('description') ? ' has-error' : '' }}">
                            <input class="form-control"
                                   placeholder="Description"
                                   required="required"
                                   name="description"
                                   value="{{ old('description') }}"
                                   type="text">
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" id="btnAddEntry">
                            <i class="glyphicon glyphicon-plus-sign"></i> Add Entry
                        </button>
                    </div>

                </form>

            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>

        $('#project').change(function () {
            var $this = $(this);
            var $todoListDropdown = $('#todolist');
            var $todoDropdown = $('#todo');

            if (this.value) {

                $this.attr('disabled', true);

                $todoListDropdown.empty();
                $todoDropdown.empty();
                $todoListDropdown.val(null).trigger("change");
                $todoDropdown.val(null).trigger("change");
                $todoListDropdown.append('<option value="">Select</option>');
                $todoDropdown.append('<option value="">Select</option>');
                $todoListDropdown.attr('disabled', true);
                $todoDropdown.attr('disabled', true);

                $.get('/todolists/' + this.value, function (response) {

                    $this.attr('disabled', false);

                    if (response) {
                        var data = JSON.parse(response);
                        var items = sortProperties(data);

                        for (var x = 0; x < items.length; x++) {
                            $todoListDropdown.append('<option value="' + items[x][0] + '">' + items[x][1] + '</option>');
                        }

                        $todoListDropdown.attr('disabled', false);
                    }
                });
            }
        });

        $('#todolist').change(function () {
            var $this = $(this);
            var $todoDropdown = $('#todo');

            if (this.value) {

                $this.attr('disabled', true);

                $todoDropdown.empty();
                $todoDropdown.val(null).trigger("change");
                $todoDropdown.append('<option value="">Select</option>');
                $todoDropdown.attr('disabled', true);

                $.get('/todos/' + this.value, function (response) {

                    $this.attr('disabled', false);

                    if (response) {
                        var data = JSON.parse(response);
                        var items = sortProperties(data);

                        for (var x = 0; x < items.length; x++) {
                            $todoDropdown.append('<option value="' + items[x][0] + '">' + items[x][1] + '</option>');
                        }

                        $todoDropdown.attr('disabled', false);
                    }
                });
            }
        });

        function sortProperties(obj) {
            // convert object into array
            var sortable = [];

            for (var key in obj)
                if (obj.hasOwnProperty(key))
                    sortable.push([key, obj[key]]); // each item is an array in format [key, value]

            // sort items by value
            sortable.sort(function (a, b) {
                var x = a[1].toLowerCase(),
                    y = b[1].toLowerCase();
                return x < y ? -1 : x > y ? 1 : 0;
            });

            return sortable; // array in format [ [ key1, val1 ], [ key2, val2 ], ... ]
        }
    </script>
@endpush

