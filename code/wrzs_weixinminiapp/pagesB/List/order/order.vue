<template>
	<view class="content">
		<Topbar title="我的订单"></Topbar>
		<view class="tabs-nav">
			<view :class="['item', swiper.current === 0 ? 'active' : '']" @click="tabsChange(0)">待支付</view>
			<view :class="['item', swiper.current === 1 ? 'active' : '']" @click="tabsChange(1)">已预约</view>
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
										<view class="name">{{ item2.store_name }}</view>
										<view class="dzf" v-if="swiper.current === 0">
											<text class="small">￥</text>
											<text class="money">{{ item2.order_price }}</text>
											<button type="primary" @click="gotoPay(item2.order_id)">去支付</button>
										</view>
										<!-- 已预约，未开始 -->
										<view class="wks" v-if="swiper.current === 1 && item2.order_status === 1">
											<UniCountDown @timeup="refreshList" backgroundColor="transparent"
												splitorColor="#429435" :showDay="item2.times.hours >= 24 ? true : false" color="#429435"
												:hour="item2.times.hours" :minute="item2.times.minutes"
												:second="item2.times.seconds"></UniCountDown>后开始
										</view>
										<!-- 已预约，进行中 -->
										<view class="yyd" v-if="swiper.current === 1 && item2.order_status === 2">
											<view class="left">
												<UniCountDown @timeup="refreshList" backgroundColor="transparent"
													splitorColor="#FF9813" :showDay="item2.times.hours >= 24 ? true : false" color="#FF9813"
													:hour="item2.times.hours" :minute="item2.times.minutes"
													:second="item2.times.seconds"></UniCountDown>后到期
											</view>
											<button type="primary" @click="renew(item2.order_id)">续房</button>
										</view>
										<view class="normal" v-if="swiper.current === 2">已完成</view>
										<view class="normal" v-if="swiper.current === 3">已取消</view>
									</view>
									<view class="item-p">
										<view class="p">
											<label for="">订<text class="half"></text>单<text
													class="half"></text>号：</label>
											<text>{{ item2.order_sn }}</text>
										</view>
										<view class="p">
											<label for="">预定房间：</label>
											<text>{{ item2.room_name }}</text>
										</view>
										<view class="p">
											<label for="">预定时间：</label>
											{{ $dateFormatter(+(item2.start_time + '000'), 'mm月dd日 hh:mm') }}
											<text class="half"></text>至<text class="half"></text>
											{{ $dateFormatter(+(item2.end_time + '000'), 'mm月dd日 hh:mm') }}
										</view>
										<view class="p">
											<label for="">预定时长：</label>
											<text>{{ $subtTime(+(item2.start_time + '000'), +(item2.end_time + '000')).fmt }}</text>
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
			// 续费
			renew(orderID) {
				uni.navigateTo({
					url: `/pagesB/renew/renew?orderID=${ orderID }`
				})
			},
			// 刷新列表
			refreshList() {
				this.swiper.list = [{
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
				this.getOrderList()
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
					url: '/user.Order/list',
					data: {
						page: curt.page,
						limit: curt.limit,
						type: this.swiper.current + 1
					}
				}).then(data => {
					if (data.list.length) {
						data.list.forEach(item => {
							// 如果是已预约，未开始
							if (this.swiper.current === 1 && item.order_status === 1) {
								item.times = this.$secondToTimeStr(item.syKs_time)
							}
							// 如果是已预约，已开始
							if (this.swiper.current === 1 && item.order_status === 2) {
								item.times = this.$secondToTimeStr(item.syJs_time)
							}
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
				}).catch(() => {})
				curt.pageLoading = false
			}
		},
		onLoad(options) {
			this.swiper.current = +options.current
			if (this.swiper.current === 0) {
				this.getOrderList()
			}
		}
	}
</script>

<style lang="scss">
	@import 'index.scss'
</style>
