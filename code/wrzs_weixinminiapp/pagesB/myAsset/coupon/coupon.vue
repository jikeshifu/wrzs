<template>
	<view class="content">
		<Topbar title="卡券"></Topbar>
		<view class="scroll-view">
			<wLoading v-if="pageLoading"></wLoading>
			<scroll-view scroll-y v-else @scrolltolower="appendMore">
				<template v-if="list.length">
					<view class="item" v-for="(item, index) in list" :key="index" @click="checkCoupon(item)">
						<image :src="baseUrl + '/static/imgs/myAsset/coupon/bg.png'" mode="widthFix"></image>
						<view class="card-cnt">
							<view class="head">
								<view class="h4">{{ item.title }}</view>
								<view class="p">有效期至{{ $dateFormatter(+(item.end_time + '000'), 'yyyy-mm-dd hh:mm:ss') }}</view>
							</view>
							<view class="foot">
								<text class="big">{{ item.money }}</text>元
							</view>
						</view>
					</view>
					<UniLoadMore :status="appendMoreStatus"></UniLoadMore>
				</template>
				<template v-else>
					<NoData title="暂无卡券"></NoData>
				</template>
			</scroll-view>
		</view>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				pageLoading: true,
				appendMoreStatus: 'more',
				list: [],
				limit: 10,
				page: 1,
				fromPayOrder: false
			}
		},
		methods: {
			// 选择优惠券
			checkCoupon(data) {
				if (!this.fromPayOrder) {
					return
				}
				const pages = getCurrentPages()
				const prevPage = pages[pages.length - 2]
				prevPage.$vm.couponInfo = data
				uni.navigateBack({
					delta: 1
				})
			},
			// 加载更多
			appendMore() {
				if (this.appendMoreStatus !== 'more') {
					return
				}
				this.appendMoreStatus = 'loading'
				this.page += 1
				this.getCouponList()
			},
			// 获取卡券列表
			async getCouponList() {
				await this.$apiRequest({
					url: '/user.Coupons/list',
					data: {
						page: this.page,
						limit: this.limit
					}
				}).then(data => {
					if (data.list.length) {
						data.list.forEach(item => {
							this.list.push(item)
						})
						this.appendMoreStatus = 'more'
						if (this.list.length < this.limit) {
							this.appendMoreStatus = 'noMore'
						}
					} else {
						this.appendMoreStatus = 'noMore'
					}
				}).catch(() => {})
				this.pageLoading = false
			}
		},
		onLoad(options) {
			this.fromPayOrder = options.fromPayOrder
			this.getCouponList()
		}
	}
</script>

<style lang="scss">
	@import 'index.scss';
</style>
