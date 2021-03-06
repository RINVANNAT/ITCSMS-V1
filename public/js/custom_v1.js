function alert_error(title,message,todo){
    swal({
        title: title,
        text: message,
        type: "error",
        showCancelButton: false,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "OK",
        closeOnConfirm: true
    }, function(confirmed) {
        if (confirmed) {
            try {
                todo();
            } catch (e){
                // No need to do anythign here
            }
        }
    });
    return false;
}
function alert_warning(title,message,todo){
    swal({
        title: title,
        text: message,
        type: "warning",
        showCancelButton: false,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "OK",
        closeOnConfirm: true
    }, function(confirmed) {
        if (confirmed) {
            try {
                todo();
            } catch (e){
                // No need to do anythign here
            }
        }
    });
}
function alert_info(title,message,todo){
    swal({
        title: title,
        text: message,
        type: "info",
        showCancelButton: false,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "OK",
        closeOnConfirm: true
    }, function(confirmed) {
        if (confirmed) {
            try {
                todo();
            } catch (e){
                // No need to do anythign here
            }
        }
    });
}
function alert_confirm(title,message,todo){
    swal({
        title: title,
        text: message,
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "OK",
        closeOnConfirm: true
    }, function(confirmed) {
        if (confirmed) {
            try {
                todo();
            } catch (e){
                // No need to do anythign here
            }
        }
    });
}
function toggleVisible(className, obj) {
    var $input = $(obj);
    if ($input.prop('checked')) $(className).show();
    else $(className).hide();
}
// Search selector for employee
function formatRepoEmployee (repo) {

    if (repo.loading) {
        return repo.text;
    }
    if (repo.newOption) {
        return '<a href="#" class=""><em>Select employee below</em></a>';
    } else {
        var photo = "avatar.png";
        var gender = "NA";
        if(repo.photo != "" && repo.photo != null){
            photo = repo.photo;
        }
        if(repo.gender != null) {
            gender = repo.gender;
        }
        var markup = "<div class='select2-result-repository clearfix'>" +
            "<div class='select2-result-repository__avatar'><img src='"+base_url+"/"+photo+"' /></div>" +
            "<div class='select2-result-repository__meta'>" +
            "<div class='select2-result-repository__title'>" + repo.id_card+" | "+repo.text + "</div>"+
            "<div class='select2-result-repository__description'>" + repo.name_latin + "</div>"+
            "<div class='select2-result-repository__statistics'>" +
            "<div class='select2-result-repository__forks'><i class='fa fa-bank'></i> " + repo.department + "</div>" +
            "<div class='select2-result-repository__stargazers'><i class='fa fa-venus-mars'></i> " + gender + "</div>" +
            "</div>" +
            "</div>"+
            "</div>";
        return markup;

    }
}

function formatRepoSelectionUser (data, container) {

    $('#user_id').val(data.id);
    return data.name || data.email;
}

// Search selector for user

function formatRepoUser (repo) {

    if (repo.loading) {
        return repo.name;
    }
    if (repo.newOption) {
        return '<a href="#" class=""><em>Select user below</em></a>';
    } else {
        var markup =    "<div>" +
                            "<b>"+repo.name+"</b><br/>"+repo.email
                        "</div>";
        return markup;

    }
}

function formatRepoSelectionEmployee (data, container) {

    $('#lecturer_lists').val(data.id);
    return data.text || data.name_latin;
}

