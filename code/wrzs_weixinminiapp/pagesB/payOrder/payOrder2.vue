<template>
	<view class="content">
		<view class="fixed-topbar">
			<image :src="baseUrl + '/static/imgs/payOrder/bg.png'" mode="widthFix"></image>
			<view class="fixed-item">
				<view :style="{ height: menuButtonBoundingClientRect.top + 'px' }"></view>
				<view class="title" :style="{ height: menuButtonBoundingClientRect.height + 'px' }">
					<view class="iconfont icon-left" @click="back"></view>
					<view class="h1">支付订单</view>
				</view>
				<view :style="{ height: (menuButtonBoundingClientRect.top / 2) + 'px' }"></view>
			</view>
			<view class="time">
				<view class="left">
					<text class="iconfont icon-clock"></text>剩余支付时间
				</view>
				<UniCountDown @timeup="timeout" backgroundColor="transparent" color="#fff" splitorColor="#fff" :show-day="false" :hour="0" :minute="times.minutes" :second="times.seconds"></UniCountDown>
			</view>
		</view>
		<view class="card-info">
			<view class="flex-head">
				<view class="left">
					<image lazy-load
						:src="baseUrl + orderInfo.store.store_image"
						mode="aspectFill"></image>
					<view class="info">
						<view class="tt">{{ orderInfo.store.store_name }}</view>
						<view class="bb">
							<view class="add">
								<text class="iconfont icon-zb1"></text>{{ orderInfo.store.address }}
							</view>
							<view class="time">
								<text class="iconfont icon-date"></text>{{ splitDate(+(orderInfo.start_time + '000')).date }}{{ splitDate(+(orderInfo.start_time + '000')).hm }}
								<text class="half"></text>至<text class="half"></text>{{ splitDate(+(orderInfo.end_time + '000')).date }}{{ splitDate(+(orderInfo.end_time + '000')).hm }}
							</view>
						</view>
					</view>
				</view>
				<view class="right">
					<button class="share" type="default" open-type="share">
						<text class="iconfont icon-share"></text>分享
					</button>
					<button class="dh" type="default" @click="openLocation">
						<text class="iconfont icon-zb2"></text>导航
					</button>
				</view>
			</view>
			<view class="panel-items">
				<view class="head">
					<view class="flex">
						<view class="left">开始时间</view>
						<view class="right">{{ $dateFormatter(+(orderInfo.start_time + '000'), 'yyyy年mm月dd日 hh:mm') }}</view>
					</view>
					<view class="flex">
						<view class="left">结束时间</view>
						<view class="right">{{ $dateFormatter(+(orderInfo.end_time + '000'), 'yyyy年mm月dd日 hh:mm') }}</view>
					</view>
					<view class="flex">
						<view class="left">押金</view>
						<view class="right">￥{{ orderInfo.deposit }}</view>
					</view>
				</view>
				<view class="foot">
					共<text class="warn">{{ $subtTime(+(orderInfo.start_time + '000'), +(orderInfo.end_time + '000')).fmt }}</text>
					<text class="empty"></text>
					合计费用<text class="small">￥</text><text class="big">{{ orderInfo.order_total }}</text>
				</view>
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
		<wLoading v-if="pageLoading" bgColor="#fff"></wLoading>
	</view>
</template>

<script>
	import { mapState } from 'vuex'
	import UniCountDown from '@/components/uni-countdown/uni-countdown.vue'
	import wLoading from '@/components/w-loading.vue'
	import { splitDate } from '@/components/hours/index.js'
	
	const app = getApp()
	
	export default {
		components: {
			wLoading,
			UniCountDown
		},
		onShareAppMessage(e) {
			// 按钮分享
			if (e.target) {
				return {
					title: `您的好友${ this.userInfo.nick_name ? this.userInfo.nick_name : '' }分享了${ this.orderInfo.store.store_name }给您`,
					path: `/pages/Tabs/index?storeID=${ this.orderInfo.store.store_id }&keyID=''`,
					imageUrl: this.baseUrl + this.orderInfo.store.store_image
				}
			} else {
				return {
					title: `您的好友${ this.userInfo.nick_name ? this.userInfo.nick_name : '' }分享了小程序给您`,
					path: '/pages/Tabs/index'
				}
			}
		},
		computed: {
			...mapState({
				userInfo: state => state.USER.userInfo
			})
		},
		data() {
			return {
				splitDate,
				pageLoading: true,
				times: {
					minutes: 0,
					seconds: 1
				},
				payway: 'qb',
				menuButtonBoundingClientRect: app.globalData.menuButtonBoundingClientRect,
				orderInfo: {},
				// 我的余额
				myMoney: 0
			}
		},
		methods: {
			// 支付提交
			async paySubmit() {
				// 钱包支付
				if (this.payway === 'qb') {
					uni.showLoading({
						title: '正在支付...',
						mask: true
					})
					const data = await this.$apiRequest({
						url: '/pay.Balance/index',
						data: {
							order_id: this.orderInfo.order_id
						}
					})
					uni.hideLoading()
					uni.redirectTo({
						url: `/pagesB/paySuccess/paySuccess?fromPage=room&money=${ this.orderInfo.order_total }`
					})
				} else {// 微信支付
					uni.showLoading({
						title: '正在支付...',
						mask: true
					})
					
					const data = await this.$apiRequest({
						url: '/pay.Wechat/index',
						data: {
							order_id: this.orderInfo.order_id
						}
					})
					uni.hideLoading()
					uni.requestPayment({
						provider: 'wxpay',
						timeStamp: data.pay.timeStamp,
						nonceStr: data.pay.nonceStr,
						package:  data.pay.package,
						signType: 'MD5',
						paySign:  data.pay.paySign,
						success: () => {
							uni.redirectTo({
								url: `/pagesB/paySuccess/paySuccess?fromPage=room&money=${ this.orderInfo.order_total }`
							})
						},
						fail: (res) => {
							this.$showMsg('支付失败')
							console.log('支付失败', res)
						}
					})
				}
			},
			// 时间到
			timeout() {
				uni.showModal({
					title: '温馨提示',
					content: '该订单已超时，请重新下单',
					showCancel: false,
					complete() {
						const pages = getCurrentPages()
						const prevPage = pages[pages.length - 2]
						prevPage.$vm.swiper.list[prevPage.$vm.swiper.current].list = []
						prevPage.$vm.swiper.list[prevPage.$vm.swiper.current].page = 1
						prevPage.$vm.getOrderList()
						uni.navigateBack({
							delta: 1
						})
					}
				})
			},
			// 支付
			payChange(type) {
				if (type === 'qb') {
					if (this.myMoney < this.orderInfo.order_total) {
						this.$showMsg('余额不足抵扣订单金额，请选择微信支付吧')
						return
					}
				}
				this.payway = type
			},
			// 打开地图
			openLocation() {
				uni.openLocation({
					longitude: this.orderInfo.store.longitude,
					latitude: this.orderInfo.store.latitude
				})
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
				}).then(data => {
					this.orderInfo = data.info
					this.myMoney = data.money
					this.times.minutes = parseInt(this.orderInfo.sy_time / 60)
					this.times.seconds = this.orderInfo.sy_time % 60
					if (this.myMoney < this.orderInfo.order_total) {
						this.payway = 'wxzf'
					}
				}).catch(() => {})
				this.pageLoading = false
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
