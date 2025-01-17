<template>
	<view class="content">
		<Topbar title="账户充值"></Topbar>
		<!-- <navigator class="form-contain" :url="`/pagesB/List/citys/citys?city=${ citys.city_name }`">
			<view class="h2">当前城市</view>
			<view class="p">{{ citys.city_name ? citys.city_name : '请选择' }}<text class="iconfont icon-right"></text></view>
		</navigator>
		<navigator class="form-contain" :url="`/pagesB/List/store/store?cityName=${ citys.city_name }`">
			<view class="h2">当前门店</view>
			<view class="p">{{ store.store_name ? store.store_name : '请选择' }}<text class="iconfont icon-right"></text></view>
		</navigator> -->
		<view class="form-contain">
			<view class="h2">充值金额</view>
			<view class="ipt-box">
				<view class="placeholder" v-if="form.money === ''">请输入充值金额</view>
				<input type="number" v-model="form.money" maxlength="8" focus />
			</view>
		</view>
		<view class="row">
			<view class="col" v-for="item in package.list" :key="item.package_id">
				<view :class="['item', (item.package_id === package.id) ? 'active' : '']" @click="choosePackage(item)">
					<view>
						<view class="h4">{{ item.price }}元</view>
						<view class="p">赠送{{ item.profit }}元</view>
					</view>
				</view>
			</view>
		</view>
		<view class="btn">
			<button type="primary" @click="submit" :disabled="btnLoading" :loading="btnLoading">充值</button>
		</view>
	</view>
</template>

<script>
	import { mapState } from 'vuex'
	
	export default {
		computed: {
			...mapState({
				currentLocationInfo: state => state.LOCATION.currentLocationInfo
			})
		},
		watch: {
			'citys.city_name'(nv, ov) {
				if (ov) {
					this.store.store_name = ''
					this.store.store_id = ''
				}
			}
		},
		data() {
			return {
				citys: {
					city_name: ''
				},
				store: {
					store_name: '',
					store_id: ''
				},
				form: {
					money: ''
				},
				package: {
					list: [],
					id: ''
				},
				btnLoading: false
			}
		},
		methods: {
			// 选择套餐
			async choosePackage(item) {
				// if (!this.store.store_id) {
				// 	this.$showMsg('请选择门店')
				// 	return
				// }
				this.package.id = item.package_id
				uni.showLoading({
					title: '请稍后...',
					mask: true
				})
				
				const data = await this.$apiRequest({
					url: '/order.Recharge/placeOrderPackage',
					data: {
						package_id: this.package.id,
						store_id: this.store.store_id
					}
				})
				const data2 = await this.$apiRequest({
					url: '/pay.Wechat/index',
					data: {
						order_id: data.order_info.order_id
					}
				})
				uni.hideLoading()
				uni.showLoading({
					title: '正在充值...',
					mask: true
				})
				uni.requestPayment({
					provider: 'wxpay',
					timeStamp: data2.pay.timeStamp,
					nonceStr: data2.pay.nonceStr,
					package:  data2.pay.package,
					signType: 'MD5',
					paySign:  data2.pay.paySign,
					success: () => {
						uni.showModal({
							title: '温馨提示',
							content: '充值成功',
							cancelText: '返回钱包',
							confirmText: '继续充值',
							success: btn => {
								if (btn.confirm) {
									
								} else {
									const pages = getCurrentPages()
									const prevPage = pages[pages.length - 2]
									prevPage.$vm.getMoneyList()
									uni.navigateBack({
										delta: 1
									})
								}
							}
						})
					},
					fail: (res) => {
						this.$showMsg('充值失败')
						console.log('充值失败', res)
					},
					complete: () => {
						uni.hideLoading()
						this.package.id = ''
					}
				})
			},
			// 充值提交
			async submit() {
				// if (!this.store.store_id) {
				// 	this.$showMsg('请选择门店')
				// 	return
				// }
				if (+this.form.money === 0) {
					this.$showMsg('请输入充值金额')
					return
				}
				uni.showLoading({
					title: '请稍后...',
					mask: true
				})
				this.btnLoading = true
				
				const data = await this.$apiRequest({
					url: '/order.Recharge/placeOrder',
					data: {
						recharge_money: +this.form.money,
						store_id: this.store.store_id
					}
				})
				const data2 = await this.$apiRequest({
					url: '/pay.Wechat/index',
					data: {
						order_id: data.order_info.order_id
					}
				})
				uni.hideLoading()
				uni.showLoading({
					title: '正在充值...',
					mask: true
				})
				uni.requestPayment({
					provider: 'wxpay',
					timeStamp: data2.pay.timeStamp,
					nonceStr: data2.pay.nonceStr,
					package:  data2.pay.package,
					signType: 'MD5',
					paySign:  data2.pay.paySign,
					success: () => {
						uni.showModal({
							title: '温馨提示',
							content: '充值成功',
							cancelText: '返回钱包',
							confirmText: '继续充值',
							success: async btn => {
								if (btn.confirm) {
									
								} else {
									const pages = getCurrentPages()
									const prevPage = pages[pages.length - 2]
									prevPage.$vm.getMoneyList()
									uni.navigateBack({
										delta: 1
									})
								}
							}
						})
					},
					fail: (res) => {
						this.$showMsg('充值失败')
						console.log('充值失败', res)
					},
					complete: () => {
						uni.hideLoading()
						this.btnLoading = false
					}
				})
			},
			// 获取套餐列表
			async getPackageList() {
				uni.showLoading({
					title: '请稍后...',
					mask: true
				})
				
				await this.$apiRequest({
					url: '/order.Recharge/package'
				}).then(data => {
					uni.hideLoading()
					this.package.list = data.list
				})
			}
		},
		onLoad(options) {
			this.citys.city_name = options.city_name || this.currentLocationInfo.city
			this.store.store_id = options.store_id || ''
			this.store.store_name = options.store_name || ''
			this.getPackageList()
		}
	}
</script>

<style lang="scss">
	@import 'index.scss';
</style>