function get_filter_box(){
    var html = '<div class="o_cp_searchview" style="display: block;">'+
                    '<div class="o_searchview">'+
                        '<span class="o_searchview_more fa fa-search-minus" title="Advanced Search..."></span>'+
                        '<ul class="dropdown-menu o_searchview_autocomplete" role="menu" style="display: none;">'+
                            '<li class="o-selection-focus"><a href="#">Search <em>Product</em> for: <strong>dd</strong></a></li>'+
                            '<li><a class="o-expand" href="#"></a><a href="#">Search <em>Internal Category</em> for: <strong>dd</strong></a></li>'+
                            '<li><a class="o-expand" href="#"></a><a href="#">Search <em>Location</em> for: <strong>dd</strong></a></li>'+
                            '<li><a class="o-expand" href="#"></a><a href="#">Search <em>Warehouse</em> for: <strong>dd</strong></a></li>'+
                        '</ul>'+
                        '<div id="filter_items">' +
                            '<div class="o_searchview_facet" tabindex="0">'+
                            '<span class="fa fa-filter o_searchview_facet_label"></span>'+

                            '<div class="o_facet_values">'+
                            '<span>Products</span>'+
                            '<span class="o_facet_values_sep"> or </span>'+
                            '<span>Services</span>'+
                            '</div>'+
                            '<div class="fa fa-sm fa-remove o_facet_remove"></div>'+
                            '</div>'+
                        '</div>'+
                        '<input class="o_searchview_input" placeholder="Search..." type="text"></div>'+
                '</div>'+
                '<div class="slide_container">'+
                    'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus et urna erat, sed ullamcorper enim. Maecenas eu venenatis metus. Mauris tortor eros, condimentum vel faucibus ac, commodo fermentum magna. Fusce posuere interdum arcu, eget ornare eros tempus et. Maecenas convallis faucibus sem sed commodo. Donec leo nisi, consequat sed luctus eu, pellentesque eu nibh. Vivamus sed quam a tortor lobortis iaculis. Phasellus et odio sed tortor interdum accumsan. Duis tempor condimentum rhoncus. Maecenas lacinia, nulla ac adipiscing mollis, augue felis luctus lectus, quis ultrices nisl odio a diam. Nam massa sapien, consequat nec mollis eget, sodales ac velit. Maecenas vehicula, ante eu rutrum elementum, lorem urna luctus libero, at pulvinar leo turpis et quam.'+
                '</div>'+

        '';
    return html;
}
function get_filter_form(data){
    if(data.academic_years != undefined){

    }

}

function notify(type, message, title){
    toastr.options = {
        "closeButton": true,
        "debug": false,
        "newestOnTop": false,
        "progressBar": false,
        "positionClass": "toast-bottom-right",
        "preventDuplicates": true,
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "5000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    }
    toastr[type](message, title);
}

function totalScoreNotification(type, message, title) {

    toastr.options = {
        "closeButton": true,
        "debug": false,
        "newestOnTop": false,
        "progressBar": false,
        "positionClass": "toast-bottom-right",
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "5000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    }

    toastr[type](message+ '<br /><br /><button type="button" class="btn btn-xs clear">close</button>', title)
}

$(document).on('click','.clear', function (e) {
    $('.toast').hide();
})

function PopupCenterDual(url, title, w, h) {
    // Fixes dual-screen position Most browsers Firefox
    var dualScreenLeft = window.screenLeft != undefined ? window.screenLeft : screen.left;
    var dualScreenTop = window.screenTop != undefined ? window.screenTop : screen.top;
    width = window.innerWidth ? window.innerWidth : document.documentElement.clientWidth ? document.documentElement.clientWidth : screen.width;
    height = window.innerHeight ? window.innerHeight : document.documentElement.clientHeight ? document.documentElement.clientHeight : screen.height;

    var left = ((width / 2) - (w / 2)) + dualScreenLeft;
    var top = ((height / 2) - (h / 2)) + dualScreenTop;
    var newWindow = window.open(url, title, 'scrollbars=yes, width=' + w + ', height=' + h + ', top=' + top + ', left=' + left);

    // Puts focus on the newWindow
    if (window.focus) {
        newWindow.focus();
    }

    return newWindow;
}

function refundStudent(datatable){
    datatable.on('click', '.btn-refund[data-remote]', function (e) {
        var url = $(this).data('remote');
        e.preventDefault();
        swal({
            title: "Warning",
            text: "Are you sure you want to refund this payment?",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes, refund it!",
            closeOnConfirm: true
        }, function(confirmed) {
            if (confirmed) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                console.log('refunded');
                // confirm then
                $.ajax({
                    url: url,
                    type: 'POST',
                    dataType: 'json',
                    data: {method: '_POST'},
                }).always(function (data) {
                    datatable.DataTable().draw(false);
                });
            }
        });
        return false;

    });
}

function enableDeleteRecord(datatable){
    datatable.on('click', '.btn-delete[data-remote]', function (e) {
        var url = $(this).data('remote');
        e.preventDefault();
        swal({
            title: "Warning",
            text: "Are you sure you want to delete this item?",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes, delete it!",
            closeOnConfirm: true
        }, function(confirmed) {
            if (confirmed) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                // confirm then
                $.ajax({
                    url: url,
                    type: 'DELETE',
                    dataType: 'json',
                    data: {method: '_DELETE'},
                    success:function(result) {
                        if(result.status == true) {
                            notify('success', result.message);
                            datatable.DataTable().draw(false);

                        }
                    }
                }).always(function (data) {
                    datatable.DataTable().draw(false);
                });
            }
        });
        return false;

    });
}

