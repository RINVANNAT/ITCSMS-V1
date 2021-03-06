Vue.component('course-program-item', {
	props: {
		courseProgram: {
			type: Object,
			default: null
		}
	},
	template: `
		<li class="course-item" v-if="courseProgram !== null">
			<span class="handle ui-sortable-handle">
				<i class="fa fa-ellipsis-v"></i>
				<i class="fa fa-ellipsis-v"></i>
			</span>
			<span class="text course-name">{{ courseProgram.course_name }}</span>
			<span style="margin-left: 28px;" :class="'teacher_name bg-danger ' + (courseProgram.teacher_name !== null ? '' : 'badge')">
				{{ (courseProgram.teacher_name !== null ? courseProgram.teacher_name : 'Unsigned') }}
			</span><br/>
			<span style="margin-left: 28px;" class="course-type">TP : </span>
			<span class="times"> {{ courseProgram.remaining }} H</span>
			<span class="hidden lecturer-id">{{ courseProgram.lecturer_id }}</span>
			<span class="text course_program_id" style="display: none;">{{ courseProgram.course_program_id}}</span>
		</li>
	`
})


Vue.component('course-program-wrapper', {
	template: `
		<ul class="coursess todo-list" v-if="coursePrograms !== null && coursePrograms.length > 0">
			<course-program-item v-for="(eachCourse, key) in coursePrograms"
								:courseProgram="eachCourse"
			                    :key="key"></course-program-item>
		</ul>
	`,
	data() {
		return {
			coursePrograms: null
		}
	},
	methods: {
		getCoursePrograms() {
			axios.post('/admin/schedule/timetables/get_course_programs', {
				...$('#options-filter').serialize()
			}).then((response) => {
				this.coursePrograms = [
					{
						course_name: 'AI',
						teacher_name: 'Mr. YOU Vandy',
						td: 100,
						tp: 100,
						course: 100,
						remaining: 100,
						lecturer_id: 1222,
						course_program_id: 1222
					}
				]
			})
		}
	},
	mounted() {
		this.getCoursePrograms()
	}
})


Vue.component('group-wrapper', {
	template: `
		<div class="timetable_group_width" v-if="groups !== null">
			<div class="col-md-2 timetable_group"
				v-for="(eachGroup, key) in groups"
				 @click="onCLickAddGroup(eachGroup)">
				 <span v-if="eachGroup.parent_id !== null"> {{eachGroup.parent.code}} | </span> {{ eachGroup.code }}
			</div>
		</div>
	`,
	props: {
		groups: {
			type: Array,
			default: null
		}
	},
	methods: {
		onCLickAddGroup(group) {
			toggleLoading(true)
			var api = '/admin/schedule/group/assign-group'
			var data = {}
			var slot_id = parseInt($('.course-program-selected').find('.slot-id').text())
			var timetable_slot_id = parseInt($('.course-selected').attr('id'))
			
			if (parseInt(timetable_slot_id) > 0) {
				api = '/admin/schedule/group/assign-group-to-timetable-slot'
				data.timetable_slot_id = parseInt(timetable_slot_id)
				data.timetable_group_id = group.id
			} else {
				data.slot_id = slot_id
				data.group_id = group.id
			}
			
			axios.post(api, data).then((response) => {
				if (response.data.code === 1) {
					notify('info', 'The group was added', 'Assgin Group')
					get_course_programs()
					get_timetable()
					get_timetable_slots()
				}
				toggleLoading(false)
			}).catch((error) => {
				console.log(error)
				toggleLoading(false)
			})
		}
	}
})

// register globally
Vue.component('multiselect', window.VueMultiselect.default)

