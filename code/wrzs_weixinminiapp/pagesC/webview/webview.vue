<template>
	<view>
		<web-view :src="url"></web-view>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				url:'http://wmm.seeare.com'
			};
		}
	}
</script>

<style lang="scss">

</style>
