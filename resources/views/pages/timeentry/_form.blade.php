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

        @if (isset($todo))
            @push('scripts')
                <script>
                    $('#project option[value="{{$todo->project_id}}"]').attr("selected", "selected");
                </script>
            @endpush
        @elseif (old('project_id'))
            @push('scripts')
                <script>
                    $('#project option[value="{{old('project_id')}}"]').attr("selected", "selected");
                </script>
            @endpush
        @elseif (session('project_id'))
            @push('scripts')
                <script>
                    $('#project option[value="{{session('project_id')}}"]').attr("selected", "selected");
                </script>
            @endpush
        @endif

        @if ($errors->has('project_id'))
            <span class="help-block">
                <strong>{{ $errors->first('project_id') }}</strong>
            </span>
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

        @if (isset($todo))
            @push('scripts')
                <script>
                    $('#todolist option[value="{{$todo->todolist_id}}"]').attr("selected", "selected");
                </script>
            @endpush
        @elseif (old('todolist_id'))
            @push('scripts')
                <script>
                    $('#todolist option[value="{{old('todolist_id')}}"]').attr("selected", "selected");
                </script>
            @endpush
        @elseif (session('todolist_id'))
            @push('scripts')
                <script>
                    $('#todolist option[value="{{session('todolist_id')}}"]').attr("selected", "selected");
                </script>
            @endpush
        @endif

        @if ($errors->has('todolist_id'))
            <span class="help-block">
                <strong>{{ $errors->first('todolist_id') }}</strong>
            </span>
        @endif
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

        @if (isset($todo))
            @push('scripts')
                <script>
                    $('#todo option[value="{{$todo->todo_id}}"]').attr("selected", "selected");
                </script>
            @endpush
        @elseif (old('todo_id'))
            @push('scripts')
                <script>
                    $('#todo option[value="{{old('todo_id')}}"]').attr("selected", "selected");
                </script>
            @endpush
        @elseif (session('todo_id'))
            @push('scripts')
                <script>
                    $('#todo option[value="{{session('todo_id')}}"]').attr("selected", "selected");
                </script>
            @endpush
        @endif

        @if ($errors->has('todo_id'))
            <span class="help-block">
                <strong>{{ $errors->first('todo_id') }}</strong>
            </span>
        @endif
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
               value="{{old('dated') ? old('dated') : (isset($todo) ? $todo->dated : date('Y-m-d'))}}">

        @if ($errors->has('dated'))
            <span class="help-block">
                <strong>{{ $errors->first('dated') }}</strong>
            </span>
        @endif
    </div>
</div>
<div class="pull-right">
    <div class="form-group pull-left{{ $errors->has('time_start') ? ' has-error' : '' }}">
        <label for="time_start" class="control-label">Time Start</label>

        <input class="form-control"
               required="required"
               name="time_start"
               type="time"
               value="{{old('time_start') ? old('time_start') : (isset($todo) ? $todo->time_start : date('H:i'))}}"
               id="time_start">

        @if ($errors->has('time_start'))
            <span class="help-block">
                <strong>{{ $errors->first('time_start') }}</strong>
            </span>
        @endif
    </div>

    <div class="pull-left">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>

    <div class="form-group pull-left{{ $errors->has('time_end') ? ' has-error' : '' }}">
        <label for="time_end" class="control-label">Time End</label>

        <input class="form-control"
               required="required"
               name="time_end"
               type="time"
               value="{{old('time_end') ? old('time_end') : (isset($todo) ? $todo->time_end : date('H:i'))}}"
               id="time_end">

        @if ($errors->has('time_end'))
            <span class="help-block">
                <strong>{{ $errors->first('time_end') }}</strong>
            </span>
        @endif
    </div>
    <div class="clearfix"></div>
</div>
<div class="clearfix"></div>

<div class="form-group{{ $errors->has('description') ? ' has-error' : '' }}">
    <input class="form-control"
           placeholder="Description"
           required="required"
           name="description"
           value="{{old('description') ? old('description') : (isset($todo) ? $todo->description : '')}}"
           type="text">

    @if ($errors->has('description'))
        <span class="help-block">
            <strong>{{ $errors->first('description') }}</strong>
        </span>
    @endif
</div>
