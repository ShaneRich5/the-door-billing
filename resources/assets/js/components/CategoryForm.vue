<template>
	<form v-on:submit.prevent="onSubmit">
		<div class="form-group">
			<label for="name">Name</label>
			<input v-model="name" type="text" class="form-control" id="name" placeholder="Enter name" aria-label="Name">
			<small id="emailHelp" class="form-text text-muted">{{ categoryUrl.url() }}/{{ slug }}</small>
		</div>
		<div class="form-group">
			<label for="catering-maximum">Maximum catering choices</label>
			<input v-model="cateringMaximum" type="number" class="form-control"
				id="catering-maximum" placeholder="Enter a number 0 or greater" aria-label="Maximum catering">
		</div>
		<div class="form-group">
			<label for="category-description">Description</label>
			<textarea v-model="description" class="form-control" id="category-description" rows="3"></textarea>
		</div>
		<button type="submit" class="btn btn-primary">{{ shouldUpdate ? 'Update' : 'Submit' }}</button>
		<a :href="categoryUrl" class="btn btn-danger">Cancel</a>
	</form>
</template>

<script>
export default {
	props: {
		shouldUpdate: {
			type: Boolean,
			default: () => false,
		},
		category: {
			type: Object,
			default() {
				return {};
			},
		},
	},
	data() {
		return {
			name: 'name' in this.category ? this.category.name : '',
			cateringMaximum: 'catering_maximum' in this.category ? this.category['catering_maximum'] : 0,
			description: 'description' in this.category ? this.category.description : '',
			categoryUrl: window.route('categories.index'),
		};
	},
	computed: {
		slug() {
			return this.name.toLowerCase().replace(' ', '-');
		},
	},
	methods: {
		onSubmit() {
			const url = this.shouldUpdate ? route('categories.update', this.category.id) : route('categories.store');
			const method = this.shouldUpdate ? 'put' : 'post';
			const data = { name: this.name, description: this.description, cateringMaximum: this.cateringMaximum };
			axios({ method, url, data }).then((response) => {
				console.log(response.data);
				window.location.href = this.categoryUrl;
			}, console.error);
		},
	},
}
</script>