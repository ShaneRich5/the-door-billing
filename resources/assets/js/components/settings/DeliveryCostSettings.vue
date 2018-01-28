<template>
<div>
	<div class="heading">
		<p>Base Delivery Cost</p>
	</div>
	<form>
		<div class="form-group">
			<input type="text" class="form-control" id="printer-id" placeholder="$30.00" v-model="cost">
		</div>
	</form>
</div>
</template>

<script>
export default {
	props: {
		deliveryCost: {
			type: Number,
			default: 0,
		},
	},
	data() {
		return {
			cost: this.deliveryCost,
		};
	},
	methods: {
		saveNewId() {
			axios.post('/settings/delivery-cost', {
				'delivery_cost': this.cost,
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

.heading {
	display: flex;
}

.heading p {
	  display: inline-block;
    max-width: 100%;
    margin-bottom: 5px;
    font-weight: bold;
}

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