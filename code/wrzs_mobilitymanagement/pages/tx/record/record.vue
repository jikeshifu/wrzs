<template>
	<view class="content">
		<template v-if="list.length">
			<view class="item" v-for="(item, index) in list" :key="index">
				<view class="flex">
					<view class="left">
						<view :class="['h4', (item.status === 0 ? 'warning' : item.status === 1 ? 'success' : item.status === 2 ? 'danger' : '')]">{{ item.status === 0 ? '审核中' : item.status === 1 ? '提现成功' : item.status === 2 ? '提现失败' : '' }}</view>
						<view class="p">{{ $dateFormatter(+(item.created_at + '000'), 'yyyy-mm-dd hh:mm:ss') }}</view>
					</view>
					<view class="right">{{ item.money }}元</view>
				</view>
				<view class="tip" v-if="item.status === 2">{{ item.remarks }}</view>
			</view>
			<view class="append-more-text">{{ appendMoreText }}</view>
		</template>
		<NoData v-else></NoData>
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
				list: [],
				page: 1,
				limit: 10,
				appendMoreText: '上滑加载更多'
			}
		},
		methods: {
			// 获取提现记录
			async getTxList() {
				uni.showLoading({
					title: '加载中...',
					mask: true
				})
				await this.$apiRequest({
					url: '/join.Wthdrawal/list',
					data: {
						page: this.page,
						limit: this.limit
					}
				}).then(data => {
					uni.hideLoading()
					if (data.list.length) {
						if (this.page !== 1) {
							data.list.forEach(item => {
								this.list.push(item)
							})
						} else {
							this.list = data.list
						}
						this.appendMoreText = '上滑加载更多'
						if (this.list.length < this.limit) {
							this.appendMoreText = '已全部加载'
						}
					} else {
						if (this.page === 1) {
							this.list = []
						}
						this.appendMoreText = '已全部加载'
					}
				})
			}
		},
		onReachBottom() {
			if (this.appendMoreText !== '上滑加载更多') {
				return
			}
			this.page += 1
			this.appendMoreText = '正在加载中...'
			this.getTxList()
		},
		mounted() {
			this.getTxList()
		}
	}
</script>

<style lang="scss">
	@import 'index.scss';
</style>
