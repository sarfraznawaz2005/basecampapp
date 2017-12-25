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

    // post selected todos code...
    $('#checkAll').click(function () {
        var checked = this.checked;

        $('.dataTable .chk_post').each(function () {
            this.checked = checked;
        });
    });

    $('#btnPost').click(function () {
        var $this = $(this);
        var btnText = $(this).html();
        var checkedCheckboxCount = $('.dataTable .chk_post:checked').length;
        var data = $('#pendingTodosForm').serialize();

        if (!checkedCheckboxCount) {
            showAlert('Nothing Selected!', 'warning');
            return false;
        }

        $this.attr('disabled', true);
        $this.text('Working, please wait...');

        // send
        $.post('/post_todos', {data: data}, function (response) {
            $this.attr('disabled', false);
            $this.html(btnText);

            if (response === 'ok') {
                window.location.reload()
            }
            else {
                showAlert('Unable to post :(', 'error');
            }
        });

    });

</script>