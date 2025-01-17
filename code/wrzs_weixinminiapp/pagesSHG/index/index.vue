<template>
	<view class="content">
		<Topbar title="售货柜"></Topbar>
		<view class="room-info">
			<image :src="baseUrl + storeData.room_info.room_image" mode="aspectFill"></image>
			<view class="info">
				<view class="top">{{ storeData.room_info.room_name }}</view>
				<view class="foot">
					<view class="p">
						<text class="iconfont icon-zb1"></text>{{ storeData.address }}
					</view>
					<view class="p">
						<text class="iconfont icon-rili"></text>{{ storeData.label }}
					</view>
				</view>
			</view>
		</view>
		<view class="row">
			<view class="col" v-for="(item, index) in storeData.cabinet_goods" :key="index">
				<view class="item" @click="pageJump(item)">
					<view class="img-contain">
						<view class="tag">{{ item.cabinet_number }}</view>
						<image :src="baseUrl + item.goods_image" mode="aspectFill"></image>
					</view>
					<view class="caption">
						<text>{{ item.label }}</text>{{ item.goods_name }}
					</view>
					<view class="foot">
						<view class="left">
							<text>￥</text>{{ item.goods_price }}
						</view>
						<view class="right">
							<view class="error" v-if="item.lock_status === 1">柜门异常</view>
							<view class="null" v-if="item.stock === 0">
								<image :src="baseUrl + '/static/imgs/ysx.png'" mode="aspectFill"></image>
							</view>
						</view>
					</view>
				</view>
			</view>
		</view>
	</view>
</template>

<script>
	import { mapState } from 'vuex'
	
	export default {
		computed: {
			...mapState({
				storeData: state => state.STORE.storeData
			})
		},
		methods: {
			// 跳转详情
			pageJump(data) {
				uni.navigateTo({
					url: `/pagesSHG/detail/detail?goodsID=${ data.goods_id }&lockStatus=${ data.lock_status }`
				})
			}
		}
	}
</script>

<style lang="scss">
	@import 'index.scss';
</style>
