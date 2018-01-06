<template>
	<form>
		<div class="form-group">
			<label for="printer-id">Printer ID</label>
			<input type="text" class="form-control" id="printer-id" placeholder="Printer ID" :value="printerId">
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
			printerId: this.currentPrinterId,
		};
	},
}
</script>