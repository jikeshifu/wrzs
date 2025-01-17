<template>
	<view class="content">
		<Topbar title="商品详情"></Topbar>
		<wLoading v-if="pageLoading"></wLoading>
		<template v-else>
			<view class="swiper-pics">
				<view class="swiper-contain">
					<view class="badge">1/1</view>
					<swiper circular :autoplay="true" :interval="5000" :duration="500">
						<swiper-item>
							<image :src="baseUrl + SHGData.goods_image" mode="aspectFill"></image>
						</swiper-item>
					</swiper>
				</view>
				<view class="caption">{{ SHGData.goods_name }}</view>
				<view class="flex-t">
					<view class="left">
						<text>￥</text>{{ SHGData.goods_price }}
					</view>
				</view>
			</view>
			<view class="room-desc">
				<view class="title">商品说明</view>
				<uParse :content="SHGData.goods_about" :noData="' '"></uParse>
			</view>
			<view class="empty-fixed"></view>
			<view class="fixed-bottom">
				<view class="left">
					<view class="h4">
						<text>￥</text>{{ SHGData.goods_price }}
					</view>
					<view class="p">共{{ SHGData.stock }}件</view>
				</view>
				<view class="right">
					<button class="btn2" type="primary" @click="submit" :disabled="!SHGData.stock">{{ SHGData.stock ? '立即购买' : '已售馨' }}</button>
				</view>
			</view>
		</template>
	</view>
</template>

<script>
	import uParse from '@/components/u-parse/u-parse.vue'
	
	export default {
		components: {
			uParse
		},
		data() {
			return {
				goodsID: '',
				lockStatus: '',
				SHGData: {},
				pageLoading: true
			}
		},
		methods: {
			// 获取商品信息
			async getGoodsDetail() {
				await this.$apiRequest({
					url: '/cabinet.Goods/info',
					data: {
						goods_id: this.goodsID
					}
				}).then(data => {
					this.SHGData = data.info
				}).catch(() => {})
				this.pageLoading = false
			},
			// 确定购买
			async submit() {
				if (this.lockStatus === 1) {
					this.$showMsg('该售货柜异常，暂无法使用')
					return
				}
				uni.showLoading({
					title: '正在下单...',
					mask: true
				})
				const data = await this.$apiRequest({
					url: '/cabinet.Order/placeOrder',
					data: {
						goods_id: this.SHGData.goods_id
					}
				})
				uni.hideLoading()
				uni.navigateTo({
					url: `/pagesSHG/payOrder/payOrder?goodsName=${ this.SHGData.goods_name }&goodsImg=${ this.SHGData.goods_image }&goodsPrice=${ this.SHGData.goods_price }&goodsNum=${ this.SHGData.stock }&orderID=${ data.order_id }&money=${ data.money }`
				})
			}
		},
		onLoad(options) {
			this.goodsID = options.goodsID
			this.lockStatus = +options.lockStatus
			this.getGoodsDetail()
		}
	}
</script>

<style lang="scss">
	@import 'index.scss';
	@import '@/components/u-parse/u-parse.css';
</style>
