
function toggleSidebarStaffRole() {

    var right_staff_role = $("#side_window_right_staff_role"),
        content = $("#main_window_staff_role"),
        contentClass = "";
    if (content.hasClass("col-sm-6")) {
        contentClass = "col-sm-12";
        right_staff_role.hide();
        $('#btn_delete_node').hide();
        $('#btn_move_node').hide();
        $('#btn_add_role').show();

    } else {
        contentClass = "col-sm-6";
        $('#btn_add_role').hide();
        $('#btn_delete_node').show();
        $('#btn_move_node').show();

    }
    content.removeClass("col-sm-12 col-sm-9 col-sm-6")
        .addClass(contentClass);
    if(content.hasClass("col-sm-6")){
        right_staff_role.delay(300).show(0);
    }
}


function initJsTree_StaffRole( object, url_lv1, url_lv2, url_lv3) {

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
                    console.log(node.id);
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
            "#" : { "max_depth" : 3, "valid_children" : ["building","room"] },
            "building" : {
                "icon" : "{{url('plugins/jstree/img/building.png')}}",
                "valid_children" : ["room"]
            },
            "room" :{
                "icon" : "{{url('plugins/jstree/img/door.png')}}",
                "valid_children" : []
            }
        },
        "plugins" : [
            "wholerow",'checkbox', "contextmenu", "dnd", "search", "state","types"
        ]
    });

}

function initJsTree_StaffSelected( object, url_lv1, url_lv2) {

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
            "#" : { "max_depth" : 3, "valid_children" : ["building","room"] },
            "building" : {
                "icon" : "{{url('plugins/jstree/img/building.png')}}",
                "valid_children" : ["room"]
            },
            "room" :{
                "icon" : "{{url('plugins/jstree/img/door.png')}}",
                "valid_children" : []
            }
        },
        "plugins" : [
            "wholerow",'checkbox', "contextmenu", "dnd", "search", "state","types"
        ]
    });

}


$(function(){
    $("#btn_add_role").click(function () {
        toggleSidebarStaffRole();
        return false;
    });

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
                    selectOption.append(
                        $('<option></option>').val(resultData.role_id).html(resultData.role_name)
                    );
                    $("#new_role").val(null);
                    $("#new_des").val(null);
                    clickAddRole();
                });
            }
            $('#all_staff_role').jstree("refresh");
            $('#selected_staffs').jstree("refresh");

        }
    });
}

function disableButton (object1, object2) {
    //$('#btn_delete_node').hide();
    //$('#btn_move_node').hide();

    object1.hide();
    object2.hide();
    console.log('hello');
}

function clickAddRole (object) {
    disableButton($('#btn_delete_node'), $('#btn_move_node') );
    object.slideFadeToggle();
}

$.fn.slideFadeToggle = function(easing, callback) {
    console.log('called');

    return this.animate({ opacity: 'toggle', height: 'toggle' }, 'fast', easing, callback);

};
$('#btn_delete_node').hide();
$('#btn_move_node').hide();

$('#btn_cancel_staff_role').on('click',function() {
    $('.popUpRoleDown').hide();
    toggleSidebarStaffRole();
    return false;
})

$('#btn_cancel_chang_role').on('click', function() {

    clickAddRole($('.popUpRoleDown'));
    $('#btn_delete_node').show();
    $('#btn_move_node').show();
})

$('#btn_add_new_role').on('click', function() {
    $('.popUpRole').slideFadeToggle();
})

$('#alert_add_role_staff').hide();