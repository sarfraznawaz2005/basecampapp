<script>

    // sum table column
    $dataTable = $('#dataTableBuilder').dataTable();
    $dataTable.on('draw.dt', function () {
        sumColumn('#dataTableBuilder', 7)
    });

    $('.project_id').change(function () {
        var $this = $(this);
        var $todoListDropdown = $this.closest('form').find('.todolist_id');
        var $todoDropdown = $this.closest('form').find('.todo_id');

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

    $('.todolist_id').change(function () {
        var $this = $(this);
        var $todoDropdown = $this.closest('form').find('.todo_id');

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
            $(this).closest('tr').toggleClass('warning');
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
        
		swal({
			title: "Are you sure?",
			text: "Todos will be posted!",
			type: "warning",
			showCancelButton: true,
			confirmButtonText: "Yes, post!",
			closeOnConfirm: false
		}, function () {
			swal.disableButtons();

			$this.attr('disabled', true);
			$this.text('Working, please wait...');

			// send
			$.post('/post_todos', {data: data}, function (response) {
				if (response === 'ok') {
					window.location.reload()
				}
				else {
					showAlert('Unable to post :(', 'error');
				}

				$this.attr('disabled', false);
				$this.html(btnText);
			});
		});
		
    });
	
	
	$('#btnDelete').click(function () {
        var $this = $(this);
        var btnText = $(this).html();
        var checkedCheckboxCount = $('.dataTable .chk_post:checked').length;
        var data = $('#pendingTodosForm').serialize();

        if (!checkedCheckboxCount) {
            showAlert('Nothing Selected!', 'warning');
            return false;
        }


		swal({
			title: "Are you sure?",
			text: "Todos will be deleted!",
			type: "warning",
			showCancelButton: true,
			confirmButtonColor: "#DD6B55",
			confirmButtonText: "Yes, delete!",
			closeOnConfirm: false
		}, function () {
			swal.disableButtons();

			$this.attr('disabled', true);
			$this.text('Working, please wait...');

			// send
			$.post('/delete_todos', {data: data}, function (response) {
				if (response === 'ok') {
					window.location.reload()
				}
				else {
					showAlert('Unable to delete :(', 'error');
				}

				$this.attr('disabled', false);
				$this.html(btnText);
			});
		});

    });	

    $('.dataTables_info').before('<span id="selected_total" class="label label-success" style="font-size:12px; padding-top:5px;">Selected Total: <span>0.00</span></span>');

    $('.dataTables_info').hide();

    $(document).on('change','.pending-table .chk_post', function (event) {
        var total = 0.00;

        $('.chk_post:checked').each(function(){
            var amount = $(this).closest('tr').find('td:nth(6)').text();

            if (!isNaN(amount)) {
                total += Number(amount);
            }
        });

        total = Number(total).toFixed(2);

        $('#selected_total span').text(total);
    });

    $('#frmReplicate').submit(function(){
        if (confirm("Are you sure to replicate ?")) {
            var value = prompt("(Optional: Replicated stories description ? Click 'Cancel' to auto-retrive.)");
            $('#replicate_message').val(value);

            return true;
        }

        return false;
    });

</script>