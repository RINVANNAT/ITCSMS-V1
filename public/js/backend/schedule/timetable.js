
/** Get weeks. **/
function get_weeks(semester_id) {
	toggleLoading(true);
	$.ajax({
		type: 'POST',
		url: '/admin/schedule/timetables/get_weeks',
		data: {semester_id: semester_id},
		success: function (response) {
			var option = '';
			$.each(response.weeks, function (key, val) {
				option += '<option value="' + val.id + '">' + val.name_en + '</option>';
			});
			
			$('select[name="weekly"]').html(option);
		},
		error: function () {
			swal(
				'Oops...',
				'Something went wrong!',
				'error'
			);
		},
		complete: function () {
			get_course_programs();
			get_timetable();
			get_timetable_slots();
			toggleLoading(false);
		}
	});
}

/** Get options. **/
function get_options(department_id) {
	toggleLoading(true);
	$.ajax({
		type: 'POST',
		url: '/admin/schedule/timetables/get_options',
		data: {department_id: department_id},
		success: function (response) {
			var option = '';
			$.each(response.options, function (key, val) {
				option += '<option value="' + val.id + '">' + val.code + '</option>';
			});
			
			$('select[name="option"]').html(option);
		},
		error: function () {
			swal(
				'Get Options',
				'Something went wrong!',
				'error'
			);
		},
		complete: function () {
			get_grades(department_id);
			toggleLoading(false);
		}
	});
}


function get_grades(department_id) {
	$.ajax({
		type: 'POST',
		url: '/admin/schedule/timetables/get_grades',
		data: {department_id: department_id},
		success: function (response) {
			if (response.status === true) {
				var grades = '';
				$.each(response.grades, function (key, val) {
					grades += '<option value="' + val.id + '">' + val.name_en + '</option>';
				});
				$('select[name="grade"]').html(grades);
			}
		},
		error: function () {
			swal(
				'Get Grades',
				'Something went wrong!',
				'error'
			);
		},
		complete: function () {
			get_course_programs();
			get_timetable();
			get_timetable_slots();
		}
	})
}

/** Get course sessions. **/
function get_course_programs() {
	toggleLoading(true);
	$.ajax({
		type: 'POST',
		url: '/admin/schedule/timetables/get_course_programs',
		data: $('#options-filter').serialize(),
		success: function (response) {
			if (response.status === true && response.data.length > 0) {
				var course_session_item = '';
				$.each(response.data, function (key, val) {
					if (val.teacher_name === null) {
						course_session_item += '<li class="course-item disabled">';
					}
					else {
						course_session_item += '<li class="course-item">';
					}
					
					course_session_item += '<span class="text course-name">' + val.course_name + '</span><br>'
					
					if (val.tp !== 0) {
						course_session_item += '<span class="course-type"><strong>TP</strong></span> : ' +
							'<span class="times">' + val.total_hours + '</span> H <br/>'
					}
					else if (val.td !== 0) {
						course_session_item += '<span class="course-type"><strong>TD</strong></span> : ' +
							'<span class="times">' + val.total_hours + '</span> H <br/>'
					}
					else {
						course_session_item += '<span class="course-type"><strong>Course</strong></span> : ' +
							'<span class="times">' + val.total_hours + '</span> H <br/>'
					}
					
					if (val.groups.length > 0) {
						course_session_item += '<div class="list-groups"><span><strong>Groups: </strong></span>'
						val.groups.forEach((eachGroup) => {
							course_session_item += '<span class="bg-success badge remove-group-from-course-program"><span class="group-id hidden">'+ eachGroup.id +'</span>' + eachGroup.code + '</span>'
						})
						course_session_item += '</div>'
					} else {
						course_session_item += `
							<div class="list-groups">
								<span><strong>Groups: </strong></span>
								<span class="teacher_name bg-danger badge">No Groups</span><br/>
							</div>
						`
					}
					
					course_session_item += '<span class="hidden lecturer-id">' + val.lecturer_id + '</span>';
					course_session_item += '<span class="text course_program_id" style="display: none;">' + val.course_program_id + '</span><span class="text slot-id" style="display: none;">' + val.id + '</span><br>' + '</li>';
				});
				
				$('.courses.todo-list').html(course_session_item)
				drag_course_session()
			}
			else {
				$('.courses.todo-list').html("<li class='course-item text-center'>No Courses!</li>")
			}
		},
		error: function () {
			swal(
				'Oops...',
				'Something went wrong!',
				'error'
			)
		},
		complete: function () {
			toggleLoading(false)
		}
	});
}

