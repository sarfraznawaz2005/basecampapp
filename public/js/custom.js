$(function () {

    var $dataTable = $('.dataTable');

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // select 2 for dropdowns
    var $select2 = $('select').not('.no_select2').select2();
    $select2.length && $select2.data('select2').$container.addClass('wrap');

    // BTS Popover
    $('[rel="popover"]').addClass('text-primary').popover({"trigger": "hover", "html": true});

    // BTS Tooltips
    $('[data-tooltip]').tooltip();

    // activate last active tab
    try {
        if (typeof selected_tab !== 'undefined') {
            var activeTab = $('a[href="' + selected_tab + '"]');
            activeTab && activeTab.tab('show');
        }
    }
    catch (e) {
    }

    // validate forms
    $('form.validate').validator({
        html: true,
        disable: false,
        focus: true
    });

    // add red * to any fields that have "required" attribute
    $('form input, textarea, select').not('.note-editor input, textarea, select').each(function () {
        var $label = $(this).parent().find('label');

        if (this.hasAttribute('required') && $label.length) {
            $label.html($label.html() + ' <span style="color:red; font-size: 16px;">*</span>');
        }
    });

    // make tables responsive with horizontal scrollbar on smaller screens
    $('table.table').not('.no_responsive').wrap('<div class="table-responsive"</div>');

    $('.pulsate').pulsate();

    $dataTable.dataTable().fnFilterOnReturn();
    // throw datatables errors to console instead of alert box
    $.fn.dataTable.ext.errMode = 'throw';

    // this event is called when datatable is drawn
    $dataTable.on('draw.dt', function () {
        // BTS Popover
        $('[rel="popover"]').addClass('text-primary').popover({"trigger": "click", "html": true});
        // BTS Tooltips
        $('[data-tooltip]').tooltip();

        // center elements with classs "center" inside tables
        $('.tdcenter').each(function () {
            $(this).parent().addClass('text-center');
        });
    });

    // for donut chart
    $(".donutChart").peity("donut");

});

// confirm delete
$('body').on('click', '.confirm-delete', function (e) {
    var label = $(this).data('label');
    var $form = $(this).closest('form');

    swal({
        title: "Are you sure?",
        text: label + " will be deleted!",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Yes, delete it!",
        closeOnConfirm: false
    }, function () {
        swal.disableButtons();
        $form.submit();
    });

    return false;
});

function showAlert(message, type, closeOnEscapeKey, callback) {
    type = type || '';

    if (typeof closeOnEscapeKey === 'undefined') {
        closeOnEscapeKey = true;
    }

    swal({
        title: "",
        text: message,
        type: type,
        html: true,
        allowEscapeKey: closeOnEscapeKey
    });

    if (typeof callback !== 'undefined' && typeof callback === 'function') {
        callback();
    }
}