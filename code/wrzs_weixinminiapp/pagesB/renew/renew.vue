<template>
	<view class="content">
		<view class="fixed-topbar">
			<image :src="baseUrl + '/static/imgs/renew/bg.png'" mode="widthFix"></image>
			<view class="fixed-item">
				<view :style="{ height: menuButtonBoundingClientRect.top + 'px' }"></view>
				<view class="title" :style="{ height: menuButtonBoundingClientRect.top + 'px' }">
					<view @click="back" class="iconfont icon-left"></view>
					<view class="h1">续费</view>
				</view>
				<view :style="{ height: (menuButtonBoundingClientRect.top / 2) + 'px' }"></view>
			</view>
			<view class="center-cont">
				<view class="left">
					<text class="iconfont icon-clock"></text>剩余
				</view>
				<UniCountDown @timeup="timeout" backgroundColor="transparent" splitorColor="#fff" color="#fff"
					:showDay="false" :hour="orderInfo.times.hours" :minute="orderInfo.times.minutes"
					:second="orderInfo.times.seconds"></UniCountDown>
			</view>
		</view>
		<view class="slider-contain">
			<view class="tip">
				<text class="iconfont icon-tip"></text>{{ orderInfo.room_info.starting_time }}小时起续订
			</view>
			<view class="num-box">
				<view class="h4">{{ slider }}</view>
				<view class="p">小时</view>
			</view>
			<slider @change="sliderChange" @changing="sliderChange" block-size="25" :max="max" :min="orderInfo.room_info.starting_time" activeColor="#54AB60"
				backgroundColor="#eee" />
			<view class="foot">
				共<text class="warn">{{ slider }}</text>小时
				<text class="full"></text>合计费用
				<text class="small">￥</text>
				<text class="big">{{ totalMoney }}</text>
			</view>
		</view>
		<view class="pay-way">
			<view class="item t" @click="payChange('qb')">
				<view class="left">
					<text class="iconfont icon-qb"></text>钱包余额<text class="warn">(余额{{ myMoney }}元)</text>
				</view>
				<view class="right">
					<image v-if="payway === 'qb'" :src="baseUrl + '/static/imgs/payOrder/checked.png'" mode="aspectFill"></image>
					<image v-else :src="baseUrl + '/static/imgs/payOrder/check.png'" mode="aspectFill"></image>
				</view>
			</view>
			<view class="item b" @click="payChange('wxzf')">
				<view class="left">
					<text class="iconfont icon-wxzf"></text>微信支付
				</view>
				<view class="right">
					<image v-if="payway === 'wxzf'" :src="baseUrl + '/static/imgs/payOrder/checked.png'" mode="aspectFill"></image>
					<image v-else :src="baseUrl + '/static/imgs/payOrder/check.png'" mode="aspectFill"></image>
				</view>
			</view>
		</view>
		<view class="empty-fixed"></view>
		<view class="fixed-bottom-btn">
			<button type="primary" @click="paySubmit">立即支付</button>
		</view>
		<wLoading bgColor="#fff" v-if="pageLoading"></wLoading>
	</view>
</template>

