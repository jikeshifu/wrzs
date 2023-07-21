<template>
	<view class="content">
		<Topbar title="搜索"></Topbar>
		<view class="search-box">
			<input type="text" v-model="searchTxt" placeholder="请输入要搜索的内容" placeholder-style="color:#c9c9c9;" @confirm="submit" confirm-type="search" focus>
			<button type="default" @click="submit">搜索</button>
		</view>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				searchTxt: ''
			}
		},
		methods: {
			submit() {
				const pages = getCurrentPages()
				const prevPage = pages[pages.length - 2]
				prevPage.$vm.searchTxt = this.searchTxt
				prevPage.$vm.swiper.list = []
				prevPage.$vm.swiper.page = 1
				prevPage.$vm.swiper.pageLoading = true
				prevPage.$vm.getGoodsList()
				uni.navigateBack({
					delta: 1
				})
			}
		},
		onLoad(options) {
			this.searchTxt = options.searchTxt
		}
	}
</script>

<style lang="scss">
	@import 'index.scss';
</style>
