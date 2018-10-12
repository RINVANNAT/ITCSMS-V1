<template>
	<div class="info-box">
		<span class="info-box-icon bg-aqua">
			<span class="room_name">{{ room.code }}-{{ room.name }}</span>
		</span>
		<div class="info-box-content">
			<span class="info-box-number">{{ room.room_type }}</span>
			<span class="info-box-number room_id hidden">117</span>
			<span class="info-box-text text-muted">{{ room.desk !== null ? room.desk : 'N/A' }} Desk</span>
			<span class="info-box-text text-muted">{{ room.chair !== null ? room.chair : 'N/A' }} Chair</span>
		</div>
	</div>
</template>

<script>
	export default {
		name: "card-room",
		props: {
			room: {
				type: Object
			}
		}
	}
</script>

<style scoped>

</style>