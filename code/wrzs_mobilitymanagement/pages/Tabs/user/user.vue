<template>
	<view class="content">
		<view class="bg-wrap">
			<image class="bg" src="@/static/imgs/login/bg.png" mode="widthFix"></image>
			<view class="avatar-box">
				<image src="@/static/imgs/login/avatar.jpeg" mode="aspectFill"></image>
				<view class="p">管理员</view>
			</view>
		</view>
		<view class="panel-group" v-if="userInfo.level === 0">
			<view class="item" @click="pageJump('/pages/wallet/wallet')">
				<view class="left">
					<text class="iconfont icon-wallet"></text>我的资产
				</view>
				<view class="right">
					<text class="iconfont icon-right"></text>
				</view>
			</view>
		</view>
		<view class="btn-group">
			<button type="primary" @click="logout">退出登录</button>
		</view>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				userInfo: uni.getStorageSync('userInfo')
			}
		},
		methods: {
			logout() {
				uni.showModal({
					title: '温馨提示',
					content: '是否确认退出?',
					success: (btn) => {
						if (btn.confirm) {
							uni.clearStorageSync()
							uni.reLaunch({
								url: '/pages/login/login'
							})
						}
					}
				})
			},
			// 页面跳转
			pageJump(url) {
				uni.navigateTo({
					url
				})
			}
		}
	}
</script>

<style scoped lang="scss">
	@import 'index.scss';
</style>
