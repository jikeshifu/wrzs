<template>
	<view class="content">
		<view class="fixed-topbar">
			<image :src="baseUrl + '/static/imgs/payOrder/bg.png'" mode="widthFix"></image>
			<view class="fixed-item" :style="{ backgroundColor: 'rgba(65, 146, 53, ' + bgColor + ')' }">
				<view :style="{ height: menuButtonBoundingClientRect.top + 'px' }"></view>
				<view class="title" :style="{ height: menuButtonBoundingClientRect.height + 'px' }">
					<view class="iconfont icon-left" @click="back"></view>
					<view class="h1">支付订单</view>
				</view>
				<view :style="{ height: (menuButtonBoundingClientRect.top / 2) + 'px' }"></view>
			</view>
			<view class="address-box" @click="choooseAddress">
				<view class="tabs">快递邮寄</view>
				<view class="flex">
					<view class="left">
						<view class="h4">{{ address.detailInfo }}</view>
						<view class="p">{{ address.userName }}<text>{{ address.telNumber }}</text></view>
					</view>
					<view class="right">
						<text class="iconfont icon-right"></text>
					</view>
				</view>
				<view class="foot">
					<text class="iconfont icon-tip"></text>预计5天送达
				</view>
			</view>
		</view>
		<view class="goods-wrap">
			<view class="item" v-for="item in goodsData.list" :key="id">
				<image :src="baseUrl + item.goods_image" mode="aspectFill"></image>
				<view class="info">
					<view class="h4">{{ item.goods_name }}</view>
					<view class="bt">
						<view class="left">￥{{ item.goods_price }}</view>
						<view class="right">数量x{{ item.goods_number }}</view>
					</view>
				</view>
			</view>
			<view @click="chooseCoupon" class="coupon-item" url="/pagesB/myAsset/coupon/coupon" v-if="fromPage !== 'order'">
				<view class="left">卡券</view>
				<view class="right">
					{{ couponInfo.title ? couponInfo.title : '无使用' }}<text class="iconfont icon-right"></text>
				</view>
			</view>
			<view class="foot">
				共<text class="warn">{{ goodsData.number }}</text>件商品
				<text class="full"></text>
				合计费用<text class="small">￥</text>
				<text class="big">{{ totalPay }}</text>
			</view>
		</view>
		<view class="pay-way">
			<view class="item t" @click="payChange('qb')">
				<view class="left">
					<text class="iconfont icon-qb"></text>钱包余额<text class="warn">(余额{{ myAsset.money }}元)</text>
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
	import { mapActions, mapState } from 'vuex'
	const app = getApp()
	
	export default {
		computed: {
			// 总共支付的金额
			totalPay() {
				return this.$calc.subtract(this.goodsData.totalMoney, this.couponInfo.money, 2) < 0 ? 0 : this.$calc.subtract(this.goodsData.totalMoney, this.couponInfo.money, 2)
			},
			...mapState({
				myAsset: state => state.USER.myAsset
			})
		},
		data() {
			return {
				menuButtonBoundingClientRect: app.globalData.menuButtonBoundingClientRect,
				address: {
					userName: '您的姓名',
					detailInfo: '请选择您的收货地址',
					telNumber: '您的联系方式'
				},
				// 优惠券信息
				couponInfo: {
					title: '',
					money: 0
				},
				payway: 'wxzf',
				orderInfo: {},
				// 商品信息
				goodsData: {},
				bgColor: 0,
				// 来自哪个页面
				fromPage: ''
			}
		},
		onPageScroll(e) {
			this.bgColor = e.scrollTop / 200
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
			...mapActions({
				initGwcarData: 'pagesC_GWCAR/initGwcarData'
			}),
			back() {
				uni.navigateBack({
					delta: 1
				})
			},
			choooseAddress() {
				uni.chooseAddress({
					success: (res) => {
						this.address.userName = res.userName
						this.address.telNumber = res.telNumber
						this.address.detailInfo = res.provinceName + res.cityName + res.countyName + res.detailInfo
					}
				})
			},
			// 支付
			payChange(type) {
				if (type === 'qb') {
					if (this.myAsset.money < this.totalPay) {
						this.$showMsg('余额不足抵扣订单金额，请选择微信支付吧')
						return
					}
				}
				this.payway = type
			},
			// 支付提交
			async paySubmit() {
				if (this.address.userName === '您的姓名') {
					this.$showMsg('请选择收货地址')
					return
				}
				let data = {
					order_id: ''
				}
				// 如果不是订单页面过来的
				if (!this.goodsData.orderID) {
					uni.showLoading({
						title: '正在下单...',
						mask: true
					})
					const goods_list = []
					this.goodsData.list.forEach(item => {
						goods_list.push({
							goods_id: item.goods_id,
							goods_number: item.goods_number
						})
					})
					data = await this.$apiRequest({
						url: '/shop.Order/placeOrder',
						data: {
							goods_list,
							address: this.address.detailInfo,
							mobile: this.address.telNumber,
							user_name: this.address.userName
						}
					})
					await this.$apiRequest({
						url: '/shop.ShoppingCar/dels'
					})
					this.initGwcarData()
					uni.hideLoading()
				} else {
					data.order_id = this.goodsData.orderID
				}
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
								order_id: data.order_id,
								member_coupons_id: this.couponInfo.member_coupons_id
							}
						})
					}
					await this.$apiRequest({
						url: '/pay.Balance/index',
						data: {
							order_id: data.order_id
						}
					})
					uni.hideLoading()
					uni.redirectTo({
						url: `/pagesB/paySuccess/paySuccess?fromPage=shopping&money=${ this.totalPay }`
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
								order_id: data.order_id,
								member_coupons_id: this.couponInfo.member_coupons_id
							}
						})
					}
					const data1 = await this.$apiRequest({
						url: '/pay.Wechat/index',
						data: {
							order_id: data.order_id
						}
					})
					uni.requestPayment({
						provider: 'wxpay',
						timeStamp: data1.pay.timeStamp,
						nonceStr: data1.pay.nonceStr,
						package:  data1.pay.package,
						signType: 'MD5',
						paySign:  data1.pay.paySign,
						success: () => {
							uni.redirectTo({
								url: `/pagesB/paySuccess/paySuccess?fromPage=shopping&money=${ this.totalPay }`
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
			// 获取商品信息
			getGoodsData() {
				this.goodsData = uni.getStorageSync('goodsList')
			}
		},
		onLoad(options) {
			this.$store.dispatch('USER/getAssetInfo')
			this.fromPage = options.fromPage
			this.getGoodsData()
		}
	}
</script>

<style lang="scss">
	@import 'index.scss';
</style>