<script>
	import UniCountDown from '@/components/uni-countdown/uni-countdown.vue'
	import wLoading from '@/components/w-loading.vue'
	const app = getApp()

	export default {
		computed: {
			totalMoney() {
				const total = this.$calc.multiply(this.orderInfo.room_info.room_price, this.slider, 2)
				if (this.myMoney < total) {
					this.payway = 'wxzf'
				}
				return total
			}
		},
		components: {
			UniCountDown,
			wLoading
		},
		data() {
			return {
				pageLoading: true,
				menuButtonBoundingClientRect: app.globalData.menuButtonBoundingClientRect,
				slider: 1,
				myMoney: 0,
				payway: 'qb',
				orderInfo: {
					room_info: {},
					times: {
						hours: 1
					}
				},
				max: 0
			}
		},
		methods: {
			// 时间到
			timeout() {
				const pages = getCurrentPages()
				const prevPages = pages[pages.length - 2]
				// 如果是订单列表页面过来的
				if (prevPages.route === 'pagesB/List/order/order') {
					prevPages.$vm.swiper.list[prevPages.$vm.swiper.current].page = 1
					prevPages.$vm.getOrderList()
					uni.navigateBack({
						delta: 1
					})
				} else {
					uni.navigateBack({
						delta: 1
					})
				}
			},
			// 立即支付
			async paySubmit() {
				uni.showLoading({
					title: '正在支付...',
					mask: true
				})
				const pages = getCurrentPages()
				const prevPages = pages[pages.length - 2]
				const data = await this.$apiRequest({
					url: '/order.Room/renew',
					data: {
						order_id: this.orderInfo.order_id,
						number: this.slider,
						room_id: this.orderInfo.room_info.room_id
					}
				})
				// 钱包支付
				if (this.payway === 'qb') {
					await this.$apiRequest({
						url: '/pay.Balance/index',
						data: {
							order_id: data.order_info.order_id
						}
					})
					uni.hideLoading()
					uni.showModal({
						title: '温馨提示',
						content: '续费成功',
						showCancel: false,
						complete() {
							// 如果是订单列表页面过来的
							if (prevPages.route === 'pagesB/List/order/order') {
								prevPages.$vm.swiper.list[prevPages.$vm.swiper.current].page = 1
								prevPages.$vm.getOrderList()
								uni.navigateBack({
									delta: 1
								})
							} else {
								uni.navigateBack({
									delta: 1
								})
							}
						}
					})
				} else { // 微信支付
					const data2 = await this.$apiRequest({
						url: '/pay.Wechat/index',
						data: {
							order_id: data.order_info.order_id
						}
					})
					uni.requestPayment({
						provider: 'wxpay',
						timeStamp: data2.pay.timeStamp,
						nonceStr: data2.pay.nonceStr,
						package: data2.pay.package,
						signType: 'MD5',
						paySign: data2.pay.paySign,
						success: () => {
							uni.showModal({
								title: '温馨提示',
								content: '支付成功',
								showCancel: false,
								complete() {
									// 如果是订单列表页面过来的
									if (prevPages.route === 'pagesB/List/order/order') {
										prevPages.$vm.swiper.list[prevPages.$vm.swiper.current]
											.page = 1
										prevPages.$vm.getOrderList()
										uni.navigateBack({
											delta: 1
										})
									} else {
										uni.navigateBack({
											delta: 1
										})
									}
								}
							})
						},
						fail: (res) => {
							this.$showMsg('支付失败')
							console.log('支付失败', res)
						},
						complete() {
							uni.hideLoading()
						}
					})
				}
			},
			// 支付切换
			payChange(type) {
				if (type === 'qb') {
					if (this.myMoney < this.totalMoney) {
						this.$showMsg('余额不足抵扣订单金额，请选择微信支付吧')
						return
					}
				}
				this.payway = type
			},
			sliderChange(e) {
				this.slider = e.detail.value
			},
			back() {
				uni.navigateBack({
					delta: 1
				})
			},
			// 获取订单信息
			async getOrderInfo(order_id) {
				await this.$apiRequest({
					url: '/user.Order/info',
					data: {
						order_id
					}
				}).then((data) => {
					this.orderInfo = data.info
					this.myMoney = data.money
					this.max = data.kx_number
					this.orderInfo.times = this.$secondToTimeStr(this.orderInfo.syJs_time)
					this.slider = this.orderInfo.room_info.starting_time
					this.pageLoading = false
				}).catch(_ => {
					uni.navigateBack({
						delta: 1
					})
				})
			}
		},
		onLoad(options) {
			this.getOrderInfo(options.orderID)
		}
	}
</script>

<style lang="scss">
	@import 'index.scss';
</style>
