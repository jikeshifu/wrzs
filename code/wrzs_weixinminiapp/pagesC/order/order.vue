<template>
	<view class="content">
		<Topbar title="商城订单"></Topbar>
		<view class="tabs-nav">
			<view :class="['item', swiper.current === 0 ? 'active' : '']" @click="tabsChange(0)">待支付</view>
			<view :class="['item', swiper.current === 1 ? 'active' : '']" @click="tabsChange(1)">进行中</view>
			<view :class="['item', swiper.current === 2 ? 'active' : '']" @click="tabsChange(2)">已完成</view>
			<view :class="['item', swiper.current === 3 ? 'active' : '']" @click="tabsChange(3)">已取消</view>
		</view>
		<view class="swiper">
			<swiper @change="swiperChange" :current="swiper.current">
				<swiper-item v-for="(item, index) in swiper.list" :key="index">
					<wLoading v-if="item.pageLoading"></wLoading>
					<template v-else>
						<template v-if="item.list.length">
							<scroll-view scroll-y @scrolltolower="appendMore">
								<view style="height:30upx"></view>
								<view class="item" v-for="item2 in item.list" :key="item2.order_id">
									<view class="top">
										<view class="left">{{ item2.order_sn }}</view>
										<view class="right">
											<view class="dzf" v-if="swiper.current === 0">
												<view><text>￥</text>{{ item2.order_price }}</view>
												<button type="primary" @click="jumpTopayPage(item2)">去支付</button>
											</view>
											<view class="normal" v-if="swiper.current === 1 && item2.order_status === 1">
												<text>￥{{ item2.order_price }}</text>未发货
											</view>
											<view class="qdsh" v-if="swiper.current === 1 && item2.order_status === 2">
												<text>已发货</text>
												<button type="primary" @click="confirmGet(item2)">确定收货</button>
											</view>
											<view class="normal" v-if="swiper.current === 2">已完成</view>
											<view class="normal" v-if="swiper.current === 3">已取消</view>
										</view>
									</view>
									<view class="goods-wrap" v-for="item3 in item2.orderGoods" :key="item3.id">
										<view class="inner">
											<image :src="baseUrl + item3.goods_info.goods_image" mode="aspectFill"></image>
											<view class="info">
												<view class="h4">{{ item3.goods_info.goods_name }}</view>
												<view class="bt">
													<view class="left">￥{{ item3.goods_info.goods_price }}</view>
													<view class="right">数量x{{ item3.goods_number }}</view>
												</view>
											</view>
										</view>
									</view>
								</view>
								<UniLoadMore :status="item.appendMoreStatus"></UniLoadMore>
							</scroll-view>
						</template>
						<template v-else>
							<NoData title="还没有订单"></NoData>
						</template>
					</template>
				</swiper-item>
			</swiper>
		</view>
	</view>
</template>

<script>
	import UniCountDown from '@/components/uni-countdown/uni-countdown.vue'

	export default {
		components: {
			UniCountDown
		},
		data() {
			return {
				swiper: {
					// 1 待支付，2 进行中，3 已完成， 4 已取消
					current: 0,
					list: [{
						pageLoading: true,
						appendMoreStatus: 'more',
						list: [],
						page: 1,
						limit: 10
					}, {
						pageLoading: true,
						appendMoreStatus: 'more',
						list: [],
						page: 1,
						limit: 10
					}, {
						pageLoading: true,
						appendMoreStatus: 'more',
						list: [],
						page: 1,
						limit: 10
					}, {
						pageLoading: true,
						appendMoreStatus: 'more',
						list: [],
						page: 1,
						limit: 10
					}]
				}
			}
		},
		methods: {
			// 确定收货
			async confirmGet(item) {
				uni.showLoading({
					title: '请稍后...',
					mask: true
				})
				await this.$apiRequest({
					url: '/order.Goods/takeDelivery',
					data: {
						order_id: item.order_id
					}
				})
				uni.hideLoading()
				uni.showToast({
					title: '已确认收货',
					icon: 'success'
				})
				const curt = this.swiper.list[this.swiper.current]
				curt.page = 1
				this.getOrderList()
				this.swiper.list[this.swiper.current + 1].page = 1
				this.swiper.list[this.swiper.current + 1].list = []
			},
			// 去支付
			gotoPay(orderID) {
				uni.navigateTo({
					url: `/pagesB/payOrder/payOrder2?orderID=${ orderID }`
				})
			},
			tabsChange(idx) {
				this.swiper.current = idx
			},
			swiperChange(e) {
				this.swiper.current = e.detail.current
				const curt = this.swiper.list[this.swiper.current]
				if (!curt.list.length) {
					curt.pageLoading = true
					this.getOrderList()
				}
			},
			// 滚动底部加载更多
			appendMore() {
				const curt = this.swiper.list[this.swiper.current]
				if (curt.appendMoreStatus !== 'more') {
					return
				}
				curt.page += 1
				curt.appendMoreStatus = 'loading'
				this.getOrderList()
			},
			// 获取订单列表
			async getOrderList() {
				const curt = this.swiper.list[this.swiper.current]
				await this.$apiRequest({
					url: '/order.Goods/list',
					data: {
						page: curt.page,
						limit: curt.limit,
						type: this.swiper.current + 1
					}
				}).then(data => {
					if (data.list.length) {
						data.list.forEach(item => {
							if (curt.page !== 1) {
								curt.list.push(item)
							}
						})
						if (curt.page === 1) {
							curt.list = data.list
						}
						curt.appendMoreStatus = 'more'
						if (curt.list.length < curt.limit) {
							curt.appendMoreStatus = 'noMore'
						}
					} else {
						if (curt.page === 1) {
							curt.list = []
						}
						curt.appendMoreStatus = 'noMore'
					}
				}).catch(() => {
					
				})
				curt.pageLoading = false
			},
			// 跳转支付页面
			jumpTopayPage(item) {
				let list = []
				item.orderGoods.forEach(item2 => {
					list.push({
						goods_id: item2.goods_info.goods_id,
						goods_image: item2.goods_info.goods_image,
						goods_price: item2.goods_info.goods_price,
						goods_number: item2.goods_number,
						goods_name: item2.goods_info.goods_name,
					})
				})
				uni.setStorageSync('goodsList', {
					number: item.orderGoods.length,
					list: list,
					totalMoney: item.order_price,
					orderID: item.order_id
				})
				uni.redirectTo({
					url: '/pagesC/payOrder/payOrder?fromPage=order'
				})
			}
		},
		onLoad(options) {
			if (options.current) {
				this.swiper.current = +options.current
			}
			if (this.swiper.current === 0) {
				this.getOrderList()
			}
		}
	}
</script>

<style lang="scss">
	@import 'index.scss'
</style>
