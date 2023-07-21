<template>
	<view class="content">
		<template v-if="list.length">
			<view class="item" v-for="item in list" :key="item.cabinet_id" @click="pageJump(item.cabinet_id)">
				<view class="flex">
					<view class="left">
						<view class="h4">{{ item.store ? item.store.store_name : '' }}</view>
						<view class="p">{{ item.cabinet_name }}</view>
					</view>
					<view class="right">
						<button type="primary" @click.stop="fastReplenishment(item.cabinet_id)">一键补货</button>
					</view>
				</view>
			</view>
			<view class="append-more-text">{{ appendMoreText }}</view>
		</template>
		<NoData v-else>暂无售货柜</NoData>
	</view>
</template>

<script>
	import NoData from '@/components/NoData.vue'
	
	export default {
		components: {
			NoData
		},
		data() {
			return {
				page: 1,
				limit: 20,
				list: [],
				appendMoreText: '正在加载更多'
			}
		},
		methods: {
			// 一键补货
			async fastReplenishment(cabinet_id) {
				uni.showLoading({
					title: '请稍后...',
					mask: true
				})
				await this.$apiRequest({
					url: '/cabinet.Goods/replenishs',
					data: {
						cabinet_id
					}
				})
				uni.hideLoading()
				uni.showToast({
					title: '柜门已打开',
					icon: 'success'
				})
			},
			// 页面跳转
			pageJump(cabinet_id) {
				uni.navigateTo({
					url: `/pages/shg/shg?cabinet_id=${ cabinet_id }`
				})
			},
			// 获取售货柜列表
			async getSHGList() {
				const data = await this.$apiRequest({
					url: '/cabinet.Cabinet/list',
					data: {
						page: this.page,
						limit: this.limit
					}
				})
				if (data.list.length) {
					data.list.forEach(item => {
						this.list.push(item)
					})
					this.appendMoreText = '上滑加载更多'
					if (this.list.length < this.limit) {
						this.appendMoreText = '没有更多数据了'
					}
				} else {
					this.appendMoreText = '没有更多数据了'
				}
				uni.stopPullDownRefresh()
			}
		},
		onReachBottom() {
			if (this.appendMoreText !== '上滑加载更多') {
				return
			}
			this.page += 1
			this.appendMoreText = '正在加载中...'
			this.getSHGList()
		},
		onPullDownRefresh() {
			this.page = 1
			this.list = []
			this.getSHGList()
		},
		mounted() {
			this.getSHGList()
		}
	}
</script>

<style lang="scss">
	@import 'index.scss';
</style>
