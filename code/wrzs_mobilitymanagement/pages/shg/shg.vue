<template>
	<view class="content">
		<view class="row">
			<view class="col" v-for="item in list" :key="item.goods_id">
				<view class="item" @click="replenishment(item.goods_id)">
					<view class="img-box">
						<view :class="['tag', (item.lock_status === 1 ? 'open' : item.lock_status === 0 ? 'close' : '')]">{{ item.lock_status === 1 ? '柜门已开' : item.lock_status === 0 ? '柜门已关' : '' }}</view>
						<image :src="baseUrl + item.goods_image" mode="aspectFill"></image>
					</view>
					<view class="caption">{{ item.goods_name }}</view>
					<view class="foot">
						<view class="left">￥{{ item.goods_price }}</view>
						<view class="right" v-if="item.stock">库存x{{ item.stock }}</view>
					</view>
					<image class="empty" v-if="!item.stock" src="/static/imgs/ysx.png" mode="aspectFill"></image>
				</view>
			</view>
		</view>
		<view class="append-more-text">{{ appendMoreText }}</view>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				baseUrl: this.$baseUrl,
				page: 1,
				limit: 20,
				list: [],
				appendMoreText: '上滑加载更多',
				cabinet_id: ''
			}
		},
		methods: {
			// 补货
			replenishment(goods_id) {
				uni.showModal({
					title: '温馨提示',
					content: '是否进行补货?',
					success: async (btn) => {
						if (btn.confirm) {
							uni.showLoading({
								title: '请稍后...',
								mask: true
							})
							await this.$apiRequest({
								url: '/cabinet.Goods/replenish',
								data: {
									goods_id
								}
							})
							uni.hideLoading()
							uni.showToast({
								title: '柜门已开',
								icon: 'success'
							})
							this.page = 1
							this.list = []
							this.getLatticeList()
						}
					}
				})
			},
			// 获取商品格列表
			async getLatticeList() {
				const data = await this.$apiRequest({
					url: '/cabinet.Goods/list',
					data: {
						cabinet_id: this.cabinet_id
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
			}
		},
		onLoad(options) {
			this.cabinet_id = options.cabinet_id
			this.getLatticeList()
		}
	}
</script>

<style lang="scss">
	@import 'index.scss';
</style>
