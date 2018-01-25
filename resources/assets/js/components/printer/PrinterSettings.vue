<template>
	<form>
		<div class="form-group">
			<label for="printer-id">Printer ID</label>
			<transition name="fade">
				<button 
					v-show="printerId !== id" 
					v-on:click="saveNewId"
					class="btn btn-primary save-btn"
				>Save</button>
			</transition>
			<input type="text" class="form-control" id="printer-id" placeholder="Printer ID" v-model="printerId">
		</div>
	</form>
</template>

<script>
export default {
	props: {
		id: {
			type: String,
			default: '',
		},
	},
	data() {
		return {
			printerId: this.id,
		};
	},
	methods: {
		saveNewId() {
			axios.post('/settings/printer', {
				'printer_id': this.printerId,
			}).then(response => {
				console.log('response: ' + response.data);
			}, console.error);
		},
	},
}
</script>

<style>
.save-btn {
	float: right;
	margin-top: -11px;
}

.fade-enter-active, .fade-leave-active {
  transition: opacity .5s;
}
.fade-enter, .fade-leave-to /* .fade-leave-active below version 2.1.8 */ {
  opacity: 0;
}
</style>