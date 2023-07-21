<template>
	<view class="no-data">
		<image src="/static/imgs/no_data.png" mode="aspectFill"></image>
		<view class="p">空空如也</view>
	</view>
</template>

<style lang="scss" scoped>
	.no-data {
		position: absolute;
		z-index: 1;
		top: 20%;
		left: 0;
		width: 100%;
		image {
			width: 200upx;
			height: 200upx;
			margin-left: auto;
			margin-right: auto;
		}
		.p {
			font-size: 30upx;
			text-align: center;
			margin-top: 20upx;
			color: #a5a5a5;
		}
	}
</style>