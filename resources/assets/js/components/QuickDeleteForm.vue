<template>
	<a :href="url" @click="deleteRecord">Delete</a>
</template>

<script>
export default {
	props: {
		url: {
			type: String,
			required: true,
		},
	},
	methods: {
		deleteRecord() {
			axios.delete(this.url)
				.then((response) => {
					const { url } = response.data;
					window.location.href = url;
				}, console.error);
		},
	},
}
</script>