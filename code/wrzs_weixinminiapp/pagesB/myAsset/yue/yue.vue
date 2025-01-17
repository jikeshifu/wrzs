<template>
	<view class="content">
		<Topbar title="钱包列表"></Topbar>
		<view class="bg-wrap">
			<image :src="baseUrl + '/static/imgs/myAsset/yue/bg.png'" mode="aspectFill"></image>
			<view class="abs-contt">
				<view class="head">
					<view class="left">账户总余额(元)</view>
					<view class="right" @click="pageJump('/pagesB/List/wallet/wallet')">钱包明细<text class="iconfont icon-right"></text></view>
				</view>
				<view class="foot" @click="pageJump('/pagesB/recharge/recharge')">{{ myAsset.money }}<text>账户充值</text></view>
			</view>
		</view>
		<!-- <view class="h4" v-if="list.length">以下店铺有余额</view>
		<scroll-view scroll>
			<wLoading v-if="pageLoading"></wLoading>
			<template v-else>
				<template v-if="list.length">
					<view style="height:20upx;"></view>
					<view class="item" v-for="item in list" :key="item.store_id" @click="pageJump(`/pagesB/recharge/recharge?store_id=${ item.store_id }&city_name=${ item.store.city }&store_name=${ item.store.store_name }`)">
						<view class="head">
							<image :src="baseUrl + item.store.store_image" mode="aspectFill"></image>
							<view class="info">
								<view class="title">{{ item.store.store_name }}</view>
								<view class="bt">
									<view class="p">
										<text class="iconfont icon-zb1"></text>{{ item.store.address }}
									</view>
									<view class="p">
										<text class="iconfont icon-rili"></text>{{ item.store.label }}
									</view>
								</view>
							</view>
						</view>
						<view class="foot">
							<view class="left">
								余额<text>￥</text><text class="big">{{ item.money }}</text>
							</view>
							<view class="right">店铺充值</view>
						</view>
					</view>
				</template>
				<NoData v-else title="还没有店铺余额"></NoData>
			</template>
		</scroll-view> -->
	</view>
</template>

<script>
	import {
		mapState
	} from 'vuex'
	
	export default {
		computed: {
			...mapState({
				myAsset: state => state.USER.myAsset
			})
		},
		data() {
			return {
				pageLoading: true,
				list: []
			}
		},
		methods: {
			// 获取所有钱包列表
			async getMoneyList() {
				await this.$apiRequest({
					url: '/member.MemberWallet/list'
				}).then(data => {
					this.list = data.list
				}).catch(_ => {})
				this.pageLoading = false
			},
			// 页面跳转
			pageJump(url) {
				uni.navigateTo({
					url
				})
			}
		},
		mounted() {
			// this.getMoneyList()
		}
	}
</script>

<style lang="scss">
	@import 'index.scss';
</style>
