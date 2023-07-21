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
			<view class="goods-info">
				<view class="item">
					<image :src="baseUrl + goodsData.goodsImg" mode="aspectFill"></image>
					<view class="info">
						<view class="h4">{{ goodsData.goodsName }}</view>
						<view class="bt">
							<view class="left">￥{{ goodsData.goodsPrice }}</view>
							<view class="right">数量x{{ goodsData.goodsNum }}</view>
						</view>
					</view>
				</view>
			</view>
		</view>
		<view class="pay-way">
			<view class="item t" @click="payChange('qb')">
				<view class="left">
					<text class="iconfont icon-qb"></text>钱包余额<text class="warn">(余额{{ goodsData.money }}元)</text>
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
	</view>
</template>

<script>
	const app = getApp()
	
	export default {
		data() {
			return {
				menuButtonBoundingClientRect: app.globalData.menuButtonBoundingClientRect,
				payway: 'qb',
				goodsData: {
					money: '0.00',
					goodsName: '',
					orderID: '',
					goodsImg: '',
					goodsNum: 0,
					goodsPrice: 0
				}
			}
		},
		methods: {
			back() {
				uni.navigateBack({
					delta: 1
				})
			},
			async paySubmit() {
				if (this.payway === 'qb') {
					uni.showLoading({
						title: '正在支付...',
						mask: true
					})
					await this.$apiRequest({
						url: '/pay.Balance/index',
						data: {
							order_id: this.goodsData.orderID
						}
					})
					uni.hideLoading()
					uni.redirectTo({
						url: `/pagesB/paySuccess/paySuccess?fromPage=SHG&money=${ this.goodsData.goodsPrice }&orderID=${ this.goodsData.orderID }`
					})
				} else {
					uni.showLoading({
						title: '正在支付...',
						mask: true
					})
					
					const data1 = await this.$apiRequest({
						url: '/pay.Wechat/index',
						data: {
							order_id: this.goodsData.orderID
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
								url: `/pagesB/paySuccess/paySuccess?fromPage=SHG&money=${ this.goodsData.goodsPrice }&orderID=${ this.goodsData.orderID }`
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
			// 支付
			payChange(type) {
				if (type === 'qb') {
					if (this.goodsData.money < this.goodsData.goodsPrice) {
						this.$showMsg('余额不足抵扣订单金额，请选择微信支付吧')
						return
					}
				}
				this.payway = type
			},
		},
		async onLoad(options) {
			this.goodsData = options
		}
	}
</script>

<style lang="scss">
	@import 'index.scss';
</style>
