<template>
	<view class="topbar">
		<image class="bg" :src="baseUrl + '/static/imgs/topbar_bg.png'" mode="widthFix"></image>
		<view class="fixed-item">
			<view :style="{ height: menuButtonBoundingClientRect.top + 'px' }"></view>
			<view class="title" :style="{ height: menuButtonBoundingClientRect.height + 'px' }">
				<view class="iconfont icon-left" @click="back"></view>
				<view class="h1">{{ title }}</view>
			</view>
			<view :style="{ height: (menuButtonBoundingClientRect.top / 2) + 'px' }"></view>
		</view>
	</view>
</template>

<script>
	const app = getApp()

	export default {
		props: {
			title: {
				type: String,
				default: () => {
					return ''
				}
			}
		},
		data() {
			return {
				menuButtonBoundingClientRect: app.globalData.menuButtonBoundingClientRect
			}
		},
		methods: {
			back() {
				uni.navigateBack({
					delta: 1,
					success(res) {
						console.log(res)
					},
					fail(err) {
						console.log(err)
						uni.reLaunch({
							url: '/pages/Tabs/index',
							complete(cerr) {
								console.log("cerr:", cerr)
							}
						});
					}
				})
			}
		}
	}
</script>

<style lang="scss" scoped>
	.topbar {
		position: relative;
		overflow: hidden;
		background-color: #fff;

		.bg {
			position: absolute;
			z-index: 1;
			top: 0;
			left: 0;
			width: 100%;
			height: 152px;
		}

		.fixed-item {
			position: relative;
			z-index: 2;

			.title {
				position: relative;
				display: flex;
				align-items: center;
				justify-content: center;

				.h1 {
					font-size: 37upx;
				}

				.icon-left {
					position: absolute;
					z-index: 1;
					left: 0;
					padding-left: 20upx;
					padding-right: 20upx;
					font-size: 45upx;
					font-weight: bold;
				}
			}
		}
	}
</style>