<template>
	<view class="footer-bottom-navbar">
		<view :class="['item', (active === 0 ? 'active' : '')]" @click="tabChange(0)">
			<image v-show="active === 0" :src="baseUrl + '/static/imgs/Tabs/home2.png'" mode="aspectFill"></image>
			<image v-show="active !== 0" :src="baseUrl + '/static/imgs/Tabs/home.png'" mode="aspectFill"></image>
			<view class="h4">附近门店</view>
		</view>
		<view class="center" @click="listChange">
			<image :src="baseUrl + '/static/imgs/Tabs/' + (list ? 'close' : 'open') + '.png'" mode="aspectFill"></image>
		</view>
		<view :class="['item', (active === 1 ? 'active' : '')]" @click="tabChange(1)">
			<image v-show="active === 1" :src="baseUrl + '/static/imgs/Tabs/user2.png'" mode="aspectFill"></image>
			<image v-show="active !== 1" :src="baseUrl + '/static/imgs/Tabs/user.png'" mode="aspectFill"></image>
			<view class="h4">个人中心</view>
		</view>
		<view :class="['tip', (tipClose === 'loading' ? 'close' : '')]" v-if="tipClose != 'close'">
			<view class="title">这里可以快速开门</view>
			<text class="iconfont icon-close" @click="closeTipClose"></text>
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
		props: {
			active: {
				type: Number,
				default: _ => {
					return ''
				}
			},
			list: {
				type: Boolean,
				default: _ => {
					return false
				}
			}
		},
		data() {
			return {
				tipClose: uni.getStorageSync('tabClose') || 'open'
			}
		},
		methods: {
			// 钥匙管理
			listChange() {
				if (!uni.getStorageSync('tabClose')) {
					uni.setStorageSync('tabClose', 'close')
					this.tipClose = 'close'
				}
				this.$emit('listChange', !this.list)
			},
			// 关闭提示
			closeTipClose() {
				uni.setStorageSync('tabClose', 'close')
				this.tipClose = 'loading'
				setTimeout(() => {
					this.tipClose = 'close'
				}, 1000)
			},
			// 更改页面
			tabChange(idx) {
				if (idx === this.active) {
					// 如果是首页地图
					if (idx === 0) {
						if(!this.currentLocationInfo.map){
							uni.navigateTo({
								url: `/pagesB/List/nearbyStore/nearbyStore?longitude=119.95000499999992&latitude=31.77351499999999&store_type=1`
							})
							return;
						}
						this.currentLocationInfo.map.getCenterLocation({
							success: (res) => {
								uni.navigateTo({
									url: `/pagesB/List/nearbyStore/nearbyStore?longitude=${ res.longitude }&latitude=${ res.latitude }&store_type=1`
								})
							}
						})
					}
					return
				}
				this.$emit('tabChange', idx)
				this.$emit('listChange', false)
			}
		}
	}
</script>

<style lang="scss" scoped>
	.footer-bottom-navbar {
		position: relative;
		z-index: 3;
		background-color: #fff;
		height: 164upx;
		display: flex;
		align-items: center;
		justify-content: space-between;
		padding-left: 125upx;
		padding-right: 125upx;
		.tip {
			width: 100%;
			position: absolute;
			z-index: 1;
			background-color: rgba($color: #8ec396, $alpha: .7);
			height: 68upx;
			display: flex;
			align-items: center;
			justify-content: center;
			top: -68upx;
			left: 0;
			border-top-left-radius: 10upx;
			border-top-right-radius: 10upx;
			.title {
				color: #fff;
				font-size: 30upx;
			}
			.icon-close {
				position: absolute;
				z-index: 1;
				right: 20upx;
				color: #fff;
				font-size: 40upx;
			}
			&:before {
				content: '';
				position: absolute;
				z-index: 1;
				width: 0;
				height: 0;
				left: 0;
				right: 0;
				bottom: -18upx;
				margin-left: auto;
				margin-right: auto;
				border-style: solid;
				border-width: 10upx;
				border-color: rgba($color: #8ec396, $alpha: .7) transparent transparent transparent;
			}
		}
		.tip.close {
			animation: scaleY .35s both ease;
		}
		.item {
			color: rgba($color: #787878, $alpha: .5);
			image {
				width: 55upx;
				height: 55upx;
				margin-left: auto;
				margin-right: auto;
			}
			.h4 {
				font-size: 18upx;
				text-align: center;
				margin-top: 10upx;
			}
			&.active {
				color: #429435;
			}
		}
		.center {
			image {
				width: 122upx;
				height: 122upx;
			}
		}
	}
	
	@keyframes scaleY {
		0% {
			transform: scaleY(1);
		}
		100% {
			transform: scaleY(0);
		}
	}
</style>