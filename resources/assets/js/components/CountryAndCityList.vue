<template>

</template>

<script>
export default {
	props: {
		countries: {
			type: Array,
			required: true,
		},
		cities: {
			required: true,
		},
	},
	data() {
		return {
			country: '',
			city: '',
		}
	},
	computed: {
		cities() {
			if (!this.country) {
				return [];
			}
			return this.cities.filter(item => {
				return item.city_code == this.city;
			})
		}
	}
};
</script>
