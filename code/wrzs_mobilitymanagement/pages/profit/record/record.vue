<template>
	<view class="content">
		<view class="picker-group">
			<picker mode="date" @change="startDateChange" :end="$dateFormatter(null, 'yyyy-mm-dd')">
				<view class="picker-item">
					<label for="">开始日期</label>
					<view class="text">{{ startDate ? startDate : '请选择开始日期' }}</view>
				</view>
			</picker>
			<picker mode="date" @change="endDateChange" :end="$dateFormatter(null, 'yyyy-mm-dd')">
				<view class="picker-item">
					<label for="">结束日期</label>
					<view class="text">{{ endDate ? endDate : '请选择结束日期' }}</view>
				</view>
			</picker>
		</view>
		<button class="btn" type="primary" @click="searchSubmit">查询</button>
		<view class="profit">总收益：{{ profit }}</view>
		<scroll-view scroll-y>
			<template v-if="list.length">
				<view class="item" v-for="(item, index) in list" :key="index">
					<view class="left">
						<view class="h4">时间</view>
						<view class="p">{{ $dateFormatter(+(item.created_at + '000'), 'yyyy-mm-dd hh:mm:ss') }}</view>
					</view>
					<view class="right">{{ item.order_profit }}元</view>
				</view>
				<view class="append-more-text">{{ appendMoreText }}</view>
			</template>
			<NoData v-else></NoData>
		</scroll-view>
	</view>
</template>

<script>
	import NoData from '@/components/NoData.vue'
	
	export default {
		components: {
			NoData
		},
		data() {
			return {
				startDate: '',
				endDate: '',
				profit: '0.00',
				list: [],
				page: 1,
				limit: 20,
				appendMoreText: '上滑加载更多'
			}
		},
		methods: {
			// 获取收益列表
			async getProfitList() {
				uni.showLoading({
					title: '加载种...',
					mask: true
				})
				await this.$apiRequest({
					url: '/join.Profit/list',
					data: {
						// page: this.page,
						// limit: this.limit,
						start_time: this.startDate ? new Date(this.startDate.replace(/-/g, '/')).getTime().toString().slice(0, 10) : '',
						end_time: this.endDate ? new Date(this.endDate.replace(/-/g, '/')).getTime().toString().slice(0, 10) : ''
					}
				}).then(data => {
					uni.hideLoading()
					this.profit = data.profit
					if (data.list.length) {
						if (this.page !== 1) {
							data.list.forEach(item => {
								this.list.push(item)
							})
						} else {
							this.list = data.list
						}
						this.appendMoreText = '上滑加载更多'
						if (this.list.length < this.limit) {
							this.appendMoreText = '已全部加载'
						}
					} else {
						if (this.page === 1) {
							this.list = []
						}
						this.appendMoreText = '已全部加载'
					}
				})
			},
			// 查询
			searchSubmit() {
				this.page = 1
				this.getProfitList()
			},
			startDateChange(e) {
				this.startDate = e.detail.value
			},
			endDateChange(e) {
				this.endDate = e.detail.value
			},
			// 上滑加载更多
			appendMore() {
				if (this.appendMoreText !== '上滑加载更多') {
					return
				}
				this.page += 1
				this.appendMoreText = '正在加载更多'
				this.getProfitList()
			}
		},
		mounted() {
			this.getProfitList()
		}
	}
</script>

<style lang="scss">
	@import 'index.scss';
</style>
