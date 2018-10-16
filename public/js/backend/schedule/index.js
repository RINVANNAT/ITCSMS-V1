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
	data () {
		return {
			coursePrograms: null
		}
	},
	methods: {
		getCoursePrograms () {
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
	mounted () {
		this.getCoursePrograms()
	}
})

new Vue({
	el: '.app',
	data () {
		return {
			message: []
		}
	},
	methods: {
		reset () {
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
				} {
					notify('error', 'Reset', 'Error')
				}
			})
		},
		onCLickAddGroup (group) {
			toggleLoading(true)
			var slot_id = $('.course-program-selected').find('.slot-id').text();
			group = JSON.parse(group)
			axios.post('/admin/schedule/group/assign-group', {
				group_id: group.id,
				slot_id: parseInt(slot_id)
			}).then((response) => {
				if (response.data.code === 1) {
					notify('info', 'The group was added', 'Assgin Group')
					var newGroup = '<span class="bg-info badge">'+ group.code +'</span>'
					$('.course-program-selected').find('.list-groups').append(newGroup)
				}
				toggleLoading(false)
			}).catch((error) => {
				console.log(error)
				toggleLoading(false)
			})
		},
		removeGroupFromCourseProgram (group) {
			console.log(group)
		}
	}
})