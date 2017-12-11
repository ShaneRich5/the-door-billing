<template>
	<form v-on:submit.prevent="onSubmit">

	</form>
</template>

<script>
export default {
	props: {
		shouldUpdate: {
			type: Boolean,
			default: () => false,
		},
		menuItem: {
			type: Object,
			default: () => {},
		},
	},
	data() {
		return {
			name: 'name' in this.menuItem ? this.menuItem.name : '',
			description: 'description' in this.menuItem ? this.menuItem.description : '',
			menuItemsUrl: window.route('menu-items.index'),
		};
	},
}
</script>