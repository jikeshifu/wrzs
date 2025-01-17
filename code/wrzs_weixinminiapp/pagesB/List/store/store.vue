<template>
	<view class="content">
		<Topbar :title="cityName"></Topbar>
		<scroll-view class="scroll-view" scroll-y @scrolltolower="appendMore">
			<wLoading v-if="pageLoading"></wLoading>
			<template v-else>
				<template v-if="list.length">
					<view class="item" v-for="(item, index) in list" :key="index" @click="chooseStore(item)">
						<view class="left">
							<image lazy-load :src="baseUrl + item.store_image" mode="aspectFill"></image>
						</view>
						<view class="right">
							<view class="head">
								{{ item.store_name }}<text>9.9分</text>
							</view>
							<view class="bottom">
								<view class="p">{{ item.label }}</view>
								<view class="flex">
									<view class="l">
										<text class="iconfont icon-zb1"></text>{{ item.address }}
									</view>
								</view>
							</view>
						</view>
					</view>
					<UniLoadMore :status="appendMoreStatus"></UniLoadMore>
				</template>
				<NoData v-else title="还没有门店"></NoData>
			</template>
		</scroll-view>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				pageLoading: true,
				cityName: '',
				appendMoreStatus: 'more',
				page: 1,
				limit: 20,
				list: []
			}
		},
		methods: {
			// 加载更多
			appendMore() {
				if (this.appendMoreStatus !== 'more') {
					return
				}
				this.page += 1
				this.appendMoreStatus = 'loading'
				this.getStoreList()
			},
			// 获取门店列表
			async getStoreList() {
				await this.$apiRequest({
					url: '/store.Store/list',
					data: {
						city: this.cityName,
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
				}).catch((errMsg) => {})
				this.pageLoading = false
			},
			// 选择门店
			chooseStore(data) {
				const pages = getCurrentPages()
				const prevPage = pages[pages.length - 2]
				prevPage.$vm.store = data
				uni.navigateBack({
					delta: 1
				})
			}
		},
		onLoad(options) {
			this.cityName = options.cityName
			this.getStoreList()
		}
	}
</script>

<style lang="scss">
	@import 'index.scss';
</style>
