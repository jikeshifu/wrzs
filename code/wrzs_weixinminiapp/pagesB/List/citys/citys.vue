<template>
	<view class="content">
		<Topbar title="城市"></Topbar>
		<view class="checked">
			<view class="h4">当前选择城市</view>
			<view class="p">{{ city }}</view>
		</view>
		<view class="list">
			<view class="h4">已开通城市</view>
			<wLoading v-if="pageLoading"></wLoading>
			<scroll-view v-else scroll-y>
				<view class="col" v-for="(item, index) in list" :key="item.city_id">
					<view @click="checkCity(item)" class="item">{{ item.city_name }}</view>
				</view>
			</scroll-view>
		</view>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				pageLoading: true,
				city: '',
				list: []
			}
		},
		methods: {
			// 获取城市列表
			async getCitysList() {
				await this.$apiRequest({
					url: '/store.City/list'
				}).then(data => {
					this.list = data.list
				}).catch(() => {})
				this.pageLoading = false
			},
			// 选择城市
			checkCity(item) {
				const pages = getCurrentPages()
				const prevPage = pages[pages.length - 2]
				prevPage.$vm.citys = item
				uni.navigateBack({
					delta: 1
				})
			}
		},
		onLoad(options) {
			this.city = options.city
			this.getCitysList()
		}
	}
</script>

<style lang="scss">
	@import 'index.scss';
</style>
