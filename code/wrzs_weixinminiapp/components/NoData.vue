<template>
	<view class="layer-mask">
		<view class="card-box">
			<image :src="baseUrl + '/static/imgs/no_data.png'"></image>
			<view class="p">{{ title }}</view>
		</view>
	</view>
</template>

<script>
	export default {
		props: {
			title: String,
			default: () => {
				return ''
			}
		}
	}
</script>

<style lang="scss" scoped>
	.layer-mask {
		position: absolute;
		z-index: 10;
		top: 0;
		left: 0;
		width: 100%;
		height: 100%;
		display: flex;
		align-items: center;
		justify-content: center;
		.card-box {
			image {
				width: 324upx;
				height: 236upx;
				margin-left: auto;
				margin-right: auto;
			}
			.p {
				text-align: center;
				font-size: 30upx;
				color: #b5b5b5;
				margin-top: 20upx;
			}
		}
	}
</style>
