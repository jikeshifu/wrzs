<template>
	<view class="content">
		<template v-if="list.length">
			<view class="item" v-for="item in list" :key="item.order_id">
				<view class="title">订单编号:{{ item.order_sn }}</view>
				<view class="store">
					<view class="h4">门店:{{ item.store.store_name }}</view>
				</view>
				<view class="room">
					<image :src="baseUrl + item.orderRoom.room_info.room_image " mode="aspectFill"></image>
					<view class="info">
						<view class="h4">{{ item.orderRoom.room_info.room_name }}</view>
						<view class="label">{{ item.orderRoom.room_info.room_label }}</view>
						<view class="foot">可收益:<text>{{ item.order_profit }}</text>元</view>
					</view>
				</view>
				<view class="user-info">
					<view class="h4">【下单用户】</view>
					<view class="p">微信昵称:{{ item.member.nick_name }}</view>
					<view class="p">联系方式:{{ item.member.mobile }}</view>
				</view>
				<view class="btn-group">
					<button class="tk" type="default" v-if="item.deposit_status  === 1" @click="tk(item)">退款</button>
					<button class="qxdd" type="default" @click="cancelOrder(item)">取消订单</button>
				</view>
			</view>
			<view class="append-more-text">{{ appendMoreText }}</view>
		</template>
		<NoData v-else>暂无订单</NoData>
		<view class="refund-dialog" v-if="refundDialog.show">
			<view class="mask"></view>
			<view class="d-content">
				<view class="title">退款押金</view>
				<view class="input-group">
					<label for="">当前押金</label>
					<text>￥{{ refundDialog.form.deposit }}</text>
				</view>
				<view class="input-group flex">
					<label for="">扣除</label>
					<view class="ml20">
						<UniNumberBox v-model="refundDialog.form.deposit_deduct" :min="0" :max="refundDialog.form.deposit" @change="numboxChange"></UniNumberBox>
					</view>
					<view class="btn" @click="refundDialog.form.deposit_deduct = refundDialog.form.deposit">全扣</view>
				</view>
				<view class="input-group">
					<label for="">退款备注</label>
					<textarea v-model="refundDialog.form.deposit_remarks" placeholder="退款备注(选填),100字以内" placeholder-style="color:#c9c9c9" maxlength="100" />
				</view>
				<view class="btn-group">
					<button type="default" @click="hideRefundDialog">关闭</button>
					<button type="primary" @click="submitRefund" :loading="refundDialog.btnLoading" :disabled="refundDialog.btnLoading">确认退款</button>
				</view>
			</view>
		</view>
	</view>
</template>

<script>
	import NoData from '@/components/NoData.vue'
	import UniNumberBox from '@/components/UniNumberBox.vue'
	
	export default {
		components: {
			NoData,
			UniNumberBox
		},
		data() {
			return {
				baseUrl: this.$baseUrl,
				page: 1,
				limit: 20,
				list: [],
				appendMoreText: '正在加载更多',
				refundDialog: {
					show: false,
					btnLoading: false,
					form: {
						order_id: '',
						deposit: 0,
						deposit_deduct: 0,
						deposit_remarks: ''
					}
				}
			}
		},
		methods: {
			// 取消订单
			cancelOrder(data) {
				uni.showModal({
					title: '温馨提示',
					content: '您确定要取消该订单吗?',
					success: async (btn) => {
						if (btn.confirm) {
							uni.showLoading({
								title: '请稍后...',
								mask: true
							})
							await this.$apiRequest({
								url: '/order.Room/cancel',
								data: {
									order_id: data.order_id
								}
							}).then(() => {
								uni.showToast({
									title: '取消成功',
									icon: 'success'
								})
								this.page = 1
								this.getOrderList()
							})
						}
					}
				})
			},
			// 确认退款
			async submitRefund() {
				if (!this.refundDialog.form.deposit_deduct) {
					uni.showToast({
						title: '请输入正确的金额',
						icon: 'none'
					})
					return
				}
				uni.showModal({
					title: '温馨提示',
					content: '确定要退该订单的款吗?',
					success: async (btn) => {
						if (btn.confirm) {
							this.refundDialog.btnLoading = true
							uni.showLoading({
								title: '请稍后...',
								mask: true
							})
							await this.$apiRequest({
								url: '/order.Room/returnDeposit',
								data: this.refundDialog.form
							}).then(() => {
								this.refundDialog.show = false
								uni.showToast({
									title: '退款成功',
									icon: 'success'
								})
								this.refundDialog.form.deposit_deduct = 0
								this.page = 1
								this.getOrderList()
							}).catch(() => {})
							this.refundDialog.btnLoading = false
						}
					}
				})
			},
			// 隐藏弹窗
			hideRefundDialog() {
				this.refundDialog.show = false
				this.refundDialog.form.deposit_deduct = 0
			},
			numboxChange(e) {
				this.refundDialog.form.deposit_deduct = e
			},
			// 退款
			tk(data) {
				this.refundDialog.show = true
				this.refundDialog.form.order_id = data.order_id
				this.refundDialog.form.deposit = data.deposit
			},
			// 获取订单列表
			async getOrderList() {
				await this.$apiRequest({
					url: '/order.Room/list',
					data: {
						page: this.page,
						limit: this.limit
					}
				}).then(data => {
					if (data.list.length) {
						data.list.forEach(item => {
							this.list.push(item)
						})
						this.appendMoreText = '上滑加载更多'
						if (this.list.length < this.limit) {
							this.appendMoreText = '没有更多数据了'
						}
					} else {
						this.appendMoreText = '没有更多数据了'
					}
				})
				uni.stopPullDownRefresh()
			},
			onReachBottom() {
				if (this.appendMoreText !== '上滑加载更多') {
					return
				}
				this.page += 1
				this.appendMoreText = '正在加载中...'
				this.getOrderList()
			},
		},
		onPullDownRefresh() {
			this.page = 1
			this.list = []
			this.getOrderList()
		},
		mounted() {
			this.getOrderList()
		}
	}
</script>

<style lang="scss">
	@import './index.scss';
</style>
