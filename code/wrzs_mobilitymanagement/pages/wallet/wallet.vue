<template>
	<view class="content">
		<view class="banner">
			<view class="caption">
				<view class="title">可提现金额</view>
				<view class="p">￥{{ myMoney }}</view>
			</view>
		</view>
		<view class="card-box">
			<view class="row">
				<view class="col">
					<view class="item" @click="pageJump('/pages/tx/tx')">
						<view class="iconfont icon-tx"></view>
						<view class="text">提现</view>
					</view>
				</view>
				<view class="col">
					<view class="item" @click="pageJump('/pages/tx/record/record')">
						<view class="iconfont icon-record"></view>
						<view class="text">提现记录</view>
					</view>
				</view>
				<view class="col">
					<view class="item" @click="pageJump('/pages/profit/record/record')">
						<view class="iconfont icon-record"></view>
						<view class="text">收益记录</view>
					</view>
				</view>
			</view>
		</view>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				myMoney: '0.00'
			}
		},
		methods: {
			// 页面跳转
			pageJump(url) {
				uni.navigateTo({
					url
				})
			},
			// 获取余额
			async getWallet() {
				await this.$apiRequest({
					url: '/join.Wallet/info'
				}).then(data => {
					this.myMoney = data.info.money
				})
			}
		},
		onShow() {
			this.getWallet()
		}
	}
</script>

<style lang="scss">
	@import 'index.scss';
</style>
