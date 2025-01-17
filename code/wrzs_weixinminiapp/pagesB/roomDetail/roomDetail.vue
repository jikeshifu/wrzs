<template>
	<view class="content">
		<Topbar :title="roomData.room_name"></Topbar>
		<view class="flex-contain">
			<wLoading v-if="pageLoading"></wLoading>
			<template v-else>
				<view class="swiper">
					<view class="dot">{{ ( current + 1 ) }}/{{ roomData.room_images.length }}</view>
					<swiper @change="swiperChange" :current="current" :circular="true" :autoplay="true" :interval="3000" :duration="500">
						<swiper-item v-for="(item, index) in roomData.room_images" :key="index">
							<view class="swiper-item">
								<image :src="baseUrl + item" mode="aspectFill"></image>
							</view>
						</swiper-item>
					</swiper>
				</view>
				<view class="room-desc">
					<view class="row">
						<view class="col">
							<text class="label">面积</text>
							<text class="text">{{ roomData.room_size }}m²</text>
						</view>
						<view class="col">
							<text class="label">宾位</text>
							<text class="text">{{ (roomData.room_people - 3) > 0 ? (roomData.room_people - 3) : 0 }}~{{ roomData.room_people }}人</text>
						</view>
						<view class="col">
							<text class="label">通风</text>
							<text class="text">有窗</text>
						</view>
					</view>
					<!-- <view class="row">
						<view class="col">
							<text class="label">空调</text>
							<text class="text">有</text>
						</view>
						<view class="col">
							<text class="label">茶叶</text>
							<text class="text">有售</text>
						</view>
					</view> -->
					<view class="row">
						<view class="col">
							<text class="label">规则</text>
							<text class="text2">{{ roomData.starting_time }}小时起订</text>
						</view>
						<view class="col">
							<text class="label">价格</text>
							<text class="text3">￥{{ roomData.room_price }}</text>时
						</view>
					</view>
				</view>
				<view class="choose-panel" @click="chooseHours" v-if="!hoursDialog.totalMoeny">
					<view class="left">
						<text class="iconfont icon-date"></text>请选择预定时间
					</view>
					<view class="iconfont icon-right"></view>
				</view>
				<view class="choosed-hours-panel" v-if="hoursDialog.totalMoeny" @click="chooseHours">
					<view class="left">
						<view class="item">
							<view class="h4">到店时间</view>
							<view class="p">{{ splitDate(hoursDialog.choosedTime.startTime).hm }}<text>{{ splitDate(hoursDialog.choosedTime.startTime).date }}</text></view>
						</view>
						<view class="item">
							<view class="h4">离店时间</view>
							<view class="p">{{ splitDate(hoursDialog.choosedTime.endTime).hm }}<text>{{ splitDate(hoursDialog.choosedTime.endTime).date }}</text></view>
						</view>
					</view>
					<view class="right">
						共<text class="warn">{{ hoursDialog.totalHours.fmt }}</text>
						<text class="iconfont icon-right"></text>
					</view>
				</view>
				<view class="room-desc">
					<view class="title">房间说明</view>
					<uParse :content="roomData.room_about" :noData="' '"></uParse>
				</view>
				<view class="empty-fixed"></view>
			</template>
		</view>
		<view class="fixed-bottom-bar">
			<view class="left">
				<view class="h4">
					<text class="small">￥</text>{{ hoursDialog.totalMoeny }}
				</view>
				<view class="p">共{{ hoursDialog.totalHours ? hoursDialog.totalHours.fmt : '0小时' }}</view>
			</view>
			<view class="right">
				<template v-if="userInfo.mobile">
					<button type="primary" @click="chooseHours" v-if="!hoursDialog.totalHours">立即预定</button>
					<button type="primary" @click="submit" v-if="hoursDialog.totalHours">立即预定</button>
				</template>
				<template v-else>
					<button type="primary" open-type="getPhoneNumber" @getphonenumber="getPhoneNumber">绑定手机</button>
				</template>
			</view>
		</view>
		<Hours v-if="hoursDialog.show" @closeHoursDialog="closeHoursDialog" :hoursDialog="hoursDialog" @hoursConfirm="hoursConfirm"></Hours>
	</view>
</template>

<script>
	import uParse from '@/components/u-parse/u-parse.vue'
	import { mapState } from 'vuex'
	import Hours from '@/components/hours/hours.vue'
	import { splitDate } from '@/components/hours/index.js'
	
	export default {
		computed: {
			...mapState({
				userInfo: state => state.USER.userInfo
			})
		},
		components: {
			uParse,
			Hours
		},
		data() {
			return {
				pageLoading: true,
				current: 0,
				// 分割日期格式
				splitDate,
				hoursDialog: {
					show: false,
					totalHours: 0,
					totalMoeny: 0,
					// 选择时间对象
					choosedTime: {
						startTime: '',
						endTime: ''
					}
				},
				code: '',
				// 房间信息
				roomData: {}
			}
		},
		methods: {
			// 判断手机号是否绑定
			isBindPhone() {
				uni.login({
					success: lg => {
						this.code = lg.code
					}
				})
			},
			// 绑定手机号
			async getPhoneNumber(e) {
				if (e.detail.errMsg !== 'getPhoneNumber:ok') {
					this.$showMsg('为了更方便提供服务，请绑定您的手机')
					return
				} else {
					uni.showLoading({
						title: '正在绑定...',
						mask: true
					})
					const data = await this.$apiRequest({
						url: '/watch.User/mobileCode',
						data: {
							code: this.code,
							iv: e.detail.iv,
							encryptedData: e.detail.encryptedData
						}
					})
					const u = uni.getStorageSync('userInfo')
					u.mobile = data.mobile
					this.$store.commit('USER/resetUserInfo', u)
					uni.hideLoading()
					uni.showToast({
						title: '绑定成功',
						icon: 'success'
					})
				}
			},
			// 按时确定选择
			hoursConfirm(data) {
				this.hoursDialog.totalHours = data.totalHours
				this.hoursDialog.totalMoeny = data.totalMoeny
				this.hoursDialog.choosedTime.startTime = data.startTime
				this.hoursDialog.choosedTime.endTime = data.endTime
			},
			swiperChange(e) {
				this.current = e.detail.current
			},
			// 关闭按时计费的弹窗
			closeHoursDialog(data) {
				this.hoursDialog.show = false
			},
			// 选择按时计费的弹窗
			chooseHours() {
				uni.showLoading({
					title: '请稍后...',
					mask: true
				})
				this.hoursDialog.show = true
			},
			// 提交预定
			submit() {
				uni.navigateTo({
					url: `/pagesB/payOrder/payOrder?start_time=${ this.hoursDialog.choosedTime.startTime }&end_time=${ this.hoursDialog.choosedTime.endTime }`
				})
			},
			// 获取房间信息
			async getRoomData(room_id) {
				await this.$apiRequest({
					url: '/store.Room/info',
					data: {
						room_id
					}
				}).then(async data => {
					this.roomData = data.room_info
					await this.$store.dispatch('ROOM/initRoomData', data.room_info)
				}).catch(() => {
					
				})
				this.pageLoading = false
			}
		},
		onLoad(options) {
			this.getRoomData(options.roomID)
			if (!this.userInfo.mobile) {
				this.isBindPhone()
			}
		}
	}
</script>

<style lang="scss">
	@import 'index.scss';
	@import '@/components/u-parse/u-parse.css';
</style>
