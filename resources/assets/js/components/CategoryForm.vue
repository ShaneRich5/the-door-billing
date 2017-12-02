<template>
	<form v-on:submit.prevent="onSubmit">
		<div class="form-group">
			<label for="name">Name</label>
			<input v-model="name" type="text" class="form-control" id="name" placeholder="Enter name" aria-label="Name">
			<small id="emailHelp" class="form-text text-muted">{{ categoryUrl.url() }}/{{ slug }}</small>
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
			default() {
				return false;
			},
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
			const url = shouldUpdate ? route('categories.update') : route('categories.store');
			const method = shouldUpdate ? 'put' : 'post';
			const data = { name: this.name, description: this.description };
			axios({ method, url, data }).then((response) => {
				window.location.href = this.categoryUrl;
			}, console.error);
		},
	},
}
</script>