<template>
	<div>
		<div class="heading">
			<p>Zip Code</p><p>Cost</p>
		</div>
		<template v-for="zipCodeCost in zipCodeCosts">
			<zip-code-cost-form
				v-bind:zip-code-cost="zipCodeCost"
				v-bind:key="zipCodeCost.id"
				v-on:saved="handleSave"
			></zip-code-cost-form>
		</template>
		<zip-code-cost-form v-on:saved="handleSave"></zip-code-cost-form>
	</div>
</template>

<script>
export default {
	created() {
		axios.get('zip-code-costs').then(response => {
			const { zipCodeCosts } = response.data;
			this.zipCodeCosts = zipCodeCosts.map(i => window.humps.camelizeKeys(i));
			console.log('resonse: ' + response.data);
		}, console.error);
	},
	data() {
		return {
			zipCodeCosts: [],
		};
	},
	methods: {
		handleSave(newZipCode) {
			const index = this.zipCodeCosts.findIndex(zip => zip.zipCode === newZipCode);
			console.log('newZipCode', newZipCode, 'index', index);
			if (index == -1) {
				this.zipCodeCosts.push(newZipCode);
			} else {
				this.zipCodeCosts[index] = newZipCode;
			}
		}
	},
}
</script>

<style>
.heading {
	display: flex;
}

.heading p {
	  display: inline-block;
    max-width: 100%;
		width: 46%;
    margin-bottom: 5px;
    font-weight: bold;
}

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