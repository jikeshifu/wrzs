<template>
	<view class="hour-choose">
		<view class="mask" @click="cancel"></view>
		<view class="contt">
			<view class="head">
				<view class="left" @click="cancel">取消</view>
				<view class="center">选择时间</view>
				<view class="right" @click="clearChoosedTime">清空</view>
			</view>
			<view class="fp">{{ roomData.starting_time }}小时起预定</view>
			<view class="swiper-wrap">
				<view class="date-title">{{ getDate[swiper.current].year + '年' + getDate[swiper.current].month + '月' }}</view>
				<view class="tabs-nav">
					<block v-for="(item, index) in getDate" :key="index">
						<view :class="['item', swiper.current === index ? 'active' : '']" @click="swiper.current = index">
							<view class="l">{{ item.date }}<text>日</text></view>
							<view class="r">
								<view class="p">{{ index === 0 ? '今天' : index === 1 ? '明天' : index === 2 ? '后天' : '' }}</view>
								<view class="week">{{ item.week }}</view>
							</view>
						</view>
					</block>
				</view>
				<swiper :current="swiper.current" @change="swiperChange">
					<swiper-item v-for="(item, index) in getDate" :key="index">
						<view class="swiper-item">
							<block v-for="(item2, index2) in item.times" :key="index2">
								<view class="col" v-if="!item2.hide">
									<view :class="['item', (item2.checked ? 'checked' : ''), (item2.active ? 'active' : '')]" @click="chooseTime(item, item2)">
										<text class="yd" v-if="item2.checked">已定</text>
										<text class="dd" v-if="item2.dd">到店</text>
										<text class="ld" v-if="item2.ld">离店</text>
										{{ item2.value }}
									</view>
								</view>
							</block>
						</view>
					</swiper-item>
				</swiper>
			</view>
			<view class="btn">
				<button type="primary" @click="chooseFinish">确定{{ totalHours ? totalHours.fmt : '' }}</button>
			</view>
		</view>
	</view>
</template>

