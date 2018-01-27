<template>
	<form>
		<div class="form-group">
			<input type="text" class="form-control" id="printer-id" placeholder="Printer ID" v-model="zipCodeCost.zipCode">
		</div>
		<div class="form-group">
			<input type="text" class="form-control" id="printer-id" placeholder="Printer ID" v-model="zipCodeCost.cost">
		</div>
		<button
			class="btn btn-primary save-btn"
			v-on:click="saveChanges"
		>Save</button>
	</form>
</template>

<script>
export default {
	props: {
		zipCodeCost: {
			type: Object,
			default() {
				return {
					zipCode: '',
					cost: 0,
				};
			},
		}
	},
	methods: {
		updateChanges() {
			this.$emit('save', this.zipCodeCost);
		},
	},
}
</script>
