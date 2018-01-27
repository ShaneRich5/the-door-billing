<template>
	<div>
		<div class="heading">
			<p for="printer-id">Zip Code</p><p>Cost</p>
		</div>
		<template v-for="zipCodeCost in zipCodeCosts">
			<zip-code-form :zip-code-cost="zipCodeCost"></zip-code-form>
		</template>
		<zip-code-form></zip-code-form>
	</div>
</template>

<script>
export default {
	created() {
		axios.get('zip-code-costs').then(response => {
			const { zipCodeCosts } = response;
			this.zipCodeCosts = zipCodeCosts;
		}, console.error);
	},
	data() {
		return {
			zipCodeCosts: [],
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