<script>
	import { getDate } from './index.js'
	import { mapState } from 'vuex'
	
	export default {
		computed: {
			...mapState({
				roomData: state => state.ROOM.roomData
			}),
			// 总共时间
			totalHours() {
				if (this.choosedTime.startTime && this.choosedTime.endTime) {
					return this.$subtTime(this.choosedTime.startTime, this.choosedTime.endTime)
				} else {
					return ''
				}
			},
			// 总共金额
			totalMoeny() {
				if (this.totalHours) {
					let daysT = 0
					if (this.totalHours.days) {
						daysT = +this.$calc.multiply(24, this.roomData.room_price, 2)
					}
					let hoursT = 0
					if (this.totalHours.hours) {
						hoursT = +this.$calc.multiply(this.totalHours.hours, this.roomData.room_price, 2)
					}
					let minutesT = 0
					if (this.totalHours.minutes) {
						minutesT = +this.$calc.divide(this.roomData.room_price, 2, 2)
					}
					return daysT + hoursT + minutesT
				} else {
					return 0
				}
			}
		},
		props: {
			hoursDialog: {
				type: Object,
				default: _ => {
					return {}
				}
			}
		},
		data() {
			return {
				getDate: getDate(),
				swiper: {
					current: 0
				},
				// 已预定的时间范围
				selectedTime: [],
				choosedTime: this.hoursDialog.choosedTime
			}
		},
		methods: {
			// 取消选择
			cancel() {
				this.$emit('closeHoursDialog', false)
			},
			// 确定选择
			chooseFinish() {
				if (!this.choosedTime.startTime) {
					this.$showMsg('请选择到店时间')
					return
				}
				if (!this.choosedTime.endTime) {
					this.$showMsg('请选择离店时间')
					return
				}
				if (this.totalHours.hours < this.roomData.starting_time) {
					this.$showMsg(`亲爱的，${ this.roomData.starting_time }小时起预定哦`)
					return
				}
				this.$emit('hoursConfirm', {
					startTime: this.choosedTime.startTime,
					endTime: this.choosedTime.endTime,
					totalHours: this.totalHours,
					totalMoeny:this.totalMoeny.toFixed(2) 
				})
				this.$emit('closeHoursDialog', false)
			},
			// 清除已选时间、及样式
			clearChoosedTime() {
				this.choosedTime.startTime = ''
				this.choosedTime.endTime = ''
				this.clearActiveStyle()
			},
			// 清除已选择的样式
			clearActiveStyle() {
				this.getDate.forEach(item => {
					item.times.forEach(item2 => {
						item2.active = false
						item2.dd = false
						item2.ld = false
					})
				})
			},
			// 绘制已选择样式
			setActiveStyle() {
				this.getDate.forEach(item => {
					item.times.forEach(item2 => {
						const fmt = new Date(item.nyr + ' ' + item2.value + ':00').getTime()
						const startT = new Date(this.choosedTime.startTime).getTime()
						const endT = new Date(this.choosedTime.endTime).getTime()
						// 如果在已选择的时间范围内，绘制 active 样式
						if (fmt >= startT && fmt <= endT) {
							item2.active = true
							// 添加到店字样
							if (fmt === startT) {
								item2.dd = true
							}
							// 添加离店字样
							if (fmt === endT) {
								item2.ld = true
							}
						}
					})
				})
			},
			// 这里先判断下首选和结束时间中间是否有已预定的时间，如果有，重置首选时间和结束时间
			judgeCenterIsChecked(item, item2) {
				// 默认没有
				let have = false
				this.getDate.forEach(item => {
					item.times.forEach(item2 => {
						let fmt = new Date(item.nyr + ' ' + item2.value + ':00').getTime()
						if (fmt >= (new Date(this.choosedTime.startTime).getTime()) && fmt <= (new Date(this.choosedTime.endTime))) {
							if (item2.checked) {
								have = true
							}
						}
					})
				})
				if (have) {
					this.choosedTime.endTime = ''
					this.choosedTime.startTime = item.nyr + ' ' + item2.value + ':00'
					item2.active = true
					item2.dd = true
				} else {
					this.setActiveStyle()
				}
			},
			// 选择时间
			chooseTime(item, item2) {
				if (item2.checked) {
					return
				}
				// 如果首选时间已选
				if (this.choosedTime.startTime) {
					this.clearActiveStyle()
					// 并且如果再次选择的时间小于或者等于已经首选的时间，重置结束时间，初始化首选时间
					if (new Date(item.nyr + ' ' + item2.value + ':00').getTime() <= new Date(this.choosedTime.startTime).getTime()) {
						this.choosedTime.endTime = ''
						this.choosedTime.startTime = item.nyr + ' ' + item2.value + ':00'
						item2.active = true
						item2.dd = true
					} else {
						this.choosedTime.endTime = item.nyr + ' ' + item2.value + ':00'
						this.judgeCenterIsChecked(item, item2)
					}
				}
				// 如果首选时间没选
				if (!this.choosedTime.startTime) {
					item2.active = true
					item2.dd = true
					this.choosedTime.startTime = item.nyr + ' ' + item2.value + ':00'
				}
				console.log('开始时间:', this.choosedTime.startTime, '结束时间:', this.choosedTime.endTime)
			},
			// 筛选已预定的时间
			filterCheckedTimes() {
				if (this.choosedTime.startTime && this.choosedTime.endTime) {
					this.setActiveStyle()
				}
				if (!this.selectedTime.length) {
					uni.hideLoading()
					return
				}
				// 今天、明天、后天
				this.getDate.forEach(item => {
					// 时间
					item.times.forEach(item2 => {
						let fmt = new Date(item.nyr + ' ' + item2.value + ':00').getTime()
						this.selectedTime.forEach(item3 => {
							// 如果时间在预定的时间范围内
							if (fmt >= (item3.start_time + '000') && fmt <= (item3.end_time + '000')) {
								item2.checked = true
							}
						})
					})
				})
				uni.hideLoading()
			},
			swiperChange(e) {
				this.swiper.current = e.detail.current
			},
			// 获取房间已预定的时间列表
			async getSelectedTime() {
				await this.$apiRequest({
					url: '/room.Room/timeSlot',
					data: {
						room_id: this.roomData.room_id
					}
				}).then(data => {
					this.selectedTime = data.list
					this.filterCheckedTimes()
				})
			}
		},
		mounted() {
			this.getSelectedTime()
		}
	}
