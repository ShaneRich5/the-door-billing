<template>
	<div class="panel panel-default">
		<div class="panel-heading">Tags</div>
		<div class="list-group">
			<template v-if="tags.length > 0">
				<p v-for="tag in tags"
					class="list-group-item list-group-item-action">{{ tag.name }}</p>
			</template>
			<p v-else class="list-group-item">No tags available</p>
		</div>
	</div>
</template>

<script>
export default {
	props: {
		tags: {
			type: Array,
			default() {
				return [];
			},
		},
	},
}
</script>