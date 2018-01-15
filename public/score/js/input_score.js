function column_freezing(delet_col_url) /*'{{route('admin.course.delete-score')}}'*/
{
    hotInstance.updateSettings({
        contextMenu: {
            callback: function (key, options) {

                if (key === 'deletecol') {

                    if(hotInstance.getSelected()) {

                        var colIndex = hotInstance.getSelected()[1]; //console.log(hotInstance.getSelected()[1]);// return index of column header count from 0 index

                        // check not allow to delete on the specific columns
                        if(((colIndex != 0) && (colIndex != 1)) && ((colIndex != 2) && (colIndex != 3)) && ((colIndex != 4) && (colIndex != setting.colHeaders.length-1)) && (colIndex != setting.colHeaders.length-2)) {

                            var colNmae = setting.colHeaders[colIndex];
                            var percentageId = setting.data[0]['percentage_id_'+colNmae];
                            var courseAnnualId = setting.data[0]['course_annual_id'];
                            var deleteUrl = delet_col_url;
                            var baseData = {
                                percentage_id: percentageId,
                                percentage_name: colNmae,
                                course_annual_id: $('select[name=available_course] :selected').val()
                            };


                            swal({
                                title: "Confirm",
                                text: "Delete Score??",
                                type: "info",
                                showCancelButton: true,
                                confirmButtonColor: "#DD6B55",
                                confirmButtonText: "Yes",
                                closeOnConfirm: true
                            }, function(confirmed) {
                                if (confirmed) {

                                    $.ajax({
                                        type: 'DELETE',
                                        url: deleteUrl,
                                        data: baseData,
                                        dataType: "json",
                                        success: function(resultData) {
                                            notify('success', 'info', 'Score Deleted!!');
                                            updateSettingHandsontable(resultData);
                                        },
                                        error:function(e) {
                                            notify('error', 'Delete Error!', 'Attention');
                                        }
                                    });

                                }
                            });
                        } else {
                            notify('error', 'info', 'This Column is not Deletable');
                        }

                    } else {
                        notify('error', 'info', 'Column Score Not Selected!!')
                    }

                }
                if(key == 'freeze_column') {

                    if(hotInstance.getSelected()) {

                        var selectedColumn = hotInstance.getSelected()[1];

                        if(setting.fixedColumnsLeft) {

                            if (selectedColumn > setting.fixedColumnsLeft - 1) {

                                freezeColumn(selectedColumn);
                            } else {
                                unfreezeColumn(selectedColumn);
                            }

                        } else {

                            freezeColumn(selectedColumn);
                        }

                    }

                }

                function freezeColumn(column) {
                    setting.fixedColumnsLeft = column+1;
                    setting.manualColumnFreeze = true;
                    hotInstance.updateSettings({
                        fixedColumnsLeft: column + 1,
                        manualColumnFreeze: true
                    });
                }

                function unfreezeColumn(column) {


                    if (column > setting.fixedColumnsLeft - 1) {
                        return; // not fixed
                    }
                    removeFixedColumn(column+1);
                }

                function removeFixedColumn(column) {
                    hotInstance.updateSettings({
                        fixedColumnsLeft: column - 1
                    });
                    setting.fixedColumnsLeft--;
                }
            },
            items: {

                   "deletecol": {
                       name: '<span><i class="fa fa-trash"> Delete Column</i></span>'
                   },

                    "freeze_column": {
                        name: function() {
                            var selectedColumn = hotInstance.getSelected()[1];
                            if(setting.fixedColumnsLeft) {
                                if (selectedColumn > setting.fixedColumnsLeft - 1) {
                                    return '<span><i class="fa fa-fire"> Freeze This Column </i></span>';
                                } else {
                                    return '<span><i class="fa fa-leaf"> Unfreeze This Column </i></span>';
                                }
                            } else {
                                return '<span><i class="fa fa-fire"> Freeze This Column </i></span>';
                            }

                        }
                    }
            }
        }
    })
}


function setSelectedRow() {

    // var current_rows = $(document).find(".current_row");
    // if(current_rows != null){
    //     current_rows.removeClass("current_row");
    // }
    // $(".current").closest("tr").addClass("current_row");
}


// use this function to update the table when success of ajax request
function updateSettingHandsontable(resultData) {
    setting.data = resultData.data;
    setting.colHeaders = resultData.columnHeader;
    setting.columns = resultData.columns;

    if(!resultData.should_add_score) {
        $('.btn_action_group').hide();
    } else {
        $('.btn_action_group').show();
    }
    hotInstance.updateSettings({
        data: resultData['data'],
        colHeaders:resultData['columnHeader'],
        columns:resultData['columns']
    });
}

function init_input_score_table( resultData)
{

    if(hotInstance) {
        updateSettingHandsontable(resultData);
        declareColumnHeaderDataEmpty()

    } else {

        setting.data = resultData.data;
        setting.colHeaders = resultData.columnHeader;
        setting.columns = resultData.columns;
        declareColumnHeaderDataEmpty();
        var table_size = $('.box-body').width();
        var mainHeaderHeight = $('.main-header').height();
        var mainFooterHeight = $('.main-footer').height();
        var boxHeaderHeight = $('.box-header').height();
        var height = $(document).height();
        var tab_height = height - (mainHeaderHeight + mainFooterHeight + boxHeaderHeight + 70);

        setting.height=tab_height;
        setting.width=table_size;

        hotInstance = new Handsontable(jQuery("#score_table")[0], setting);

        $(window).on('resize', function(){
            var table_size = $('.box-body').width();
            setting.width=table_size;
            hotInstance.updateSettings({
                width:table_size
            });
        })

    }

}