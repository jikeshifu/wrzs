<template>
	<view class="content">
		<view class="fixed-topbar">
			<view :style="{ height: menuButtonBoundingClientRect.top + 'px' }"></view>
			<view class="title" :style="{ height: menuButtonBoundingClientRect.height + 'px' }">
				<view @click="back" class="iconfont icon-left"></view>
			</view>
			<view :style="{ height: (menuButtonBoundingClientRect.top / 2) + 'px' }"></view>
		</view>
		<view class="swiper">
			<text class="dot">{{ (current + 1) }}/1</text>
			<swiper @change="swiperChange" :current="current" :circular="true" :autoplay="true" :interval="5000" :duration="500">
				<swiper-item>
					<view class="swiper-item">
						<image :src="baseUrl + storeData.store_image" mode="aspectFill"></image>
					</view>
				</swiper-item>
			</swiper>
		</view>
		<view class="store-desc">
			<view class="title">{{ storeData.store_name }}<text>已售{{ storeData.store_sold }}</text></view>
			<view class="flex-items">
				<view class="left">
					<view class="tp">{{ storeData.label }}</view>
					<view class="bt">
						<text class="iconfont icon-zb1"></text>{{ storeData.address }}
					</view>
				</view>
				<view class="right" @click="openMap">
					<text class="iconfont icon-zb2"></text>{{ storeData.distance }}km
				</view>
			</view>
			<view class="new-cus-active" @click="makePhoneCall">
				<view class="p">联系电话</view>
				<view class="p">{{ storeData.contact }} - 点击即可拨打</view>
			</view>
		</view>
		<view class="scroll-view">
			<view class="title">所有房间</view>
			<view class="scroll-contain">
				<wLoading v-if="pageLoading"></wLoading>
				<scroll-view scroll-y v-else @scrolltolower="appendMore">
					<template v-if="list.length">
						<view class="item" v-for="(item, index) in list" :key="index" @click="jumpToRoomDetail(item)">
							<image lazy-load :src="baseUrl + item.room_image" mode="aspectFill"></image>
							<view class="info">
								<view class="l">
									<view class="tp">{{ item.room_name }}</view>
									<view class="bt">
										<view class="head">
											<text>{{ item.room_label }}</text>
										</view>
										<template v-if="storeData.store_type !== 3">
											<view class="body">{{ item.starting_time }}小时起订</view>
											<view class="foot">
												<text class="small">￥</text>
												<text class="money">{{ item.room_price }}</text>
												<text class="code">时</text>
												<text class="sale">已售{{ item.room_sold }}</text>
											</view>
										</template>
									</view>
								</view>
								<view class="r" v-if="storeData.store_type !== 3">预定</view>
								<view class="r" v-else>详情</view>
							</view>
						</view>
						<UniLoadMore :status="appendMoreStatus"></UniLoadMore>
					</template>
					<NoData v-else title="该门店还没有房间"></NoData>
				</scroll-view>
			</view>
		</view>
	</view>
</template>

<script>
	import wLoading from '@/components/w-loading.vue'
	import NoData from '@/components/NoData.vue'
	import UniLoadMore from '@/components/uni-load-more.vue'
	import { mapState } from 'vuex'
	
	const app = getApp()
	
	export default {
		computed: {
			...mapState({
				storeData: state => state.STORE.storeData
			})
		},
		components: {
			wLoading,
			NoData,
			UniLoadMore
		},
		data() {
			return {
				menuButtonBoundingClientRect: app.globalData.menuButtonBoundingClientRect,
				current: 0,
				pageLoading: true,
				page: 1,
				limit: 50,
				list: [],
				appendMoreStatus: 'more'
			}
		},
		methods: {
			makePhoneCall() {
				uni.makePhoneCall({
					phoneNumber: this.storeData.contact
				})
			},
			// 跳转房间详情
			jumpToRoomDetail(data) {
				// await this.$store.dispatch('ROOM/initRoomData', data)
				uni.navigateTo({
					url: `/pagesB/roomDetail/roomDetail?roomID=${ data.room_id }`
				})
			},
			// 打开地图
			openMap() {
				uni.openLocation({
					latitude: this.storeData.latitude,
					longitude: this.storeData.longitude
				})
			},
			swiperChange(e) {
				this.current = e.detail.current
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
				this.page += 1
				this.appendMoreStatus = 'loading'
				this.getRoomList()
			},
			// 获取房间列表
			async getRoomList() {
				await this.$apiRequest({
					url: '/store.Room/list',
					data: {
						page: this.page,
						limit: this.limit,
						store_id: this.storeData.store_id
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
		mounted() {
			this.getRoomList()
		}
	}
</script>

<style lang="scss">
	@import 'index.scss';
</style>
