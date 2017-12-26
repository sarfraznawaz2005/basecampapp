function sumColumn(tableSelector, columnIndex) {
    var total = 0;
    var totalTDs = $(tableSelector + ' tr:first-child td').length;

    $(tableSelector + ' tbody tr').find('td:nth-child(' + (columnIndex) + ')').each(function () {
        var amount = $(this).text();

        if (!isNaN(amount)) {
            total += Number(amount);
        }
    });

    // add table row
    $(tableSelector).append('<tr></tr>');

    for (var i = 1; i <= totalTDs; i++) {
        if (i === columnIndex) {
            $(tableSelector + ' tr:last').append('<td align="center"><strong>' + Number(total).toFixed(2) + '</strong></td>');
        }
        else {
            $(tableSelector + ' tr:last').append('<td>&nbsp;</td>');
        }
    }

}