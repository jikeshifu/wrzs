<template>
	<view>
		<view class="fixed-icons">
			<view class="box">
				<navigator class="icon" url="/pagesC/order/order">
					<text class="iconfont icon-order"></text>
					<text class="badge">{{ dzfCount }}</text>
				</navigator>
				<view class="icon" @click="showCar = true">
					<text class="iconfont icon-gwcar"></text>
					<text class="badge">{{ gwcarData.length }}</text>
				</view>
			</view>
		</view>
		<view class="fixed-gwcar-wrap" v-if="showCar">
			<view class="mask" @click="showCar = false"></view>
			<view class="contt">
				<view class="header">
					<view class="left">购物车</view>
					<view class="right" @click="clearCar">清空</view>
				</view>
				<scroll-view scroll-y>
					<NoData title="还没有商品" v-if="!gwcarData.length"></NoData>
					<template v-else>
						<view class="item" v-for="(item, index) in gwcarData" :key="item.id">
							<view class="flex">
								<view class="left">
									<image :src="baseUrl + item.goodsInfo.goods_image" mode="aspectFill"></image>
									<view class="info">
										<view class="h4">{{ item.goodsInfo.goods_name }}</view>
										<view class="p">￥{{ item.goodsInfo.goods_price }}</view>
									</view>
								</view>
								<view class="right">
									<image @click="reduceGoods(item, index)" :src="baseUrl + '/static/imgs/reduce.png'" mode="aspectFill"></image>
									<text>{{ item.number }}</text>
									<image @click="addGoods(item)" :src="baseUrl + '/static/imgs/add.png'" mode="aspectFill"></image>
								</view>
							</view>
						</view>
					</template>
				</scroll-view>
				<view class="foot">
					<view class="left">
						<view class="h4">
							<text>￥</text>{{ totalMoney }}
						</view>
						<view class="p">共{{ gwcarData.length }}件</view>
					</view>
					<view class="right" v-if="totalMoney">
						<button type="primary" @click="submit">去支付</button>
					</view>
				</view>
			</view>
		</view>
	</view>
</template>

<script>
	import { mapState, mapActions, mapGetters } from 'vuex'
	
	export default {
		computed: {
			...mapState({
				gwcarData: state => state.pagesC_GWCAR.gwcarData,
				dzfCount: state => state.pagesC_DZF.dzfCount
			}),
			...mapGetters({
				totalMoney: 'pagesC_GWCAR/totalMoney'
			})
		},
		data() {
			return {
				showCar: false
			}
		},
		methods: {
			...mapActions({
				initGwcarData: 'pagesC_GWCAR/initGwcarData',
				initDZFCount: 'pagesC_DZF/initDZFCount'
			}),
			// 减去商品
			async reduceGoods(item, index) {
				if (item.number === 1) {
					this.gwcarData.splice(index, 1)
					item.number -= 1
					uni.showLoading({
						title: '请稍后...',
						mask: true
					})
					await this.$apiRequest({
						url: '/shop.ShoppingCar/del',
						data: {
							id: item.id
						}
					})
					uni.hideLoading()
					return
				}
				item.number -= 1
				uni.showLoading({
					title: '请稍后...',
					mask: true
				})
				await this.$apiRequest({
					url: '/shop.ShoppingCar/edit',
					data: {
						id: item.id,
						number: item.number
					}
				})
				uni.hideLoading()
			},
			// 增加商品
			async addGoods(item) {
				if (item.number === item.goodsInfo.goods_stock) {
					this.$showMsg('库存不足')
					return
				}
				item.number += 1
				uni.showLoading({
					title: '请稍后...',
					mask: true
				})
				await this.$apiRequest({
					url: '/shop.ShoppingCar/edit',
					data: {
						id: item.id,
						number: item.number
					}
				})
				uni.hideLoading()
			},
			// 清空购物车
			clearCar() {
				uni.showModal({
					title: '温馨提示',
					content: '您确定要清除购物车吗?',
					success: async (btn) => {
						if (btn.confirm) {
							uni.showLoading({
								title: '正在清除...',
								mask: true
							})
							await this.$apiRequest({
								url: '/shop.ShoppingCar/dels'
							})
							this.initGwcarData()
							uni.hideLoading()
							uni.showToast({
								title: '清除成功',
								icon: 'success'
							})
						}
					}
				})
			},
			// 提交
			submit() {
				let list = []
				this.gwcarData.forEach(item => {
					list.push({
						goods_id: item.goodsInfo.goods_id,
						goods_number: item.number,
						goods_image: item.goodsInfo.goods_image,
						goods_price: item.goodsInfo.goods_price,
						goods_name: item.goodsInfo.goods_name
					})
				})
				uni.setStorageSync('goodsList', {
					number: this.gwcarData.length,
					list,
					totalMoney: this.totalMoney
				})
				this.showCar = false
				uni.navigateTo({
					url: '/pagesC/payOrder/payOrder'
				})
			}
		},
		mounted() {
			this.initGwcarData()
			this.initDZFCount()
		}
	}
