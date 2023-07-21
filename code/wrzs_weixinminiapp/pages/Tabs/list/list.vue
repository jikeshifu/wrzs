<template>
	<view class="content">
		<view class="close-btn" @click="closeList">
			<view class="flex">
				<text class="iconfont icon-down"></text>
			</view>
		</view>
		<wLoading v-if="pageLoading"></wLoading>
		<template v-else>
			<template v-if="list.length">
				<view class="scroll-view">
					<view class="title">预定管理</view>
					<scroll-view scroll-y @scrolltolower="appendMore">
						<view style="height:30upx"></view>
						<view class="item" v-for="(item, index) in list" :key="index">
							<view class="mobile" @click="makePhoneCall(item.contact)">联系</view>
							<view class="store-name">{{ item.store_name }}</view>
							<view class="flex-head">
								<view class="left">
									<image lazy-load
										:src="baseUrl + item.room_info.room_image"
										mode="aspectFill"></image>
									<view class="info">
										<view class="tt">{{ item.room_info.room_name }}</view>
										<view class="bb">
											<view class="add">
												<text class="iconfont icon-zb1"></text>{{ item.address }}
											</view>
											<view class="time">
												<text class="iconfont icon-date"></text>{{ splitDate(+(item.start_time + '000')).date }}{{ splitDate(+(item.start_time + '000')).hm }}
												<text class="half"></text>至<text class="half"></text>{{ splitDate(+(item.end_time + '000')).date }}{{ splitDate(+(item.end_time + '000')).hm }}
											</view>
										</view>
									</view>
								</view>
								<view class="right">
									<view class="flex-btn">
										<button type="default" open-type="share" :data-keyID="item.key_id" :data-share="item" v-if="item.uid === userInfo.member_id">
											<text class="iconfont icon-share"></text>
										</button>
										<button type="default" @click="openLocation(item)">
											<text class="iconfont icon-zb2"></text>
										</button>
									</view>
									<view class="ft" @click="pageJump(item)">
										<text class="iconfont icon-shg"></text>售货柜
									</view>
								</view>
							</view>
							<view class="flex-end-type1" v-if="item.room_status === 2">
								<view class="left">
									<view class="l2">
										<text class="iconfont icon-clock"></text>剩余
									</view>
									<UniCountDown @timeup="freshList" backgroundColor="transparent" splitorColor="#5665C9" :showDay="item.times.hours >= 24 ? true : false" color="#5665C9" :hour="item.times.hours" :minute="item.times.minutes" :second="item.times.seconds"></UniCountDown>
								</view>
								<view class="right">
									<button class="vlot" type="warn" @click="renew(item.order_id)">续房</button>
									<button class="door" type="default" @click="openDoor(item.key_id, '/user.Room/openPublicLock')" v-if="item.room_info.dm_status">
										<view class="icon">
											<text class="iconfont icon-key"></text>
										</view>
										<view class="p">大门</view>
									</button>
									<button class="door" type="default" @click="openDoor(item.key_id, '/user.Room/openLock')">
										<view class="icon">
											<text class="iconfont icon-key"></text>
										</view>
										<view class="p">房门</view>
									</button>
								</view>
							</view>
							<view class="flex-end-type2" v-if="item.room_status === 1">
								<view class="left">
									<text class="iconfont icon-clock"></text>距离预约时间
								</view>
								<UniCountDown @timeup="freshList" backgroundColor="transparent" splitorColor="#ffa026" :showDay="item.times.hours >= 24 ? true : false" color="#ffa026" :hour="item.times.hours" :minute="item.times.minutes" :second="item.times.seconds"></UniCountDown>
							</view>
						</view>
						<UniLoadMore :status="appendMoreStatus"></UniLoadMore>
					</scroll-view>
				</view>
			</template>
			<template v-else>
				<NoData title="没有预约订单"></NoData>
			</template>
		</template>
	</view>
</template>

<script>
	import { mapState } from 'vuex'
	import { splitDate } from '@/components/hours/index.js'
	import UniCountDown from '@/components/uni-countdown/uni-countdown.vue'

	export default {
		computed: {
			...mapState({
				userInfo: state => state.USER.userInfo
			})
		},
		components: {
			UniCountDown
		},
		data() {
			return {
				splitDate,
				pageLoading: true,
				page: 1,
				limit: 50,
				appendMoreStatus: 'more',
				list: []
			}
		},
		methods: {
			// 联系电话
			makePhoneCall(phoneNumber) {
				uni.makePhoneCall({
					phoneNumber
				})
			},
			// 页面跳转
			async pageJump(data) {
				if (!data.cabinet) {
					this.$showMsg('该房间暂无售货柜')
					return
				}
				await this.$store.dispatch('STORE/initStoreData', data)
				uni.navigateTo({
					url: '/pagesSHG/index/index'
				})
			},
			// 打开门
			async openDoor(key_id, url) {
				uni.showLoading({
					title: '正在开门...',
					mask: true
				})
				
				await this.$apiRequest({
					url,
					data: {
						key_id
					}
				}).then(() => {
					uni.hideLoading()
					uni.showToast({
						title: '开门成功',
						icon: 'success'
					})
				})
			},
			// 续费
			renew(orderID) {
				this.closeList()
				uni.navigateTo({
					url: `/pagesB/renew/renew?orderID=${ orderID }`
				})
			},
			// 刷新列表
			freshList() {
				this.page = 1
				this.getManageList()
			},
			// 打开地图
			openLocation(item) {
				uni.openLocation({
					longitude: item.longitude,
					latitude: item.latitude
				})
			},
			appendMore() {
				if (this.appendMoreStatus !== 'more') {
					return
				}
				this.page += 1
				this.appendMoreStatus = 'loading'
				this.getManageList()
			},
			// 关闭
			closeList() {
				this.$emit('listChange', false)
			},
			// 获取管理列表
			async getManageList() {
				await this.$apiRequest({
					url: '/user.Room/list',
					data: {
						page: this.page,
						limit: this.limit
					}
				}).then(data => {
					if (data.list.length) {
						data.list.forEach(item => {
							// 如果是未开始
							if (item.room_status === 1) {
								item.times = this.$secondToTimeStr(item.syKs_time)
							}
							// 如果是进行中
							if (item.room_status === 2) {
								item.times = this.$secondToTimeStr(item.syJs_time)
							}
							if (this.page !== 1) {
								this.list.push(item)
							}
						})
						if (this.page === 1) {
							this.list = data.list
						}
						this.appendMoreStatus = 'more'
						if (this.list.length < this.limit) {
							this.appendMoreStatus = 'noMore'
						}
					} else {
						if (this.page === 1) {
							this.list = []
						}
						this.appendMoreStatus = 'noMore'
					}
				}).catch(() => {})
				this.pageLoading = false
			},
			async init() {
				if (!uni.getStorageSync('token')) {
					await this.$store.dispatch('USER/initUserInfo')
				}
				this.getManageList()
			}
		},
		mounted() {
			this.init()
		}
	}
</script>

<style lang="scss" scoped>
	@import 'index.scss';
</style>
