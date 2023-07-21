<template>
	<view class="content">
		<template v-if="list.length">
			<view class="item" v-for="(item, index) in list" :key="index">
				<view class="img-box" @click="item.show = !item.show">
					<image lazy-load :src="baseUrl + item.store_image" mode="aspectFill"></image>
					<view class="title">{{ item.store_name }}</view>
					<view :class="['status', item.status ? 'open' : 'close']">{{ item.status ? '已开启' : '已关闭' }}</view>
					<view class="switch-btn" @click.stop="switchStore(item)">
						<text class="iconfont icon-switch"></text>{{ item.status ? '关闭' : '开启' }}
					</view>
				</view>
				<view class="room-list">
					<view v-if="item.show">
						<view class="inner-item" v-for="(item2, index) in item.roomList" :key="index" @click="actionSheet(item2)">
							<view class="pic-contain">
								<image :src="baseUrl + item2.room_image" mode="aspectFill"></image>
								<view :class="['status', item2.status ? 'open' : 'close']">
									{{ item2.status ? '已开启' : '已关闭' }}</view>
							</view>
							<view class="r-info">
								<view class="top">{{ item2.room_name }}</view>
								<view class="m">
									<view class="l">{{ item2.room_label }}</view>
								</view>
								<view class="foot">
									<view class="l">
										<text class="danger">￥{{ item2.room_price }}</text>
										<text
											class="code">/小时</text>
									</view>
									<view class="r"><text
											class="iconfont icon-setup"></text>操作</view>
								</view>
							</view>
						</view>
					</view>
					<view class="check-btn"  @click="item.show = !item.show">
						<text :class="['iconfont', item.show ? 'icon-up' : 'icon-down']"></text>查看房间
					</view>
				</view>
			</view>
			<view class="append-more-text">{{ appendMoreText }}</view>
		</template>
		<template v-else>
			<NoData></NoData>
		</template>
		<ActionSheet ref="action" @itemClick="itemClick"></ActionSheet>
	</view>
</template>

<script>
	import NoData from '@/components/NoData.vue'
	import ActionSheet from '@/components/ActionSheet.vue'

	export default {
		components: {
			NoData,
			ActionSheet
		},
		data() {
			return {
				baseUrl: this.$baseUrl,
				list: [],
				page: 1,
				limit: 20,
				appendMoreText: '上滑加载更多',
				roomCurtData: {}
			}
		},
		onHide() {
			this.$refs.action.actionConfig.specClass = 'none'
		},
		methods: {
			// 房间功能的操作
			async actionSheet(item) {
				this.roomCurtData = item
				let list = ['开房间门', '开大门', '开电', '关电']
				if (item.status) {
					list.unshift('关闭房间')
				} else {
					list.unshift('开启房间')
				}
				this.$refs.action.actionConfig = {
					list,
					type: 0,
					isBorderColor: false,
					specClass: 'show'
				}
			},
			async itemClick(index) {
				if (index === 0) {
					uni.showModal({
						title: '温馨提示',
						content: `您确定要${ this.roomCurtData.status ? '关闭' : '开启' }该房间吗?`,
						success: async (btn) => {
							if (btn.confirm) {
								uni.showLoading({
									title: '请稍后...',
									mask: true
								})
								const data = await this.$apiRequest({
									url: '/store.Room/editStatus',
									data: {
										room_id: this.roomCurtData.room_id,
										status: this.roomCurtData.status ? 0 : 1
									}
								})
								uni.hideLoading()
								uni.showToast({
									title: !this.roomCurtData.status ? '开启成功' : '关闭成功',
									icon: 'success'
								})
								await this.getStoreList()
							}
						}
					})
				} else {
					uni.showLoading({
						title: '请稍后...',
						mask: true
					})
					const data = await this.$apiRequest({
						url: '/store.Room/openDevice',
						data: {
							room_id: this.roomCurtData.room_id,
							type: index
						}
					})
					uni.hideLoading()
					uni.showToast({
						title: data.msg,
						icon: 'success'
					})
				}
			},
			// 关闭/开启门店
			switchStore(item) {
				uni.showModal({
					title: '温馨提示',
					content: `您确定要${ item.status ? '关闭' : '开启' }该门店吗?`,
					success: async (btn) => {
						if (btn.confirm) {
							uni.showLoading({
								title: '请稍后...',
								mask: true
							})
							const data = await this.$apiRequest({
								url: '/store.Store/editStatus',
								data: {
									store_id: item.store_id,
									status: item.status ? 0 : 1
								}
							})
							uni.hideLoading()
							uni.showToast({
								title: !item.status ? '开启成功' : '关闭成功',
								icon: 'success'
							})
							await this.getStoreList()
						}
					}
				})
			},
			// 获取门店列表
			async getStoreList() {
				const data = await this.$apiRequest({
					url: '/store.Store/list',
					data: {
						page: this.page,
						limit: this.limit
					}
				})
				if (data.list.length) {
					data.list.forEach(item => {
						item.show = false
						this.list.push(item)
					})
					if (this.page === 1) {
						this.list[0].show = true
					}
					this.appendMoreText = '上滑加载更多'
					if (this.list.length < this.limit) {
						this.appendMoreText = '没有更多数据了'
					}
				} else {
					this.appendMoreText = '没有更多数据了'
				}
				uni.stopPullDownRefresh()
			}
		},
		onReachBottom() {
			if (this.appendMoreText !== '上滑加载更多') {
				return
			}
			this.page += 1
			this.appendMoreText = '正在加载更多'
			this.getStoreList()
		},
		onPullDownRefresh() {
			this.page = 1
			this.list = []
			this.getStoreList()
		},
		mounted() {
			this.getStoreList()
		}
	}
</script>

<style scoped lang="scss">
	@import 'index.scss';
</style>
