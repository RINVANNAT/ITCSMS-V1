var hotInstance;
var colWidth = function() {

    return [
        50,
        100,
        150,
        50,
        80,
        80,
        120,
        120,
        120,
        120,
        120,
        180
    ]
};

var EN_HEADER = function()
{
    var EN_SECTION_HEADER = [

                [
                    '',
                    'Student ID',
                    'Student Name',
                    'Sexe',

                    { label: 'Absence', colspan :3 },

                    { label : 'Competency', colspan :4 },

                    'Toal Score'
                ],
                [
                    '',
                    '',
                    '',
                    '',
                    { label : 'Total', colspan: 1 },
                    { label :'S_1', colspan : 1 },
                    { label : 'S_2', colspan : 1 },

                    { label : 'Speaking', colspan : 1 },
                    { label : 'Writing', colspan : 1 },
                    { label : 'Listening', colspan : 1 },
                    { label : 'Reading', colspan : 1 } ,
                    ''
                ]
    ];

    return EN_SECTION_HEADER;

};
var FR_HEADER = function()
{
    var FR_SECTION_HEADER = [

        [
            'N.o',
            'Student ID',
            'Student Name',
            'Sexe',
            'Department',
            'Group',

            { label : 'Comprehension De 4 Competent', colspan :4 },

            'Toal Score',
            'ADMISSION'
        ],
        [
            '',
            '',
            '',
            '',
            '',
            '',
            { label : 'CO | (25/25)', colspan : 1 },
            { label : 'CE | (25/25)', colspan : 1 },
            { label : 'PO | (25/25)', colspan : 1 },
            { label : 'PE | (25/25)', colspan : 1 } ,
            '',
            ''
        ]
    ];

    return FR_SECTION_HEADER;

}
var setting =  {

    rowHeaders: false,
    colHeaders: false,
    // performance tip: set constant size
    rowHeights: 10,
    // performance tip: turn off calculations
    autoRowSize: false,
    autoColSize: false,
    colWidths:colWidth(),
    minSpareRows: 1,
    readOnly: true,
    manualColumnMove: true,
    manualColumnResize: true,
    manualRowResize: false,
    filters: true,
    dropdownMenu: ['filter_by_condition', 'filter_action_bar'],
    className: "htCenter",
    cells: function (row, col, prop) {
        var cellProperties = {};

        if (prop === 'co') {
            cellProperties.readOnly = false;
        } else if (prop === 'ce') {
            cellProperties.readOnly = false;
        } else if (prop === 'po') {
            cellProperties.readOnly = false;
        } else if (prop === 'pe') {
            cellProperties.readOnly = false;
        }
        return cellProperties;
    },
    afterGetCellMeta: function (prop) {

    },
    afterSelectionEnd: function (prop) {

        setSelectedRow()
    },
    afterChange: function (changes, source) {
        if(changes) {

            $.each(changes, function (index, element) {

                var change = element;
                var rowIndex = change[0];
                var columnIndex = change[1];
                var oldValue = change[2];
                var newValue = change[3];
                var col_student_id = hotInstance.getDataAtProp('student_id_card'); //---array data of column student_id


                if(columnIndex == 'co') {

                    alert(newValue);
                }

                if(columnIndex == 'ce') {

                }
                if(columnIndex == 'po') {

                }

                if(columnIndex == 'pe') {

                }


            });

        }
    }
}

function calculateSite(hotInstance)
{

    if(hotInstance) {

        var table_size = $('.box-body').width();
        var mainFooterHeight = $('#box_footer').height();
        var boxHeaderHeight = $('.box-header').height();
        var height = $(document).height();
        var tab_height = (height/2) + (10) ;

        setting.height=tab_height;
        setting.width=table_size;
        hotInstance.updateSettings({
            height:tab_height,
            width:table_size
        });

        $(window).on('resize', function(){
            var table_size = $('.box-body').width();
            setting.width=table_size;
            hotInstance.updateSettings({
                width:table_size
            });
        });

    }

}

function updateHeader(headers, hotInstance)
{
        hotInstance.updateSettings({
            nestedHeaders: headers
        });
}

function updateSettingData(resultData, hotInstance) {

    setting.data = resultData;

    hotInstance.updateSettings({
        data: resultData,
    });
}

function setSelectedRow() {

    var current_rows = $(document).find(".current_row");
    if(current_rows != null){
        current_rows.removeClass("current_row");
    }
    $(".current").closest("tr").addClass("current_row");
}