</script>

<style lang="scss" scoped>
	@keyframes fadeIn {
		0% {
			opacity: 0;
		}
		100% {
			opacity: 1;
		}
	}
	@keyframes slideUp {
		0% {
			opacity: 0;
			transform: translateY(500upx);
		}
		100% {
			opacity: 1;
			transform: translateY(0);
		}
	}
	.hour-choose {
		position: fixed;
		z-index: 10;
		top: 0;
		left: 0;
		width: 100%;
		height: 100%;
		.mask {
			position: absolute;
			z-index: 1;
			top: 0;
			left: 0;
			width: 100%;
			height: 100%;
			background-color: rgba($color: #000000, $alpha: .4);
			animation: fadeIn .25s both;
		}
		.contt {
			position: absolute;
			z-index: 2;
			bottom: 0;
			left: 0;
			width: 100%;
			background-color: #fff;
			padding: 30upx;
			border-top-left-radius: 40upx;
			border-top-right-radius: 40upx;
			animation: slideUp .25s both;
			.head {
				display: flex;
				align-items: center;
				justify-content: space-between;
				.left {
					font-size: 30upx;
					color: #B7B7B7;
				}
				.center {
					font-size: 37upx;
				}
				.right {
					font-size: 30upx;
					color: #4D5AB4;
				}
			}
			.fp {
				text-align: center;
				font-size: 18upx;
				color: #FF9813;
				margin-top: 10upx;
			}
			.swiper-wrap {
				background-color: #fbfbfb;
				margin-top: 30upx;
				margin-left: -30upx;
				margin-right: -30upx;
				padding-left: 30upx;
				padding-right: 30upx;
				.date-title {
					padding-top: 30upx;
					font-size: 37upx;
					color: #000;
				}
				.tabs-nav {
					display: flex;
					justify-content: space-between;
					margin-top: 35upx;
					.item {
						flex: .3;
						display: flex;
						align-items: center;
						justify-content: space-between;
						background-color: #F0F0F0;
						border-radius: 10upx;
						padding: 10upx 20upx;
						transition: all .25s;
						.l {
							font-size: 48upx;
							text {
								font-size: 24upx;
							}
						}
						.r {
							text-align: right;
							font-size: 18upx;
							.week {
								margin-top: 10upx;
							}
						}
					}
					.item.active {
						color: #fff;
						background-color: #4D5AB4;
					}
				}
				swiper {
					border-top: 1px solid #F0F0F0;
					height: 500upx;
					margin-top: 32upx;
					padding-top: 10upx;
					.swiper-item:before,
					.swiper-item:after {
						content: '';
						display: table;
						clear: both;
					}
					.swiper-item {
						height: 100%;
						overflow-y: auto;
						margin-left: -10upx;
						margin-right: -10upx;
					}
					.col {
						float: left;
						width: 25%;
						text-align: center;
						padding-left: 10upx;
						padding-right: 10upx;
						margin-top: 22upx;
						.item {
							position: relative;
							border: 1px solid #4AA63C;
							color: #4AA63C;
							font-size: 24upx;
							border-radius: 5upx;
							padding-top: 10upx;
							padding-bottom: 10upx;
							.yd {
								position: absolute;
								z-index: 1;
								top: 0;
								right: 0;
								color: #B3B3B3;
								font-size: 16upx;
								padding: 10upx;
								border-bottom-left-radius: 5upx;
								background-color: #F0F0F0;
							}
							.dd,
							.ld {
								position: absolute;
								z-index: 1;
								top: 10upx;
								right: 10upx;
								color: #fff;
								font-size: 16upx;
							}
						}
						.item.checked {
							background-color: transparent;
							border-color: #E6E6E6;
							color: #B3B3B3;
						}
						.item.active {
							background-color: #429435;
							color: #fff;
							border-color: #429435;
						}
					}
				}
			}
			.btn {
				padding: 30upx;
				button {
					background-color: #429435;
					height: 88upx;
					line-height: 88upx;
					font-size: 36upx;
					border-radius: 20upx;
				}
			}
		}
	}
</style>