/** Search rooms. **/
function search_rooms(query) {
	$.ajax({
		type: 'POST',
		url: '/admin/schedule/timetables/search_rooms',
		data: {query: query},
		success: function (response) {
			if (response.status === true) {
				var room_item = '';
				$.each(response.rooms, function (key, val) {
					room_item += '<div class="info-box">'
						+ '<span class="info-box-icon bg-aqua">'
						+ '<span>' + val.code + '-' + val.name + '</span>'
						+ '</span>'
						+ '<div class="info-box-content">'
						+ '<span class="info-box-number">' + val.room_type + '</span>'
						+ '<span class="info-box-text text-muted">' + (val.desk === null ? 'N/A' : val.desk) + ' Desk</span>'
						+ '<span class="info-box-text text-muted">' + (val.chair === null ? 'N/A' : val.chair) + ' Chair</span>'
						+ '</div>'
						+ '</div>';
				});
				
				$('.rooms').html(room_item);
			}
			else {
				var message = '<div class="room-item bg-danger" style="width: 100%; background-color: red; color: #fff;">' +
					'<i class="fa fa-warning"></i> Room not found!' +
					'</div>';
				$('.rooms').html(message);
			}
		},
		error: function () {
			swal(
				'Oops...',
				'Something went wrong!',
				'error'
			);
		}
	});
}

/** set background color slot not allow */
function set_background_color_slot_not_allow() {
	$('.view-timetable').find('[data-time="11:00:00"]').addClass('slot-not-allow');
	$('.view-timetable').find('[data-time="11:30:00"]').addClass('slot-not-allow');
	$('.view-timetable').find('[data-time="12:00:00"]').addClass('slot-not-allow');
	$('.view-timetable').find('[data-time="12:30:00"]').addClass('slot-not-allow');
	$('.view-timetable').find('[data-time="17:00:00"]').addClass('slot-not-allow');
}


function get_employees(query = null) {
	axios.post('/admin/schedule/timetables/get_employees', {
		query: query
	}).then(response => {
		if (response.data.data.length > 0) {
			let employee_template = '';
			response.data.data.forEach((employee) => {
				employee_template += `
                    <li class="select2-results__option"
                        role="treeitem"
                        aria-selected="false">
                        <div class="select2-result-repository clearfix">
                            <div class="select2-result-repository__avatar">
                                <img src="/img/profiles/avatar.png">
                            </div>
                            <div class="select2-result-repository__meta">
                                <div class="select2-result-repository__title">` + (employee.id_card == null ? 'No ID Card' : employee.id_card) + ` | ` + employee.employee_name_kh + `</div>
                                <div class="select2-result-repository__description">` + employee.employee_name_latin + `</div>
                                <div class="select2-result-repository__statistics">
                                    <div class="select2-result-repository__forks">
                                        <i class="fa fa-bank"></i> ` + employee.department_code + `
                                    </div>
                                    <div class="select2-result-repository__stargazers">
                                        <i class="fa fa-venus-mars"></i> ` + employee.gender_code + `
                                    </div>
                                </div>
                            </div>
                            <span class="lecturer_id hidden">` + employee.employee_id + `</span>
                        </div>
                    </li>
                `;
			});
			$('#employee-viewer').html(employee_template);
		} else {
			$('#employee-viewer').html('<h1>NO EMPLOYEES</h1>');
		}
	})
}

function assign_lecturer_to_course_program() {
	$(document).on('click', 'li.select2-results__option', function () {
		let slot_id = $('.course-program-selected').find('.slot-id').text();
		if (slot_id !== '') {
			let lecturer_id = $(this).find('.lecturer_id').text();
			axios.post('/admin/schedule/timetables/assign_lecturer_to_course_program', {
				slot_id: slot_id,
				lecturer_id: lecturer_id
			}).then(response => {
				get_course_programs();
				notify('info', response.data.message, 'Assign Lecturer');
			})
		}
		let timetable_slot_id = $('.side-course.course-selected').attr('id');
		if (timetable_slot_id !== '') {
			let lecturer_id = $(this).find('.lecturer_id').text();
			axios.post('/admin/schedule/timetables/assign_lecturer_to_timetable_slot', {
				timetable_slot_id: timetable_slot_id,
				lecturer_id: lecturer_id
			}).then(response => {
				get_course_programs();
				get_timetable_slots();
				notify('info', response.data.message, 'Assign Lecturer');
			})
		}
	})
}


function reset() {
	console.log($('#options-filter').formData())
}

function get_timetable_group(query = null) {
    axios.post('/admin/schedule/timetables/search_timetable_groups', {
        query: query
    }).then(response => {
        if (response.data.data.length > 0) {
            let group_template = '';
            response.data.data.forEach((group) => {
            	group_template += `<div class="col-md-2 timetable_group">`+ group.code +`</div>`;
            });
            $('.timetable_group_width').html(group_template);
        } else {
            $('.timetable_group_width').html('<h1 style="text-align: center">NO GROUPS</h1>');
        }
    })
}