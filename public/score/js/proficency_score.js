var hotInstance, setting,
    colDataArray = [],
    array_col_status = {},
    objectStatus ={},
    CELL_CHANGE = [];

setting =  {

    readOnly: true,
    rowHeaders: true,
    columnHeaders:true,
    manualColumnMove: true,
    manualColumnResize: true,
    manualRowResize: false,
    minSpareRows: 1,
    filters: true,
    autoWrapRow: true,
    dropdownMenu: ['filter_by_condition', 'filter_action_bar'],
    className: "htCenter",
    stretchH: 'last',
    beforeOnCellMouseDown: function (event, coord, TD) {
        return true;
    },
    afterOnCellMouseDown: function (event, coord, TD) {
        return true;
    },
    afterCellMetaReset: function () {
        return true;
    },
    afterRowMove: function () {
        return true;
    },

    afterSelectionEnd: function () {
        setSelectedRow();
        return true;
    },
    beforeTouchScroll: function () {

        return false;
    },
    afterScrollHorizontally: function () {
        setSelectedRow();
        return true;
    },

    afterScrollVertically:function () {
        setSelectedRow();
        return true;
    },

    afterColumnResize: function () {
        return false;
    }
}

function calculateSite(setting)
{

    var table_size = $('.box-body').width();
    var mainFooterHeight = $('#box_footer').height();
    var boxHeaderHeight = $('.box-header').height();
    var height = $(document).height();
    var tab_height = (parseInt(height) * 0.8);
    setting.height=tab_height;
    setting.width=table_size;

    return setting;
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

function onInputScoreChange(newValue, cellMaxValue, Fraud, Absence, student_annual_id, oldValue)
{
    var element={};
    if(($.isNumeric(newValue) || (newValue == '')) || ((newValue == Fraud) || (newValue == Absence))) {

        if((newValue <= cellMaxValue) ||  (newValue >= parseInt(0) ) || (newValue == '') || ((newValue == Fraud) || (newValue == Absence))) {

            if(newValue <= cellMaxValue) {
                element = {
                    score: newValue,
                    course_annual_id: $('input[name=course_annual_id]').val(),
                    student_annual_id: student_annual_id
                };

            } else if(!$.isNumeric(newValue))  {
                element = {
                    score: newValue,
                    course_annual_id: $('input[name=course_annual_id]').val(),
                    student_annual_id: student_annual_id
                };
            } else {
                if(newValue > cellMaxValue) {
                   return {};
                } else {
                    element = {
                        score: 0,
                        course_annual_id: $('input[name=course_annual_id]').val(),
                        student_annual_id: student_annual_id
                    };
                }
            }

            if(oldValue != newValue) {

                return element;
            } else {
                return {};
            }

        } else {
            return {};
        }
    } else {
        return {};
    }

}

function checkIfStringValExist(colData, colName, valToCompare, Fraud, Absence) {

    var arrayNull=[];
    var count = 0, overRattedScore = 0;
    for(var check =0; check < colData.length; check++) {

        if($.isNumeric(colData[check]) && (parseInt(colData[check]) >= 0)) {
            count++;
            if((colData[check] <= valToCompare) ) {
                overRattedScore++;
            }
        } else if( ((colData[check] == null) || (colData[check] == ''))  || ((colData[check] == Fraud) || (colData[check] == Absence))) {// to check if he/she deose not input any value or input only empty string
            arrayNull.push(colData[check])
        }
    }

    if((parseInt(count) + arrayNull.length) == colData.length) {

        if((parseInt(overRattedScore) + arrayNull.length) == colData.length) {
            objectStatus.status = true;
        } else {
            objectStatus.status = false;
            objectStatus.val_to_compare = valToCompare;
            objectStatus.colName = colName;
        }
    } else {
        objectStatus.status = false;
        objectStatus.val_to_compare = valToCompare;
        objectStatus.colName = colName;
    }
    array_col_status[colName] = objectStatus.status;

}


function declareColumnHeaderDataEmpty(array_col) {

    for(var i = 0; i < array_col.length ; i++) {
        colDataArray[array_col[i]] = [];
    }

}
function getHeader()
{
    $.ajax({
        type: 'GET',
        url: '/admin/course/course-annual/header-competency-score',
        data: {course_annual_id: $('input[name=course_annual_id]').val(), _token:$('input[name=token]').val()},
        dataType: "json",
        success: function (resultData) {
            /*setNestedHeader(resultData);*/
        },
        error:function(response) {
            notify('error', 'SomeThing Went Wrong!', 'Attention!')
        }
    });
}

var setNestedHeader =   function (param)
{
    setting.nestedHeaders = param
};



function sendRequest (col_array) {

    var saveBaseUrl = '/admin/course/course-annual/save-competency-score';

    $.each(col_array, function(key, val) {

        if(colDataArray[val].length > 0) {

            var DATA = {
                data:colDataArray[val],
                key: val
            };
            saveScoreViaAjax(saveBaseUrl, 'POST', DATA, val);
        }
    });
}

function resetColDataArray(key) {
    colDataArray[key] = [];
}

function saveScoreViaAjax(saveBaseUrl, method, baseData, key)
{
    $.ajax({
        type: method,
        url: saveBaseUrl,
        data: baseData,//{data:colDataArray[setting.colHeaders[index]], percentage: setting.colHeaders[index] },
        dataType: "json",
        success: function(resultData) {
            resetColDataArray(key);
            notify('success', 'Saved!', 'Info');

        },
        error: function(error) {
            resetColDataArray(key);
            notify('error', 'Something went wrong!');
        }
    })
}