var app = new Vue({
	el: '.app',
	data() {
		return {
			message: [],
			groups: [],
			groupsSelected: [],
			// assign room and lecturer
			newGroupRoomLecturer: {
				groups: [],
				room: null,
				lecturer: null
			},
			groupRoomLecturers: [],
			roomOptions: [],
			employees: [],
			timetable_slot_id: null
		}
	},
	methods: {
		addItem() {
			var newItem = this.newGroupRoomLecturer
			if (newItem.groups.length > 0) {
				newItem.groups.forEach((eachGroup) => {
					var eachItem = {
						group: eachGroup,
						lecturer: newItem.lecturer,
						room: newItem.room
					}
					this.groupRoomLecturers.push(eachItem)
				})
			}
			this.newGroupRoomLecturer = {
				groups: [],
				room: null,
				lecturer: null
			}
		},
		removeItem(item) {
			this.groupRoomLecturers.splice(this.groupRoomLecturers.indexOf(item), 1)
		},
		reset() {
			axios.post('/admin/schedule/timetables/reset', {
				academic_year_id: $('select[name=academicYear]').val(),
				department_id: $('select[name=department]').val(),
				department_option_id: $('select[name=option]').val(),
				degree_id: $('select[name=degree]').val(),
				grade_id: $('select[name=grade]').val(),
				group_id: $('select[name=group]').val(),
				semester_id: $('select[name=semester]').val(),
				week_id: $('select[name=weekly]').val()
			}).then((response) => {
				if (response.data.code == 1) {
					get_timetable_slots()
					get_timetable()
					drag_course_session()
				}
				{
					notify('error', 'Reset', 'Error')
				}
			})
		},
		getGroups() {
			axios.post('/admin/schedule/timetables/get_timetable_groups').then((response) => {
				if (response.data.code === 1) {
					this.groups = response.data.data
				} else {
					notify('error', 'Error Get Timetable Group', 'Error')
				}
			})
		},
		getRooms() {
			axios.post('/admin/schedule/room/get-rooms').then((response) => {
				if (response.data.code === 1) {
					this.roomOptions = response.data.data
				}
			})
		},
		getEmployees() {
			axios.post('/admin/schedule/group/get-employees').then((response) => {
				if (response.data.code === 1) {
					this.employees = response.data.data
				} else {
					notify('error', 'Error Get Timetable Group', 'Error')
				}
			})
		},
		storeTimetableGroup() {
			axios.post('/admin/schedule/timetables/store_new_group', {
				parent_id: $('select[name=timetable_group_parent_id]').val(),
				name: $('input[name=timetable_group_name]').val()
			}).then((response) => {
				if (response.data.code === 1) {
					get_course_programs()
					get_timetable_slots()
					get_timetable()
					drag_course_session()
					$('#add-new-group').modal('hide');
					
					var found = ''
					this.groups.forEach((eachGroup) => {
						if (eachGroup.id === response.data.data.id) {
							found = eachGroup
						}
					})
					
					if (found === '') {
						this.groups.push(response.data.data)
					}
					
					notify('info', 'New Group successfully created.', 'New Group')
				} else {
					$('.error-message').html(response.data.message.name).css('color', 'red')
					$('.error-message').siblings('input').css('border-color', 'red')
					notify('error', 'Create New Group', response.data.message.name)
				}
			})
		},
		exportCourseProgram() {
			toggleLoading(true);
			axios.post('/admin/schedule/timetables/export_course_program', {
				academic_year_id: $('select[name=academicYear]').val(),
				department_id: $('select[name=department]').val(),
				degree_id: $('select[name=degree]').val(),
				option_id: $('select[name=option]').val(),
				grade_id: $('select[name=grade]').val(),
				semester_id: $('select[name=semester]').val()
			}).then(function (response) {
				if (response.data.code === 1) {
					get_course_programs()
					$('#choose-timetable-group').modal('hide');
					$('.all-groups').prop('checked', false);
					$('.timetable-group-course input').prop('checked', false);
					notify('info', 'There are ' + response.data.data + ' programs exported!', 'Export Course Programs')
				} else {
					notify('info', response.data.message, 'Export Course Programs')
				}
				toggleLoading(false);
			}).catch(function (error) {
				notify('error', 'Slots was not exported', 'Export Courses');
			})
		},
		getGroupsByTimetableSlot(timetable_slot_id) {
			this.timetable_slot_id = timetable_slot_id
			axios.post('/admin/schedule/group/get-group-by-timetable-slot', {
				timetable_slot_id: timetable_slot_id
			}).then((response) => {
				if (response.data.code === 1) {
					this.groupRoomLecturers = []
					this.groupRoomLecturers = response.data.data
				}
			})
		},
		onClickAssignRoomAndLecturerToTimetableSlot() {
			toggleLoading(true)
			axios.post('/admin/schedule/timetable-slot/assign-room-and-lecturer-to-group', {
				timetable_slot_id: this.timetable_slot_id,
				data: this.groupRoomLecturers
			}).then((response) => {
				if (response.data.code === 1) {
					notify('success', 'The group has been assign.', 'Successed')
					get_course_programs()
					get_timetable_slots()
					$('#assign-lecturer-room').modal('toggle')
				} else {
					notify('error', response.data.message, 'Error')
				}
				toggleLoading(false)
			}).catch((error) => {
				toggleLoading(false)
				notify('error', error, 'Error')
			})
		},
        getCalendarClassses() {
            axios.post('calendars/get_classes').then((response) => {
                if (response.data.code === 1) {

                }
                console.log(response.data)
            })
                .catch((error) => {
                    console.log(error)
                })
        }
	},
	mounted() {
		this.getGroups()
		this.getRooms()
		this.getEmployees()
	}
})

