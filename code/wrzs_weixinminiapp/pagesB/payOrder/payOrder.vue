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
				<UniCountDown @timeup="timeout" backgroundColor="transparent" color="#fff" splitorColor="#fff" :show-day="false" :hour="0" :minute="5" :second="0"></UniCountDown>
			</view>
		</view>
		<view class="card-info">
			<view class="flex-head">
				<view class="left">
					<image lazy-load
						:src="baseUrl + storeData.store_image"
						mode="aspectFill"></image>
					<view class="info">
						<view class="tt">{{ storeData.store_name }}</view>
						<view class="bb">
							<view class="add">
								<text class="iconfont icon-zb1"></text>{{ storeData.address }}
							</view>
							<view class="time">
								<text class="iconfont icon-date"></text>{{ splitDate(form.start_time).date }}{{ splitDate(form.start_time).hm }}
								<text class="half"></text>至<text class="half"></text>{{ splitDate(form.end_time).date }}{{ splitDate(form.end_time).hm }}
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
						<view class="right">{{ $dateFormatter(form.start_time, 'yyyy年mm月dd日 hh:mm') }}</view>
					</view>
					<view class="flex">
						<view class="left">结束时间</view>
						<view class="right">{{ $dateFormatter(form.end_time, 'yyyy年mm月dd日 hh:mm') }}</view>
					</view>
					<view class="flex">
						<view class="left">押金</view>
						<view class="right">￥{{ orderInfo.deposit }}</view>
					</view>
					<view class="flex" @click="chooseCoupon">
						<view class="left">卡券</view>
						<view class="right">{{ couponInfo.title ? couponInfo.title : '无使用' }}<text class="iconfont icon-right"></text></view>
					</view>
				</view>
				<view class="foot">
					共<text class="warn">{{ $subtTime(form.start_time, form.end_time).fmt }}</text>
					<text class="empty"></text>
					合计费用<text class="small">￥</text><text class="big">{{ totalPay }}</text>
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
			<view class="item b" @click="payChange('wxzf')" v-if="totalPay !== 0">
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
	</view>
</template>

<script>
	import { mapState } from 'vuex'
	import UniCountDown from '@/components/uni-countdown/uni-countdown.vue'
	import { splitDate } from '@/components/hours/index.js'
	
	const app = getApp()
	
	export default {
		components: {
			UniCountDown
		},
		onShareAppMessage(e) {
			// 按钮分享
			if (e.target) {
				return {
					title: `您的好友${ this.userInfo.nick_name ? this.userInfo.nick_name : '' }分享了${ this.storeData.store_name }给您`,
					path: `/pages/Tabs/index?storeID=${ this.storeData.store_id }&keyID=''`,
					imageUrl: this.baseUrl + this.storeData.store_image
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
				storeData: state => state.STORE.storeData,
				userInfo: state => state.USER.userInfo,
				roomData: state => state.ROOM.roomData
			}),
			// 总共支付的金额
			totalPay() {
				const money = this.$calc.subtract(this.orderInfo.order_total, this.couponInfo.money, 2) < 0 ? 0 : this.$calc.subtract(this.orderInfo.order_total, this.couponInfo.money, 2)
				if (this.myMoney >= money) {
					this.payway = 'qb'
				} else {
					this.payway = 'wxzf'
				}
				return money
			}
		},
		data() {
			return {
				splitDate,
				// 优惠券信息
				couponInfo: {
					title: '',
					money: 0
				},
				payway: 'qb',
				menuButtonBoundingClientRect: app.globalData.menuButtonBoundingClientRect,
				form: {},
				orderInfo: {},
				// 我的余额
				myMoney: 0
			}
		},
		methods: {
			// 选择优惠券
			chooseCoupon() {
				if (!this.couponInfo.title) {
					uni.navigateTo({
						url: '/pagesB/myAsset/coupon/coupon?fromPayOrder=true'
					})
				} else {
					uni.showModal({
						title: '温馨提示',
						content: '当前已有选择卡券，是否重新选择?',
						cancelText: '不想用了',
						confirmText: '重新选择',
						success: (btn) => {
							if (btn.confirm) {
								uni.navigateTo({
									url: '/pagesB/myAsset/coupon/coupon?fromPayOrder=true'
								})
							} else {
								this.couponInfo = {
									title: '',
									money: 0
								}
							}
						}
					})
				}
			},
			// 支付提交
			async paySubmit() {
				// 钱包支付
				if (this.payway === 'qb') {
					uni.showLoading({
						title: '正在支付...',
						mask: true
					})
					if (this.couponInfo.title) {
						// 添加优惠券
						await this.$apiRequest({
							url: '/order.Coupons/placeOrder',
							data: {
								order_id: this.orderInfo.order_id,
								member_coupons_id: this.couponInfo.member_coupons_id
							}
						})
					}
					await this.$apiRequest({
						url: '/pay.Balance/index',
						data: {
							order_id: this.orderInfo.order_id
						}
					})
					uni.hideLoading()
					uni.redirectTo({
						url: `/pagesB/paySuccess/paySuccess?fromPage=room&money=${ this.totalPay }`
					})
				} else {// 微信支付
					uni.showLoading({
						title: '正在支付...',
						mask: true
					})
					if (this.couponInfo.title) {
						// 添加优惠券
						await this.$apiRequest({
							url: '/order.Coupons/placeOrder',
							data: {
								order_id: this.orderInfo.order_id,
								member_coupons_id: this.couponInfo.member_coupons_id
							}
						})
					}
					await this.$apiRequest({
						url: '/pay.Wechat/index',
						data: {
							order_id: this.orderInfo.order_id
						}
					}).then(data => {
						uni.requestPayment({
							provider: 'wxpay',
							timeStamp: data.pay.timeStamp,
							nonceStr: data.pay.nonceStr,
							package:  data.pay.package,
							signType: 'MD5',
							paySign:  data.pay.paySign,
							success: () => {
								uni.redirectTo({
									url: `/pagesB/paySuccess/paySuccess?fromPage=room&money=${ this.totalPay }`
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
						uni.navigateBack({
							delta: 1
						})
					}
				})
			},
			// 支付
			payChange(type) {
				if (type === 'qb') {
					if (this.myMoney < this.totalPay) {
						this.$showMsg('余额不足抵扣订单金额，请选择微信支付吧')
						return
					}
				}
				this.payway = type
			},
			// 打开地图
			openLocation() {
				uni.openLocation({
					longitude: this.storeData.longitude,
					latitude: this.storeData.latitude
				})
			},
			back() {
				uni.navigateBack({
					delta: 1
				})
			},
			// 下单
			async downOrder() {
				uni.showLoading({
					title: '正在下单...',
					mask: true
				})
				await this.$apiRequest({
					url: '/order.Room/placeOrder',
					data: {
						start_time: new Date(this.form.start_time).getTime().toString().slice(0, 10),
						end_time: new Date(this.form.end_time).getTime().toString().slice(0, 10),
						room_id: this.roomData.room_id
					}
				}).then(data => {
					uni.hideLoading()
					this.orderInfo = data.order_info
					this.myMoney = data.money
					if (this.myMoney < this.totalPay) {
						this.payway = 'wxzf'
					}
				})
			}
		},
		onLoad(options) {
			this.form = options
			this.downOrder()
		}
	}
</script>

<style lang="scss">
	@import 'index.scss';
</style>