function viewPopUpStudent(datatable){
    datatable.on('click', '.btn-show[data-remote]', function (e) {
        var url = $(this).data('remote');
        e.preventDefault();

        PopupCenterDual(url,'Student Detail','1200','960');
        return false;

    });
}

function toggleLoading(isLoading){
    if(isLoading){
        $('.loading').removeClass('hide');
    } else {
        $('.loading').addClass('hide');
    }
}

function allowNumberOnly(e){
    // Allow: backspace, delete, tab, escape, enter and .
    if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
            // Allow: Ctrl+A
        (e.keyCode == 65 && e.ctrlKey === true) ||
            // Allow: Ctrl+C
        (e.keyCode == 67 && e.ctrlKey === true) ||
            // Allow: Ctrl+X
        (e.keyCode == 88 && e.ctrlKey === true) ||
            // Allow: home, end, left, right
        (e.keyCode >= 35 && e.keyCode <= 39)) {
        // let it happen, don't do anything
        return;
    }
    // Ensure that it is a number and stop the keypress
    if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
        e.preventDefault();
    }
}

function convertMoney(number) {
    number = parseInt(number);
    text = "";
    if (number == 0) {
        text = "";
    } else if (number == 1) {
        text = "មួយ";
    } else if (number == 2) {
        text = "ពីរ";
    } else if (number == 3) {
        text = "បី";
    } else if (number == 4) {
        text = "បួន";
    } else if (number == 5) {
        text = "ប្រាំ";
    } else if (number == 6) {
        text = "ប្រាំមួយ";
    } else if (number == 7) {
        text = "ប្រាំពីរ";
    } else if (number == 8) {
        text = "ប្រាំបី";
    } else if (number == 9) {
        text = "ប្រាំបួន";
    } else if (number == 10) {
        text = "ដប់";
    } else if (number == 20) {
        text = "ម្ភែ";
    } else if (number == 30) {
        text = "សាមសិប";
    } else if (number == 40) {
        text = "សែសិប";
    } else if (number == 50) {
        text = "ហាសិប";
    } else if (number == 60) {
        text = "ហុកសិប";
    } else if (number == 70) {
        text = "ចិតសិប";
    } else if (number == 80) {
        text = "ប៉ែតសិប";
    } else if (number == 90) {
        text = "កៅសិប";
    } else if (number < 100) {
        prefix = convertMoney(number - (number % 10));
        suffix = convertMoney(number % 10);
        text = prefix + suffix;
    } else if (number < 1000) {
        prefix1 = convertMoney((number - (number % 100)) / 100) + "រយ ";
        suffix1 = convertMoney(number % 100);
        text = prefix1 + suffix1;
    } else if (number < 1000000) {
        prefix2 = convertMoney((number - (number % 1000)) / 1000) + "ពាន់ ";
        suffix2 = convertMoney(number % 1000);
        text = prefix2 + suffix2;

        /*} else if (number < 10000) {
         prefix2 = convertMoney((number - (number % 1000)) / 1000) + "ពាន់";
         suffix2 = convertMoney(number % 1000);
         text = prefix2 + suffix2;
         } else if (number < 100000) {
         prefix3 = convertMoney((number - (number % 10000)) / 10000) + "ម៉ឺន";
         suffix3 = convertMoney(number % 10000);
         text = prefix3 + suffix3;
         } else if (number < 1000000) {
         prefix4 = convertMoney((number - (number % 100000)) / 100000) + "សែន";
         suffix4 = convertMoney(number % 100000);
         text = prefix4 + suffix4;*/
    } else {
        prefix5 = convertMoney((number - (number % 1000000)) / 1000000) + "លាន ";
        suffix5 = convertMoney(number % 1000000);
        text = prefix5 + suffix5;
    }
    return text;
}

