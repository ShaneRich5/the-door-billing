<template>
	<form v-on:submit.prevent>
		<div class="form-group">
			<input type="text" class="form-control" placeholder="Zip Code" v-model="cache.zipCode">
		</div>
		<div class="form-group">
			<input type="text" class="form-control" placeholder="Price" v-model="cache.cost">
		</div>
		<button
			v-on:click="saveChanges"
			class="btn btn-primary save-btn"
		>Save</button>
		<span
			class="glyphicon glyphicon-remove"
			aria-hidden="true"></span>
	</form>
</template>

<script>
export default {
	props: {
		zipCodeCost: {
			type: Object,
			default() {
				return {};
			},
		},
	},
	data() {
		return {
			cache: this.zipCodeCost,
		};
	},
	methods: {
		deleteZip() {
			console.log('should delete if id is present'); // TODO
		},
		saveChanges() {
			const url = window.route('zip-code-costs.store');
			axios.post(url, {
				'zip_code': this.cache.zipCode,
				'cost': +this.cache.cost,
			}).then(response => {
				const { zipCodeCost } = window.humps.camelizeKeys(response.data);

				if (zipCodeCost !== undefined) {
					if (Object.keys(this.zipCodeCost).length == 0 || this.cache.id == undefined) {
						console.log('zipC code cost', this.zipCodeCost);
						this.cache = {};
					}
					this.$emit('saved', zipCodeCost);
					console.log('saved emitted', zipCodeCost);
				}
			}, console.error);
		},
	},
}
</script>

<style>
form {
	display: flex;
	justify-content: space-between;
	align-items: baseline;
}

.form-group {
	width: 100%;
  margin-right: 10px;
}
</style>