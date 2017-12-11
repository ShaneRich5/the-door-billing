<template>

</template>

<script>
export default{
	props: {
		initialMenuItems: {
			type: Array,
			default() {
				return [];
			},
		},
	},
	data() {
		return {
			menuItems: this.initialMenuItems ? this.initialMenuItems : [],
		};
	},
}
</script>