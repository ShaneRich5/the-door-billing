<template>
	<form v-on:submit.prevent="onSubmit">
		<div class="form-group">
			<label for="name">Name</label>
			<input v-model="name" type="text" class="form-control" id="name" placeholder="Enter name" aria-label="Name">
			<small id="emailHelp" class="form-text text-muted">{{ tagUrl.url() }}/{{ slug }}</small>
		</div>
		<button type="submit" class="btn btn-primary">Create</button>
		<a @click="$emit('cancelled')" class="btn btn-danger">Cancel</a>
	</form>
</template>

<script>
export default {
	data() {
		return {
			name: '',
			tagUrl: window.route('tags.index'),
		};
	},
	computed: {
		slug() {
			return this.name.toLowerCase().replace(' ', '-');
		},
	},
	methods: {
		onSubmit() {
			if (this.slug.length > 0) {
				this.$emit('submit', this.slug);
			}
		},
	},
}
</script>