function convert1DigitKhmerNumber(number) {
    if (number == 0) {
        text = "០";
    } else if (number == 1) {
        text = "១";
    } else if (number == 2) {
        text = "២";
    } else if (number == 3) {
        text = "៣";
    } else if (number == 4) {
        text = "៤";
    } else if (number == 5) {
        text = "៥";
    } else if (number == 6) {
        text = "៦";
    } else if (number == 7) {
        text = "៧";
    } else if (number == 8) {
        text = "៨";
    } else if (number == 9) {
        text = "៩";
    }
    return text;
}

function convertKhmerNumber(ennumber) {
    //console.log(ennumber);
    ennumber = ennumber+"";
    khnumber = ennumber.split('');


    result = "";

    $.each(khnumber, function (index, value) {
        result = result + convert1DigitKhmerNumber(value);
    });

    return result;
}

function convertKhmerDate(arg) {
    if (arg == 'monday') {
        text = "ចន្ទ";
    } else if (arg == 'tuesday') {
        text = "អង្គារ";
    } else if (arg == 'wednesday') {
        text = "ពុធ";
    } else if (arg == 'thursday') {
        text = "ព្រហស្បត្ត៏";
    } else if (arg == 'friday') {
        text = "សុក្រ";
    } else if (arg == 'saturday') {
        text = "​សៅរ​ ";
    } else if (arg == 'sunday') {
        text = "អាទិត្រ";
    }
    return text;
}

function convertKhmerMonth(number) {
    number = parseInt(number);
    if (number == 1) {
        text = "មករា";
    } else if (number == 2) {
        text = "កុម្ភះ";
    } else if (number == 3) {
        text = "មិនា";
    } else if (number == 4) {
        text = "មេសា";
    } else if (number == 5) {
        text = "ឧសភា";
    } else if (number == 6) {
        text = "មិថុនា";
    } else if (number == 7) {
        text = "កក្កដា";
    } else if (number == 8) {
        text = "សីហា";
    } else if (number == 9) {
        text = "កញ្ញា";
    } else if (number == 10) {
        text = "តុលា";
    } else if (number == 11) {
        text = "វិច្ឆិការ";
    } else if (number == 12) {
        text = "ធ្នូ";
    }

    return text;
}

function convertKhmerGender(gender) {
    gender = gender.toLowerCase();

    if (gender == 'male' || gender =='m' || gender == 'ប') {
        return 'ប្រុស';
    } else {
        return 'ស្រី';
    }
}

function getKhmerCurrentDate() {
    var today = new Date();
    var dd = today.getDate();
    var mm = today.getMonth() + 1; //January is 0!
    var year = today.getFullYear();

    if (dd < 10) {
        dd = '0' + dd
    }

    if (mm < 10) {
        mm = '0' + mm
    }
    today = "ថ្ងៃទី"+ convertKhmerNumber(dd.toString()) + ' ខែ ' + convertKhmerMonth(mm) + ' ឆ្នាំ ' + convertKhmerNumber(year.toString());
    return today;
}

function getCurrentDate() {
    var today = new Date();
    var dd = today.getDate();
    var mm = today.getMonth() + 1; //January is 0!
    var yyyy = today.getFullYear();

    if (dd < 10) {
        dd = '0' + dd
    }

    if (mm < 10) {
        mm = '0' + mm
    }
    today = dd + "/" + mm + "/" + yyyy;
    return today;
}

function getSelectedRooms(allSelectedNode){
    var selectedRooms = [];
    $.each(allSelectedNode, function (id,obj) {
        if(obj.tags[0] == 'room'){ // This node is room
            selectedRooms.push(obj);
        }
    });
    return selectedRooms;
}
function getSelectedStaffs(allSelectedNode){
    var selectedStaffs = [];
    $.each(allSelectedNode, function (id,obj) {
        if(obj.tags[0] == 'staff'){ // This node is room
            selectedStaffs.push(obj);
        }
    });
    return selectedStaffs;
}
function countSelectedRoom(selectedRooms) {
    var result = {};
    result.count_room = 0;
    result.count_capacity = 0;

    $.each(selectedRooms, function (id,obj) {
        result.count_room = result.count_room+1;
        result.count_capacity = result.count_capacity+ obj.tags[1];
    });

    return result;
}

