<template>
	<view class="content">
		<Topbar title="商品详情" style="visibility:hidden;"></Topbar>
		<Topbar title="商品详情" style="position:fixed;top:0;left:0;width:100%;z-index:1;"></Topbar>
		<view class="swiper-pics">
			<view class="swiper-contain">
				<view class="badge">1/1</view>
				<swiper circular :autoplay="true" :interval="5000" :duration="500">
					<swiper-item>
						<image :src="baseUrl + goodsData.goods_image" mode="aspectFill"></image>
					</swiper-item>
				</swiper>
			</view>
			<view class="caption">{{ goodsData.goods_name }}</view>
			<view class="flex-t">
				<view class="left">
					<text>￥</text>{{ goodsData.goods_price }}
				</view>
				<view class="right">
					<image @click="reduceGoods" :src="baseUrl + '/static/imgs/reduce.png'" mode="aspectFill"></image>
					<text>{{ count }}</text>
					<image @click="addGoods" :src="baseUrl + '/static/imgs/add.png'" mode="aspectFill"></image>
				</view>
			</view>
			<view class="flex-b">
				<view class="left">已售{{ goodsData.goods_sold }}件</view>
				<view class="right">库存{{ goodsData.goods_stock }}</view>
			</view>
			<view class="panel">{{ goodsData.shipping_remarks ? goodsData.shipping_remarks : '预计3天发货' }}</view>
		</view>
		<view class="room-desc">
			<view class="title">商品说明</view>
			<uParse :content="goodsData.goods_about" :noData="' '"></uParse>
		</view>
		<view class="empty-fixed"></view>
		<view class="fixed-bottom">
			<view class="left">
				<view class="h4">
					<text>￥</text>{{ totalMoney }}
				</view>
				<view class="p">共{{ count }}件</view>
			</view>
			<view class="right">
				<button class="btn1" type="primary" @click="addGwcar">加入购物车</button>
				<button class="btn2" type="primary" @click="submit">立即购买</button>
			</view>
		</view>
		<FixedIcons></FixedIcons>
	</view>
</template>

<script>
	import { mapState, mapActions } from 'vuex'
	import uParse from '@/components/u-parse/u-parse.vue'
	import FixedIcons from '@/components/pagesC/fixed-icons.vue'
	
	export default {
		computed: {
			...mapState({
				goodsData: state => state.pagesC_GOODS.goodsData
			}),
			totalMoney() {
				return this.$calc.multiply(this.goodsData.goods_price, this.count, 2)
			}
		},
		components: {
			uParse,
			FixedIcons
		},
		data() {
			return {
				count: 1
			}
		},
		methods: {
			...mapActions({
				initGwcarData: 'pagesC_GWCAR/initGwcarData'
			}),
			// 加入购物车
			async addGwcar() {
				uni.showLoading({
					title: '加入中...',
					mask: true
				})
				await this.$apiRequest({
					url: '/shop.ShoppingCar/add',
					data: {
						goods_id: this.goodsData.goods_id,
						number: this.count
					}
				})
				uni.hideLoading()
				uni.showToast({
					title: '加入成功',
					icon: 'success'
				})
				this.initGwcarData()
			},
			// 减去商品
			reduceGoods() {
				if (this.count === 1) {
					return
				}
				this.count -= 1
			},
			// 增加商品
			addGoods() {
				if (this.count === this.goodsData.goods_stock) {
					this.$showMsg('库存不足')
					return
				}
				this.count += 1
			},
			submit() {
				uni.setStorageSync('goodsList', {
					number: 1,
					list: [{
						goods_id: this.goodsData.goods_id,
						goods_image: this.goodsData.goods_image,
						goods_price: this.goodsData.goods_price,
						goods_number: this.count,
						goods_name: this.goodsData.goods_name,
					}],
					totalMoney: this.totalMoney
				})
				uni.navigateTo({
					url: '/pagesC/payOrder/payOrder'
				})
			}
		}
	}
</script>

<style lang="scss">
	@import 'index.scss';
	@import '@/components/u-parse/u-parse.css';
</style>