$(function () {
	$(document).on('click', '.remove-group-from-course-program', function (e) {
		var dom = $(this)
		var timetable_group_id = parseInt(dom.find('.group-id').text())
		var slot_id = parseInt(dom.parent().parent().find('.slot-id').text())
		swal({
			title: 'Are you sure?',
			text: "You won't be able to revert this!",
			type: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Yes, delete it!'
		}).then((result) => {
			axios.post('/admin/schedule/group/remove-group', {
				timetable_group_id: timetable_group_id,
				slot_id: slot_id
			}).then((response) => {
				if (response.data.code === 1) {
					swal(
						'Group Removed!',
						'The group has been deleted.',
						'success'
					)
					get_course_programs()
					get_timetable_slots()
				} else {
					notify('error', response.data.message, 'Remove Group!')
				}
			}).catch((error) => {
				notify('error', error, 'Remove Group!')
			})
			
		})
	})
	
	$(document).on('click', '.remove-group-from-timetable-slot', function (e) {
		var dom = $(this)
		var timetable_group_id = parseInt(dom.find('.group-id').text())
		var timetable_slot_id = parseInt(dom.find('.timetable-slot-id').text())
		swal({
			title: 'Are you sure?',
			text: "You won't be able to revert this!",
			type: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Yes, delete it!'
		}).then((result) => {
			axios.post('/admin/schedule/group/remove-group-from-timetable-slot', {
				timetable_group_id: timetable_group_id,
				timetable_slot_id: timetable_slot_id
			}).then((response) => {
				if (response.data.code === 1) {
					swal(
						'Group Removed!',
						'The group has been deleted.',
						'success'
					)
					get_course_programs()
					get_timetable_slots()
				} else {
					notify('error', response.data.message, 'Remove Group!')
				}
			}).catch((error) => {
				notify('error', error, 'Remove Group!')
			})
			
		})
	})
	
	$(document).on('click', '.btn-toggle-modal-assign-lecturer-room', function () {
		var timetable_slot_id = parseInt($(this).parent().parent().prev().find('.timetable-slot-id').text())
		if (timetable_slot_id > 0) {
			app.getGroupsByTimetableSlot(timetable_slot_id)
		} else {
			notify('error', 'The could not found timetable slot id.', 'Get Group')
		}
		
		$('#assign-lecturer-room').modal('toggle')
	})
})