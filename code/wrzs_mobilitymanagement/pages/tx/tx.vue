<template>
	<view class="content">
		<view class="banner">
			<view class="caption">
				<view class="title">可提现金额</view>
				<view class="p">￥{{ myMoney }}</view>
			</view>
		</view>
		<view class="ipt-group">
			<label for="">提现金额</label>
			<input type="number" v-model.trim="number" maxlength="5" placeholder="请输入提现金额" placeholder-style="color:#c9c9c9" @input="number = number.replace(/[^\d]/g, '')" />
		</view>
		<view class="tip">最低提现金额为2000元，申请后3-5个工作日完成</view>
		<button type="primary" @click="submit">确认提现</button>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				number: '',
				myMoney: '0.00'
			}
		},
		methods: {
			// 获取余额
			async getWallet() {
				await this.$apiRequest({
					url: '/join.Wallet/info'
				}).then(data => {
					this.myMoney = data.info.money
				})
			},
			// 确认提交提现
			submit() {
				if (this.number < 2000) {
					this.$showMsg('最低提现金额为2000元')
					return
				}
				if (this.myMoney - this.number < 1000) {
					this.$showMsg('预留金额须保留1000元')
					return
				}
				if (this.myMoney < this.number) {
					this.$showMsg('余额不足')
					return
				}
				
				uni.showModal({
					title: '温馨提示',
					content: '是否确认提现?',
					success: async (btn) => {
						if (btn.confirm) {
							uni.showLoading({
								title: '正在提交...',
								mask: true
							})
							await this.$apiRequest({
								url: '/join.Wallet/wthdrawal',
								data: {
									money: this.number
								}
							}).then(() => {
								uni.hideLoading()
								uni.showModal({
									title: '温馨提示',
									content: '已提交审核，系统将在3-5个工作日完成',
									showCancel: false,
									complete: () => {
										uni.navigateBack({
											delta: 1
										})
									}
								})
							})
						}
					}
				})
			}
		},
		mounted() {
			this.getWallet()
		}
	}
</script>

<style lang="scss">
	@import 'index.scss';
</style>
