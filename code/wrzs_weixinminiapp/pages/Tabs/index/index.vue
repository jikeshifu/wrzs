<template>
	<view class="index">
		<view class="out-box">
			<view class="fixed-topbar">
				<image class="bg" :src="baseUrl + '/static/imgs/topbar_bg.png'" mode="widthFix"></image>
				<view class="fixed-item">
					<view :style="{ height: menuButtonBoundingClientRect.top + 'px' }"></view>
					<view class="title" :style="{ height: menuButtonBoundingClientRect.height + 'px' }">
						<image class="logo" :src="baseIMG + '/logo.png'" mode="aspectFill"></image>
						<view @click="pageJump(`/pagesB/List/citys/citys?city=${ picker.range[picker.value].city_name }`)" class="picker-view">{{ picker.range[picker.value].city_name }}<text class="iconfont icon-down"></text></view>
					</view>
					<view :style="{ height: (menuButtonBoundingClientRect.top / 2) + 'px' }"></view>
				</view>
			</view>
			<view class="banner" v-if="advList.length">
				<swiper :autoplay="true" :circular="true">
					<swiper-item v-for="(item, index) in advList" :key="index">
						<image :src="baseUrl + item.src" mode="aspectFill" @click="setAdvData(item)"></image>
					</swiper-item>
				</swiper>
			</view>
			<view class="sus-btns" :class="[advList.length ? '' : 'mt-18']">
				<view class="left">
					<!-- <view @click="pageJump('/pagesC/index/index')">
						<image :src="baseUrl + '/static/imgs/Tabs/index/sc.png'" mode="aspectFill"></image>
					</view> -->
					<view @click="pageJump('/pagesB/jmhz/jmhz')">
						<image :src="baseUrl + '/static/imgs/Tabs/index/jm.png'" mode="aspectFill"></image>
					</view>
				</view>
				<view class="right">
					<button open-type="contact">
						<image class="kf" :src="baseUrl + '/static/imgs/Tabs/index/kf.png'" mode="aspectFill"></image>
					</button>
					<!-- <view class="img-contain" @click="openScan">
						<image class="scan" :src="baseUrl + '/static/imgs/Tabs/index/scan.png'" mode="aspectFill"></image>
					</view> -->
				</view>
			</view>
			<view class="swiper-wrap">
				<view class="loca">
					<view class="iconfont icon-loca" @click="moveToMyPosition"></view>
				</view>
				<view :class="['swiper-card', (swiper.show === 'loading' ? 'loading' : '')]" v-if="swiper.show !== 'close'">
					<view class="flex-head">
						<view class="left">
							<image lazy-load :src="baseUrl + swiper.data.store_image" mode="aspectFill"></image>
							<view class="info">
								<view class="tt">{{ swiper.data.store_name }}</view>
								<view class="bb">
									<view><text class="iconfont icon-rili"></text>{{ swiper.data.label }}</view>
									<view class="add">
										<text class="iconfont icon-zb1"></text>{{ swiper.data.address }}
									</view>
								</view>
							</view>
						</view>
						<view class="right">
							<button class="share" type="default" open-type="share" :data-share="swiper.data">
								<text class="iconfont icon-share"></text>分享
							</button>
							<button class="dh" type="default" @click="openLocation">
								<text class="iconfont icon-zb2"></text>导航
							</button>
						</view>
					</view>
					<view class="flex-end" @click="jumpToStoreDetail">
						<view class="left">{{ swiper.data.room_price }}元/时</view>
						<view class="right">查看详情<text class="iconfont icon-right"></text></view>
					</view>
				</view>
			</view>
			<!-- <view class="tab-card">
				<view class="loca">
					<view class="iconfont icon-loca" @click="moveToMyPosition"></view>
				</view>
				<view class="row">
					<view class="col" @click="getMapCenter(1)">
						<image :src="baseIMG + '/xiaoman12yue/Tabs/index/cqkj.png'" mode=""></image>
						<view class="text">茶雀空间</view>
					</view>
					<view class="col bdlr" @click="getMapCenter(2)">
						<image :src="baseIMG + '/xiaoman12yue/Tabs/index/wskj.png'" mode=""></image>
						<view class="text">舞社唱吧</view>
					</view>
					<view class="col" @click="getMapCenter(3)">
						<image :src="baseIMG + '/xiaoman12yue/Tabs/index/mskj.png'" mode=""></image>
						<view class="text">民宿公寓</view>
					</view>
					<view class="col bdtp" @click="pageJump('/pagesC/index/index')">
						<image :src="baseIMG + '/xiaoman12yue/Tabs/index/yxsc.png'" mode=""></image>
						<view class="text">文化商城</view>
					</view>
					<view class="col bdtp bdlr" @click="listChange">
						<image :src="baseIMG + '/xiaoman12yue/Tabs/index/order.png'" mode=""></image>
						<view class="text">预订管理</view>
					</view>
					<view class="col bdtp" @click="toUser">
						<image :src="baseIMG + '/xiaoman12yue/Tabs/index/user.png'" mode=""></image>
						<view class="text">个人中心</view>
					</view>
				</view>
			</view> -->
		</view>
		<map id="map" @tap="mapClick" @markertap="markerTap" :markers="mapContext.markers" :latitude="currentLocationInfo.latitude" show-location :longitude="currentLocationInfo.longitude">
			<cover-view class="center-img" @click="getMapCenter('')" v-if="!list">
				<cover-view class="text">附近门店</cover-view>
				<cover-image :src="baseUrl + '/static/imgs/Tabs/index/map_center.png'"></cover-image>
			</cover-view>
		</map>
	</view>
