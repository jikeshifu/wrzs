<template>
	<view class="content">
		<view class="bg-wrap">
			<image class="bg" src="@/static/imgs/login/bg.png" mode="widthFix"></image>
			<view class="avatar-box">
				<image src="@/static/imgs/login/avatar.jpeg" mode="aspectFill"></image>
				<view class="p">欢迎使用管理系统</view>
			</view>
		</view>
		<view class="form-contain">
			<view class="input-group">
				<text class="iconfont icon-mobile"></text>
				<input type="number" v-model="form.mobile" placeholder="请输入账号" maxlength="11" />
			</view>
			<view class="input-group">
				<text class="iconfont icon-pwd"></text>
				<input password v-model="form.pw" placeholder="请输入密码" maxlength="30" />
			</view>
			<button class="btn" type="default" :loading="btnLoading" :disabled="btnLoading" @click="submit">登录</button>
		</view>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				btnLoading: false,
				form: {
					mobile: '',
					pw: ''
				}
			}
		},
		methods: {
			// 提交
			async submit() {
				if (!this.form.mobile) {
					this.$showMsg('请输入账号')
					return
				}
				if (!this.form.pw) {
					this.$showMsg('请输入密码')
					return
				}
				
				this.btnLoading = true
				const data = await this.$apiRequest({
					url: '/user.user/login',
					data: this.form
				}).catch(() => {
					this.btnLoading = false
				})
				if (data) {
					this.btnLoading = false
					uni.setStorageSync('token', data.token)
					uni.setStorageSync('userInfo', data.user_info)
					uni.switchTab({
						url: '/pages/Tabs/index/index'
					})
				}
			}
		},
		created() {
			const token = uni.getStorageSync('token')
			if (token) {
				uni.switchTab({
					url: '/pages/Tabs/index/index'
				})
			}
		}
	}
</script>

<style scoped lang="scss">
	@import 'index.scss';
</style>
