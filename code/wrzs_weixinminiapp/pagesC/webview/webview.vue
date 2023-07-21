<template>
	<view>
		<web-view :src="url"></web-view>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				url:'http://wmm.weishequ.com'
			};
		}
	}
</script>

<style lang="scss">

</style>