</script>

<style lang="scss" scoped>
	.fixed-icons {
		position: fixed;
		left: 20upx;
		z-index: 10;
		bottom: 200upx;
		.box {
			box-shadow: 0 0 10upx rgba($color: #000000, $alpha: .15);
			border-radius: 10upx;
			background-color: #fff;
			padding-left: 5upx;
			padding-right: 5upx;
			.icon {
				position: relative;
				padding-top: 10upx;
				padding-bottom: 10upx;
				.iconfont {
					font-size: 50upx;
				}
				& + .icon {
					border-top: 1px solid #EBEBEB;
				}
				.badge {
					position: absolute;
					z-index: 1;
					background-color: #FF4A4A;
					color: #fff;
					border-radius: 10upx;
					top: 10upx;
					right: 0;
					font-size: 18upx;
					padding: 2upx 4upx;
				}
			}
		}
	}
	
	@keyframes slideUp {
		0% {
			transform: translateY(200px);
			opacity: 0;
		}
		100% {
			transform: translateY(0);
			opacity: 1;
		}
	}
	
	
	.fixed-gwcar-wrap {
		position: fixed;
		z-index: 11;
		bottom: 0;
		left: 0;
		width: 100%;
		height: 100%;
		.mask {
			position: absolute;
			z-index: 1;
			top: 0;
			left: 0;
			width: 100%;
			height: 100%;
			background-color: rgba($color: #000000, $alpha: .45);
		}
		.contt {
			position: absolute;
			z-index: 2;
			bottom: 0;
			left: 0;
			width: 100%;
			background-color: #fff;
			border-top-left-radius: 40upx;
			border-top-right-radius: 40upx;
			padding-top: 20upx;
			padding-bottom: 20upx;
			animation: slideUp .45s both ease;
			.header {
				display: flex;
				align-items: center;
				justify-content: space-between;
				margin-top: 10upx;
				padding-left: 20upx;
				padding-right: 20upx;
				.left {
					font-size: 37upx;
				}
				.right {
					font-size: 30upx;
					color: #4D5AB4;
				}
			}
			scroll-view {
				height: 400upx;
				margin-top: 20upx;
				.item {
					background-color: #FBFBFB;
					padding-left: 20upx;
					padding-right: 20upx;
					.flex {
						display: flex;
						align-items: center;
						justify-content: space-between;
						padding-top: 20upx;
						padding-bottom: 20upx;
						.left {
							display: flex;
							flex: 1;
							width: 1%;
							margin-right: 30upx;
							image {
								width: 160upx;
								height: 130upx;
							}
							.info {
								font-size: 24upx;
								display: flex;
								flex-direction: column;
								justify-content: space-between;
								margin-left: 20upx;
								flex: 1;
								width: 1%;
								.h4 {
									display: -webkit-box;
									-webkit-box-orient: vertical;
									-webkit-line-clamp: 2;
									overflow: hidden;
									height: 65upx;
									line-height: 35upx;
								}
								.p {
									color: #FF9813;
								}
							}
						}
						.right {
							display: flex;
							align-items: center;
							image {
								width: 40upx;
								height: 40upx;
							}
							text {
								font-size: 37upx;
								margin-left: 20upx;
								margin-right: 20upx;
							}
						}
					}
					& + .item {
						.flex {
							border-top: 1px solid #F0F0F0;
						}
					}
				}
			}
		}
		.foot {
			display: flex;
			align-items: center;
			justify-content: space-between;
			padding-left: 20upx;
			padding-right: 20upx;
			.left {
				color: #FF9813;
				.h4 {
					font-size: 42upx;
					text {
						font-size: 24upx;
					}
				}
				.p {
					font-size: 24upx;
					color: #C3C3C3;
					margin-top: 5upx;
				}
			}
			.right {
				button {
					width: 210upx;
					background-color: #429435;
					height: 72upx;
					line-height: 72upx;
					border-radius: 10upx;
					font-size: 30upx;
				}
			}
		}
	}
</style>