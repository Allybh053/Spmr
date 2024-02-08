// For datatable
var ConfigDTGlobal = {
    dom : 'Bfrtip',
    language : {
        search : " ",
        searchPlaceholder : "Search",
        zeroRecords: "No matching records found",
        info : 'Showing _START_ to _END_ from _TOTAL_ records',
        infoEmpty : "Showing 0 to 0 from 0 records",
        infoPostFix : "",
        infoFiltered : "",
        processing : 'Upload a record. Please wait...',
        paginate : {
            first :    "Start",
            last :     "End",
            previous : "Previous",
            next :     "Next"
        },
        lengthMenu :
            '<div class="form-group select2">'+
                'Show&nbsp;<select class="form-control select2">'+
                    '<option value="10">10</option>'+
                    '<option value="25">25</option>'+
                    '<option value="100">100</option>'+
                    '<option value="-1">All</option>'+
                '</select>'+
            '&nbsp;records</div>',
    },
    ordering : true,
    searching : true,
    processing: true,
    serverSide: true,
    ajax: '',
    columns: []
};

