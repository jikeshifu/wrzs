<template>
	<view class="content">
		<Topbar title="钱包明细"></Topbar>
		<view class="tabs-nav">
			<view :class="['item', swiper.current === 0 ? 'active' : '']" @click="tabsChange(0)">全部</view>
			<view :class="['item', swiper.current === 1 ? 'active' : '']" @click="tabsChange(1)">充值</view>
			<view :class="['item', swiper.current === 2 ? 'active' : '']" @click="tabsChange(2)">支出</view>
		</view>
		<view class="swiper">
			<swiper @change="swiperChange" :current="swiper.current">
				<swiper-item v-for="(item, index) in swiper.list" :key="index">
					<wLoading v-if="item.pageLoading"></wLoading>
					<template v-else>
						<picker mode="date" fields="month" :end="item.range.end" @change="pickerChange($event, item)">
							<view class="date-title">{{ $dateFormatter(item.range.now, 'yyyy年mm月') }}<text class="iconfont icon-down"></text></view>
						</picker>
						<template v-if="item.list.length">
							<scroll-view class="scroll-view" scroll-y @scrolltolower="appendMore">
								<view class="item" v-for="item2 in item.list" :key="item2.record_id">
									<view class="left">
										<view class="h4">{{ item2.title }}</view>
										<view class="p">{{ $dateFormatter(+(item2.created_at + '000'), 'yyyy-mm-dd hh:mm:ss') }}</view>
									</view>
									<view class="right">
										<view class="add" v-if="item2.type === 1">+{{ item2.price }}</view>
										<view class="reduce" v-if="item2.type === 2">-{{ item2.price }}</view>
									</view>
								</view>
								<UniLoadMore :status="item.appendMoreStatus"></UniLoadMore>
							</scroll-view>
						</template>
						<template v-else>
							<NoData title="还没有记录"></NoData>
						</template>
					</template>
				</swiper-item>
			</swiper>
		</view>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				swiper: {
					// 1 全部，2 充值，3 支出
					current: 0,
					list: [{
						pageLoading: true,
						appendMoreStatus: 'more',
						range: {
							yc: this.$getMonthST_ED(new Date().getTime()).yc,
							now: this.$dateFormatter(new Date().getTime(), 'yyyy-mm-dd'),
							yw: this.$getMonthST_ED(new Date().getTime()).yw,
							end: this.$dateFormatter(new Date().getTime(), 'yyyy-mm-dd')
						},
						list: [],
						page: 1,
						limit: 10
					}, {
						pageLoading: true,
						appendMoreStatus: 'more',
						range: {
							yc: this.$getMonthST_ED(new Date().getTime()).yc,
							now: this.$dateFormatter(new Date().getTime(), 'yyyy-mm-dd'),
							yw: this.$getMonthST_ED(new Date().getTime()).yw,
							end: this.$dateFormatter(new Date().getTime(), 'yyyy-mm-dd')
						},
						list: [],
						page: 1,
						limit: 10
					}, {
						pageLoading: true,
						appendMoreStatus: 'more',
						range: {
							yc: this.$getMonthST_ED(new Date().getTime()).yc,
							now: this.$dateFormatter(new Date().getTime(), 'yyyy-mm-dd'),
							yw: this.$getMonthST_ED(new Date().getTime()).yw,
							end: this.$dateFormatter(new Date().getTime(), 'yyyy-mm-dd')
						},
						list: [],
						page: 1,
						limit: 10
					}]
				}
			}
		},
		methods: {
			// 月份切换
			pickerChange(e, item) {
				const t = this.$getMonthST_ED(new Date(e.detail.value).getTime())
				item.range.now = e.detail.value
				item.range.yc = t.yc
				item.range.yw = t.yw
				item.list = []
				item.page = 1
				item.pageLoading = true
				this.getWalletList()
			},
			tabsChange(idx) {
				this.swiper.current = idx
			},
			swiperChange(e) {
				this.swiper.current = e.detail.current
				const curt = this.swiper.list[this.swiper.current]
				if (!curt.list.length) {
					curt.pageLoading = true
					this.getWalletList()
				}
			},
			// 滚动底部加载更多
			appendMore() {
				const curt = this.swiper.list[this.swiper.current]
				if (curt.appendMoreStatus !== 'more') {
					return
				}
				curt.page += 1
				curt.appendMoreStatus = 'loading'
				this.getWalletList()
			},
			// 获取钱包明细列表
			async getWalletList() {
				const curt = this.swiper.list[this.swiper.current]
				await this.$apiRequest({
					url: '/user.Consume/record',
					data: {
						page: curt.page,
						limit: curt.limit,
						type: this.swiper.current,
						yc: new Date(curt.range.yc).getTime().toString().slice(0, 10),
						yw: new Date(curt.range.yw).getTime().toString().slice(0, 10)
					}
				}).then(data => {
					if (data.list.length) {
						data.list.forEach(item => {
							curt.list.push(item)
						})
						curt.appendMoreStatus = 'more'
						if (curt.list.length < curt.limit) {
							curt.appendMoreStatus = 'noMore'
						}
					} else {
						curt.appendMoreStatus = 'noMore'
					}
				}).catch(() => {})
				curt.pageLoading = false
			}
		},
		onLoad() {
			this.getWalletList()
		}
	}
</script>

<style lang="scss">
	@import 'index.scss'
</style>
