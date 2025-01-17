<template>
	<view class="content">
		<view class="fixed-topbar">
			<image class="bg" :src="baseUrl + '/static/imgs/topbar_bg.png'" mode="widthFix"></image>
			<view class="fixed-item">
				<view :style="{ height: menuButtonBoundingClientRect.top + 'px' }"></view>
				<view class="title" :style="{ height: menuButtonBoundingClientRect.height + 'px' }">
					<view class="iconfont icon-left" @click="back"></view>
				</view>
				<view :style="{ height: (menuButtonBoundingClientRect.top / 2) + 'px' }"></view>
			</view>
			<view class="flex-item">
				<view class="left">
					<view class="tab active">{{ form.store_type === '' ? '门店列表' : form.store_type === '1' ? '共享茶室' : form.store_type === '2' ? '舞社空间' : form.store_type === '3' ? '民宿空间' : '' }}</view>
				</view>
				<view class="right" @click="back">返回地图<text class="iconfont icon-zb1"></text></view>
			</view>
		</view>
		<view class="scroll-view">
			<scroll-view id="scroll-view" scroll-y @scrolltolower="appendMore" @scroll="scrollChange">
				<wLoading v-if="pageLoading"></wLoading>
				<template v-else>
					<template v-if="list.length">
						<view class="item" v-for="(item2, index2) in list" :key="index2" @click="jumpToStoreDetail(item2, index2)">
							<!-- <template v-if="item2.store_type === 1">
								<image class="bg" lazy-load :src="baseUrl + item2.store_image" mode="aspectFill"></image>
							</template>
							<template v-else>
								<view class="wscb">
									<template v-if="item2.vid">
										<player-component :id="`play${ index2 }`" :vid="item2.vid" :autoplay="false"></player-component>
									</template>
									<template v-else>
										<image lazy-load :src="baseUrl + item2.store_image" mode="aspectFill"></image>
									</template>
								</view>
							</template> -->
							<view class="wscb">
								<template v-if="item2.vid">
									<player-component :id="`play${ index2 }`" :vid="item2.vid" :autoplay="false"></player-component>
								</template>
								<template v-else>
									<image lazy-load :src="baseUrl + item2.store_image" mode="aspectFill"></image>
								</template>
							</view>
							<view class="card-box-head">
								{{ item2.store_name }}<text>已售{{item2.store_sold}}</text>
							</view>
							<view class="card-box-bottom" @click.stop="jumpToStoreDetail2(item2)" :class="[item2.store_type !== 1 ? 'relative' : '']">
								<view class="label-arr">
									<text v-for="(item3, index3) in item2.label.split('|') " :key="index3">{{ item3 }}</text>
								</view>
								<view class="flex">
									<view class="l">
										<text class="iconfont icon-zb1"></text>{{ item2.address }}
									</view>
									<view class="r" v-if="item2.store_type === 2">
										<text>￥</text>{{ item2.room_price }}
									</view>
								</view>
								<view class="flex" v-if="item2.store_type !== 1">
									<view class="l">
										<text>￥</text>{{ item2.room_price }}
									</view>
									<view class="r">查看详情</view>
								</view>
							</view>
						</view>
						<UniLoadMore :status="item2.appendMoreStatus"></UniLoadMore>
						<view :style="{ height: (videoH / 2) + 'px' }" v-if="item2.store_type !== 1"></view>
					</template>
					<template v-else>
						<NoData title="还没有门店"></NoData>
					</template>
				</template>
			</scroll-view>
		</view>
	</view>
</template>

<script>
	const app = getApp()

	export default {
		data() {
			return {
				menuButtonBoundingClientRect: app.globalData.menuButtonBoundingClientRect,
				form: {
					longitude: '',
					latitude: '',
					store_type: ''
				},
				list: [],
				page: 1,
				limit: 20,
				appendMoreStatus: 'more',
				pageLoading: true,
				videoH: 0,
				isClick: false
			}
		},
		methods: {
			// 跳转门店详情
			async jumpToStoreDetail(data, idx) {
				if (data.vid) {
					this.list.forEach((item, index) => {
						if (item.vid && index !== idx) {
							let s = this.selectComponent(`#play${ index }`)
							if (s.state === 'playing') {
								s.pause()
							}
						}
					})
					let p = this.selectComponent(`#play${ idx }`)
					if (p) {
						if (p.state === 'canplay' || p.state === 'pause' || p.state === 'loadStart' || p.state === 'buffering') {
							this.isClick = false
							p.play()
						} else {
							this.isClick = true
							p.pause()
						}
					}
					return
				}
				await this.$store.dispatch('STORE/initStoreData', data)
				uni.navigateTo({
					url: '/pagesB/storeDetail/storeDetail'
				})
			},
			async jumpToStoreDetail2(data) {
				await this.$store.dispatch('STORE/initStoreData', data)
				uni.navigateTo({
					url: '/pagesB/storeDetail/storeDetail'
				})
			},
			back() {
				uni.navigateBack({
					delta: 1
				})
			},
			appendMore() {
				if (this.appendMoreStatus !== 'more') {
					return
				}
				this.appendMoreStatus = 'loading'
				this.page += 1
				this.getNearbyStore()
			},
			// 获取附近门店列表
			async getNearbyStore() {
				await this.$apiRequest({
					url: '/store.Store/nearby',
					data: {
						page: this.page,
						limit: this.limit,
						...this.form
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
					this.$nextTick(() => {
						if (this.page === 1 && this.list.length) {
							uni.createSelectorQuery().select('.item').boundingClientRect(res => {
								this.videoH = res.height
							}).exec()
							if (this.list[0] && this.list[0].vid) {
								setTimeout(() => {
									this.selectComponent('#play0').play()
								}, 2000)
								return
							} else if (this.list[1] && this.list[1].vid) {
								setTimeout(() => {
									this.selectComponent('#play1').play()
								}, 2000)
							}
						}
					})
				}).catch(() => {})
				this.pageLoading = false
			},
			scrollChange(e) {
				if (this.isClick) {
					return
				}
				let idx = Math.round(e.detail.scrollTop / this.videoH)
				this.list.forEach((item, index) => {
					if (item.vid && index !== idx) {
						let s = this.selectComponent(`#play${ index }`)
						if (s.state === 'playing') {
							s.pause()
						}
					}
				})
				let p = this.selectComponent(`#play${ idx }`)
				if (p) {
					p.play()
				}
			}
		},
		onLoad(options) {
			console.log(options)
			this.form = options
			this.getNearbyStore()
		}
	}
</script>

<style lang="scss">
	@import 'index.scss';
</style>
