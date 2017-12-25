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