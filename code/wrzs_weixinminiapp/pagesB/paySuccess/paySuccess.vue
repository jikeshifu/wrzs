<template>
	<view class="content">
		<view class="topbar">
			<image class="bg" :src="baseUrl + '/static/imgs/topbar_bg.png'" mode="widthFix"></image>
			<view class="fixed-item">
				<view :style="{ height: menuButtonBoundingClientRect.top + 'px' }"></view>
				<view class="title" :style="{ height: menuButtonBoundingClientRect.height + 'px' }">
					<view class="h1">支付成功</view>
				</view>
				<view :style="{ height: (menuButtonBoundingClientRect.top / 2) + 'px' }"></view>
			</view>
		</view>
		<view class="success-box">
			<view class="flex">
				<image :src="baseUrl + '/static/imgs/pay_success.png'" mode="aspectFill"></image>
				<view class="info">
					<view class="h4">支付成功</view>
					<view class="p">￥{{ options.money }}</view>
				</view>
			</view>
			<view class="foot" v-if="options.fromPage === 'SHG'">柜门已打开，请取走你购买的物品。</view>
		</view>
		<view class="btn-wrap">
			<!-- 来自售货柜支付成功 -->
			<template v-if="options.fromPage === 'SHG'">
				<button type="primary" @click="backIndex">返回首页</button>
				<button type="default" @click="openAgain">再次开门</button>
			</template>
			<!-- 来自房间预定支付成功 -->
			<template v-if="options.fromPage === 'room'">
				<button type="primary" @click="checkRoom">查看预定房间</button>
			</template>
			<!-- 来自优选商城支付成功 -->
			<template v-if="options.fromPage === 'shopping'">
				<button type="primary" @click="shoppingSuccess">查看订单</button>
			</template>
		</view>
	</view>
</template>

<script>
	const app = getApp()
	
	export default {
		data() {
			return {
				menuButtonBoundingClientRect: app.globalData.menuButtonBoundingClientRect,
				options: {
					money: 0,
					fromPage: '',
					orderID: ''
				}
			}
		},
		methods: {
			// 售货柜返回首页
			backIndex() {
				uni.reLaunch({
					url: '/pages/Tabs/index'
				})
			},
			// 再次开门
			async openAgain() {
				uni.showLoading({
					title: '请稍后...',
					mask: true
				})
				await this.$apiRequest({
					url: '/order.Cabinet/openLock',
					data: {
						order_id: this.options.orderID
					}
				}).then(() => {
					uni.hideLoading()
					uni.showToast({
						title: '开门成功',
						icon: 'success'
					})
				})
			},
			// 预定房间支付成功
			checkRoom() {
				uni.removeStorageSync('tabClose')
				uni.reLaunch({
					url: '/pages/Tabs/index'
				})
			},
			// 优选商城商品支付成功
			async shoppingSuccess() {
				uni.redirectTo({
					url: '/pagesC/order/order?current=1'
				})
			}
		},
		onLoad(options) {
			this.options = options
		}
	}
</script>

<style lang="scss">
	@import 'index.scss';
</style>