</template>

<script>
	import { mapState } from 'vuex'
	
	const app = getApp()
	
	export default {
		computed: {
			...mapState({
				currentLocationInfo: state => state.LOCATION.currentLocationInfo
			})
		},
		props: {
			list: {
				type: Boolean,
				default: _ => {
					return false
				}
			},
			storeID: {
				type: String,
				default: _ => {
					return ''
				}
			},
			deviceID: {
				type: String,
				default: _ => {
					return ''
				}
			},
			keyID: {
				type: String,
				default: _ => {
					return ''
				}
			},
			citys: {
				type: Object,
				default: _ => {
					return {}
				}
			}
		},
		watch: {
			citys(nv, ov) {
				this.cityRangeChange(nv)
			}
		},
		data() {
			return {
				map: uni.createMapContext('map', this),
				menuButtonBoundingClientRect: app.globalData.menuButtonBoundingClientRect,
				// 城市选择
				picker: {
					value: 0,
					range: [{
						city_name: '北京市'
					}]
				},
				swiper: {
					show: 'close',
					data: {}
				},
				nearbyList: [],
				// 地图对象上下文
				mapContext: {
					markers: []
				},
				// 广告列表
				advList: []
			}
		},
		methods: {
			toUser() {
				this.$emit('tabChange', 1)
				this.$emit('listChange', false)
			},
			// 钥匙管理
			listChange() {
				this.$emit('listChange', !this.list)
			},
			// 设置广告位
			setAdvData(data) {
				this.$store.commit('ADV/setAdvData', data)
				this.pageJump('/pagesB/advDetail/advDetail')
			},
			// 扫码
			openScan() {
				uni.scanCode({
					success: () => {
						
					}
				})
			},
			// 页面跳转
			pageJump(url) {
				uni.navigateTo({
					url
				})
			},
			// 判断是否是分享过来的
			async isFromShare() {
				// 如果是钥匙
				if (this.keyID) {
					uni.showLoading({
						title: '领取钥匙中...',
						mask: true
					})
					await this.$apiRequest({
						url: '/user.Room/getKey',
						data: {
							key_id: this.keyID
						}
					}).then(() => {
						uni.hideLoading()
						uni.showToast({
							title: '领取成功',
							icon: 'success'
						})
					})
					return
				}
				// 如果是门店
				if (this.storeID) {
					uni.showLoading({
						title: '正在跳转...',
						mask: true
					})
					await this.$apiRequest({
						url: '/store.Room/list',
						data: {
							store_id: this.storeID,
							...this.currentLocationInfo
						}
					}).then(async data => {
						uni.hideLoading()
						await this.$store.dispatch('STORE/initStoreData', data.store_info)
						uni.navigateTo({
							url: '/pagesB/storeDetail/storeDetail'
						})
					})
				}
				// 如果是扫描设备二维码
				if (this.deviceID) {
					uni.showLoading({
						title: '正在查询...',
						mask: true
					})
					
					await this.$apiRequest({
						url: '/user.Room/qrOpenLock',
						data: {
							device_id: this.deviceID
						}
					}).then(data => {
						uni.hideLoading()
						if (data.room_id) {
							uni.navigateTo({
								url: `/pagesB/roomDetail/roomDetail?roomID=${ data.room_id }`
							})
						} else {
							uni.showToast({
								title: '开门成功',
								icon: 'success'
							})
						}
					})
				}
			},
			// 打开地图位置
			openLocation() {
				uni.openLocation({
					longitude: this.swiper.data.longitude,
					latitude: this.swiper.data.latitude
				})
			},
			// 跳转门店详情
			async jumpToStoreDetail() {
				await this.$store.dispatch('STORE/initStoreData', this.swiper.data)
				uni.navigateTo({
					url: '/pagesB/storeDetail/storeDetail'
				})
			},
			// 获取地图中心点
			getMapCenter(store_type) {
				this.map.getCenterLocation({
					success: (res) => {
						if(res.latitude===0&&res.latitude===0){
							uni.navigateTo({
								url: `/pagesB/List/nearbyStore/nearbyStore?longitude=119.95000499999992&latitude=31.77351499999999&store_type=1`
							})
						}else{
							uni.navigateTo({
								url: `/pagesB/List/nearbyStore/nearbyStore?longitude=${ res.longitude }&latitude=${ res.latitude }&store_type=${ store_type }`
							})
						}
					}
				})
			},
			// 地图点击
			mapClick() {
				this.hideFooterSwiper()
			},
			// 移动到我当前的位置
			moveToMyPosition() {
				this.moveToLocation(this.currentLocationInfo)
				this.hideFooterSwiper()
			},
			// 隐藏底部轮播
			hideFooterSwiper() {
				if (this.swiper.show === 'close') {
					return
				}
				this.swiper.show = 'loading'
				setTimeout(() => {
					this.swiper.show = 'close'
				}, 1000)
			},
			// 地图气泡点击
			markerTap(e) {
				const idx = this.mapContext.markers.findIndex(item => item.id === e.detail.markerId)
				this.moveToLocation(this.mapContext.markers[idx])
				const idx2 = this.nearbyList.findIndex(item => item.store_id === e.detail.markerId)
				this.swiper.data = this.nearbyList[idx2]
				this.swiper.show = 'open'
			},
			// 城市切换
			cityRangeChange(e) {
				this.picker.range = [e]
				this.picker.value = 0
				this.moveToLocation({
					longitude: +e.longitude,
					latitude: +e.latitude
				})
			},
			// 获取城市列表
			async getPickerCityList() {
				await this.$apiRequest({
					url: '/store.City/list'
				}).then(data => {
					this.picker.range = data.list
				})
			},
			// 地图移动
			moveToLocation(lc) {
				this.map.moveToLocation({
					longitude: lc.longitude,
					latitude: lc.latitude,
					fail: e => {
						setTimeout(() => {
							this.moveToLocation(lc)
						}, 500)
					}
				})
			},
			// 获取当前位置
			async getCurrentPosition() {
				await this.$store.dispatch('LOCATION/getCurrentLocationInfo', this.map).catch(() => {
					this.getCurrentPosition()
				})
				const idx = this.picker.range.findIndex(item => item.city_name === this.currentLocationInfo.city)
				this.picker.value = (idx !== -1 ? idx : 0)
			},
			// 获取地图上的门店列表
			async getStoreList(local) {
				await this.$apiRequest({
					url: '/store.Store/nearby',
					data: local
				}).then(data => {
					data.list.forEach(item => {
						this.nearbyList.push(item)
						this.mapContext.markers.push({
							id: item.store_id,
							latitude: item.latitude,
							longitude: item.longitude,
							iconPath: this.baseIMG + `/${ item.store_type === 1 ? 'map_cqkj' : item.store_type === 2 ? 'map_wskj' : item.store_type === 3 ? 'map_mskj' : '' }.png`,
							width: 52,
							height: 62
						})
					})
				})
			},
			// 获取广告位
			async getAdvList() {
				await this.$apiRequest({
					url: '/ad.Ad/list'
				}).then(data => {
					this.advList = data.list
				})
			},
			// 初始化
			async init() {
				
				await this.getPickerCityList()
				await this.getCurrentPosition()
				await this.getStoreList(this.currentLocationInfo)
				this.isFromShare()
				this.getAdvList()
			}
		},
		mounted() {
			this.init()
		}
	}
</script>

<style lang="scss">
	@import 'index.scss';
</style>