function countSelectedStaff(selectedStaffs) {
    var result = {};
    result.count_staff = 0;

    $.each(selectedStaffs, function (id,obj) {
        result.count_staff = result.count_staff+1;
    });

    return result;
}

function isSelectedRoom(room,allSelectedRoom){
    var found = false;
    $.each(allSelectedRoom,function(index,element){
        if(room.id == element.id){
            found = true;
            return;
        }
    });
    return found;
}

function isSelectedStaff(staff,allSelectedStaff){
    var found = false;
    $.each(allSelectedStaff,function(index,element){
        if(staff.id == element.id){
            found = true;
            return;
        }
    });
    return found;
}

function getRooms(allRooms, selectedRooms) {

    var roomTypes = new Array();
    var roomFound = false;


    $.each(allRooms, function (id, obj) {
        var roomType = {
            text: obj.name,
            color: "#000000",
            backColor: "#FFFFFF",
            href: obj.id,
            selectable: true,
            state: {
                expanded: false,
                selected: false
            },
            tags: ['type']
        };

        if (obj.rooms != '') {
            var buildings = new Array();
            $.each(obj.rooms, function (roomId, roomObj) {
                roomFound = isSelectedRoom(roomObj,selectedRooms);
                var buildingFound = false;
                var room = {
                    text: roomObj.name,
                    color: "#000000",
                    backColor: "#FFFFFF",
                    href: roomObj.id,
                    selectable: true,
                    state: {
                        expanded: true,
                        selected: roomFound
                    },
                    tags: ['room',roomObj.capacity,roomObj.id]
                }

                $.each(buildings, function (buildingId, buildingObj) {
                    //console.log(roomObj.building.name+'-'+buildingObj.text);
                    if (roomObj.building.name == buildingObj.text) {
                        if(room.state.selected){
                            buildingObj.state.selected = true;
                            roomType.state.selected = true;
                        }
                        buildingObj.nodes.push(room);
                        buildingFound = true;
                        return false;
                    }
                })

                // if building not found, add new building and add current room to that building
                if (!buildingFound) {
                    var building = {
                        text: roomObj.building.name,
                        color: "#000000",
                        backColor: "#FFFFFF",
                        selectable: true,
                        state: {
                            expanded: true,
                            selected: false
                        },
                        tags: ['building'],
                        nodes: [room]
                    }

                    buildings.push(building);
                }

            });


            roomType['nodes'] = buildings;
        }

        roomTypes.push(roomType);
    });

    return roomTypes;
}

function getStaffs(allStaffs, selectedStaffs) {


    var staffRoles = new Array();
    var staffFound = false;

    $.each(allStaffs, function (id, obj) {
        var staffRole = {
            text: obj.name,
            color: "#000000",
            backColor: "#FFFFFF",
            href: obj.id,
            selectable: true,
            state: {
                expanded: false,
                selected: false
            },
            tags: ['role'],
            nodes:[]
        };

        if(obj.employees.length>0){
            var departments = new Array();
            //console.log(obj.employees);
            $.each(obj.employees, function (staffId, staffObj) {
                staffFound = isSelectedStaff(staffObj,selectedStaffs);
                //console.log(staffObj.department.code);
                var departmentFound = false;
                var staff = {
                    text: staffObj.name_latin,
                    color: "#000000",
                    backColor: "#FFFFFF",
                    href: staffObj.id,
                    selectable: true,
                    state: {
                        expanded: false,
                        selected: staffFound
                    },
                    tags: ['staff',staffObj.id]
                };

                $.each(departments, function (departmentId, departmentObj) {
                    if (staffObj.department.code == departmentObj.text) {
                        if(staff.state.selected){
                            //departmentObj.state.selected = true;
                            //staffRole.state.selected = true;
                        }
                        departmentObj.nodes.push(staff);
                        departmentFound = true;
                        return false;
                    }
                })

                // if department not found, add new department and add current staff to that department
                if (!departmentFound) {

                    var department = {
                        text: staffObj.department.code,
                        color: "#000000",
                        backColor: "#FFFFFF",
                        selectable: true,
                        state: {
                            expanded: true,
                            selected: false
                        },
                        tags: ['department'],
                        nodes: [staff]
                    }

                    departments.push(department);
                }

            });
            staffRole['nodes']=departments;
        }

        staffRoles.push(staffRole);


    });
    return staffRoles;
}



