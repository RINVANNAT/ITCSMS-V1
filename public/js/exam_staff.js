
    function toggleSidebarStaffRole() {

        var right_staff_role = $("#side_window_right_staff_role"),
            content = $("#main_window_staff_role"),
            contentClass = "";
        if (content.hasClass("col-sm-6")) {
            contentClass = "col-sm-12";
            right_staff_role.hide();
            $('#btn_add_role').show();

            if($("#selected_staffs").jstree('get_selected').length !== 0) {

                $('#btn_delete_node').show();
                $('#btn_move_node').show();
            } else {
                $('#btn_delete_node').hide();
                $('#btn_move_node').hide();
            }



        } else {
            contentClass = "col-sm-6";
            $('#btn_add_role').hide();


        }
        content.removeClass("col-sm-12 col-sm-9 col-sm-6")
            .addClass(contentClass);
        if(content.hasClass("col-sm-6")){
            right_staff_role.delay(300).show(0);
        }
    }

    function initJsTree_StaffRole( object, url_lv1, url_lv2, url_lv3, iconUrl1, iconUrl2, iconUrl3) {

        object.jstree({
            "core" : {
                "animation":0,
                "check_callback" : true,
                'force_text' : true,
                "themes" : {
                    "variant" : "large",
                    "stripes" : true
                },
                "data":{
                    'url' : function (node) {

                        if(node.id == '#'){
                            return url_lv1;
                        } else {

                            var node_id = node.id.split('_');
                            if(node_id[0] == 'position'){
                                return url_lv3;
                            } else {
                                return url_lv2;
                            }
                        }
                        //return node.id === '#' ? url_lv1 : url_lv2;
                    },
                    'data' : function (node) {
                        return { 'id' : node.id };
                    }
                }
            },

        "checkbox" : {
                "keep_selected_style" : false
            },
            "types" : {
                "#" : { "max_depth" : 3, "valid_children" : ["department","position", "staff"] },
                "department" : {
                    "icon" : iconUrl1,
                    "valid_children" : ["position"]
                },
                "position" :{
                    "icon" : iconUrl2,
                    "valid_children" : ["staff"]
                },
                "staff" :{
                    "icon" : iconUrl3,
                    "valid_children" : []
                }
            },
            "plugins" : [
                "wholerow",'checkbox', "contextmenu", "search", "state","types", "html_data"
            ]
        });

    }

    function initJsTree_StaffSelected( object, url_lv1, url_lv2, iconUrl2,iconUrl3 ) {

        object.jstree({

            "core" : {
                "animation":0,
                "check_callback" : true,
                'force_text' : true,
                "themes" : {
                    "variant" : "large",
                    "stripes" : true
                },
                "data":{
                    'url' : function (node) {
                        return node.id === '#' ? url_lv1 : url_lv2;
                    },
                    'data' : function (node) {
                        return { 'id' : node.id };
                    }
                }
            },
            "checkbox" : {
                "keep_selected_style" : false
            },
            "types" : {
                "#" : { "max_depth" : 3, "valid_children" : ["role","staff"] },
                "role" : {
                    "icon" : iconUrl2,
                    "valid_children" : ["room"]
                },
                "staff" :{
                    "icon" : iconUrl3,
                    "valid_children" : []
                }
            },
            "plugins" : [
                "wholerow",'checkbox', "contextmenu", "search", "state","types"
            ]
        });

    }

    $('#btn_delete_node').hide();
    $('#btn_move_node').hide();

    $(document).ready(function() {

        $("#btn_add_role").click(function () {
            toggleSidebarStaffRole();

                if($("#selected_staffs").jstree('get_selected').length !== 0) {

                    $('#btn_delete_node').show();
                    $('#btn_move_node').show();
                } else {
                    $('#btn_delete_node').hide();
                    $('#btn_move_node').hide();
                }

                $("#selected_staffs").bind('select_node.jstree', function(e) {

                    if($('.popUpRoleDown').hasClass('show')) {
                        $('#btn_delete_node').hide();
                        $('#btn_move_node').hide();
                    } else {
                        $('#btn_delete_node').show();
                        $('#btn_move_node').show();
                    }


                }).bind("deselect_node.jstree", function(evt, data) {

                    if($("#selected_staffs").jstree('get_selected').length != 0) {
                        if($('.popUpRoleDown').hasClass('show')) {
                            $('#btn_delete_node').hide();
                            $('#btn_move_node').hide();
                        } else {
                            $('#btn_delete_node').show();
                            $('#btn_move_node').show();
                        }
                    } else {
                        //console.log($("#selected_staffs").jstree('get_selected').length);
                        $('#btn_delete_node').hide();
                        $('#btn_move_node').hide();

                        if($('.popUpRoleDown').hasClass('show')) {
                            $('.popUpRoleDown').slideFadeToggle().removeClass('show');
                        }

                    }
                });

        });


        $('#btn_cancel_staff_role').on('click',function() {

            $('.popUpRoleDown').hide();
            toggleSidebarStaffRole();
            return false;
        });


    });




    $('#btn_move_node').on('click', function() {
        disableButton($('#btn_delete_node'), $('#btn_move_node') );
        $('.popUpRoleDown').slideFadeToggle().addClass('show');



    });



    $('#btn_cancel_chang_role').on('click', function() {
        $('.popUpRoleDown').slideFadeToggle().removeClass('show');

        if($("#selected_staffs").jstree('get_selected').length !== 0) {

            $('#btn_delete_node').show();
            $('#btn_move_node').show();
        } else {
            $('#btn_delete_node').hide();
            $('#btn_move_node').hide();
        }
    });


    function ajaxRequest(method, baseUrl, baseData){
        console.log('hello');
         $.ajax({
            type: method,
            url: baseUrl,
            data: baseData,
            dataType: "json",
            success: function(resultData) {
                console.log(resultData);
                if(resultData.status=='add_role_success'){
                    var myOptions = {
                        val1: resultData.role_id
                    }
                    var selectOption = $('#role');
                    $.each(myOptions, function() {
                        selectOption.prepend(
                            $('<option></option>').val(resultData.role_id).html(resultData.role_name).attr("selected","selected")
                        );
                        $("#new_role").val(null);
                        $("#new_des").val(null);
                        $('.popUpRole').slideFadeToggle();

                    });
                }
                $('#all_staff_role').jstree("refresh");
                $('#selected_staffs').jstree("refresh");
                notify("success","info", "You have done!");
            }
        });
    }

    function disableButton (object1, object2) {

        object1.hide();
        object2.hide();
    }


    function clickAddRole (object) {
        disableButton($('#btn_delete_node'), $('#btn_move_node') );
        $('.popUpRoleDown').slideFadeToggle();

    }

    $.fn.slideFadeToggle = function(easing, callback) {

        return this.animate({ opacity: 'toggle', height: 'toggle' }, 'fast', easing, callback);

    };


    if($("#all_staff_role").jstree('get_selected').length !== 0) {

        $('#btn_save_staff_role').show();
    } else {
        $('#btn_save_staff_role').hide();
    }

    $("#all_staff_role").bind('select_node.jstree', function(e) {

        $('#btn_save_staff_role').show();
        //console.log($("#all_staff_role").jstree('get_selected'));

    }).bind("deselect_node.jstree", function(evt, data) {

        //console.log($("#all_staff_role").jstree('get_selected'));

        var selected_arr = $("#all_staff_role").jstree('get_selected');
        console.log(selected_arr);
        var only_staff = [];
        for (var i=0;i<selected_arr.length;i++){
            var element = selected_arr[i].split('_');

            console.log(element);

            if(element.length >3) {
                only_staff.push(element);
            }
        }

        if(only_staff.length != 0 ) {
                $('#btn_save_staff_role').show();
        } else {
            $('#btn_save_staff_role').hide();

        }
    });

    $('#btn_save_staff_role').hide();

    $('#btn_add_new_role').on('click', function() {
        $('.popUpRole').slideFadeToggle();
    })

    $('#alert_add_role_staff').hide();
    $('#alert_save_staff_role').hide();
    $('#check_ok').hide();
    $('#alert_delete_role_staff').hide();
    $('#alert_delete_role_staff_success').hide();

    function toggleLoading(isLoading){
        if(isLoading){
            $('.loading').removeClass('hide');
        } else {
            $('.loading').addClass('hide');
        }
    }







