<template>
	<view class="content">
		<Topbar title="意见反馈"></Topbar>
		<view class="textarea">
			<textarea v-model.trim="content" placeholder="请输入您的意见" rows="5" placeholder-style="color:#B3B3B3" />
		</view>
		<view class="fixed-bottom-btn">
			<button type="primary" @click="submit">提交</button>
		</view>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				content: ''
			}
		},
		methods: {
			async submit() {
				if (this.content.length < 3) {
					this.$showMsg('内容太少了，多说点内容吧')
					return
				}
				uni.showLoading({
					title: '正在提交...',
					mask: true
				})
				
				await this.$apiRequest({
					url: '/proposal.Proposal/add',
					data: {
						content: this.content
					}
				}).then(() => {
					uni.hideLoading()
					uni.showModal({
						title: '温馨提示',
						content: '感谢您的宝贵意见与支持',
						showCancel: false,
						complete() {
							uni.navigateBack({
								delta: 1
							})
						}
					})
				})
			}
		}
	}
</script>

<style lang="scss">
	@import 'index.scss';
</style>
