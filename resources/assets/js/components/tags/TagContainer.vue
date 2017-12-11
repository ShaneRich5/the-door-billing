<template>
	<div class="container">
		<div v-if="showForm" class="row" style="margin-bottom: 15px;">
			<div class="col-md-8 col-md-offset-2">
				<tag-form
					v-on:submit="createTag"
					v-on:cancelled="showForm=!showForm"
				></tag-form>
			</div>
		</div>
		<div v-if="!showForm" class="row" style="margin-bottom: 15px;">
			<div class="col-md-8 col-md-offset-2">
				<button
					class="btn btn-default"
					@click="showForm=!showForm"
				>Create Tag</button>
			</div>
		</div>
		<div class="row">
			<div class="col-md-8 col-md-offset-2">
				<tag-list
					:tags="tags"
				></tag-list>
			</div>
		</div>
	</div>
</template>

<script>
export default {
	props: {
		initialTags: {
			type: Array,
			default() {
				return [];
			},
		},
	},
	data() {
		return {
			showForm: false,
			tags: this.initialTags,
		};
	},
	methods: {
		createTag(name) {
			const url = route('tags.store');
			axios.post(url, { name }).then(response => {
				console.log(response.data);
			}, console.error);
		},
	},
}
</script>