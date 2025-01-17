<template>
	<view class="content-body">
		<Topbar title="积分记录"></Topbar>
		<WLoading v-if="pageLoading"></WLoading>
		<template v-else>
			<scroll-view scroll-y @scrolltolower="appendMore">
				<template v-if="list.length">
					<view class="item" v-for="item in list" :key="item.record_id">
						<view class="left">
							<view class="h4">{{ item.record_name }}</view>
							<view class="p">{{ $dateFormatter(+(item.created_at + '000'), 'yyyy-mm-dd hh:mm:ss') }}</view>
						</view>
						<view class="right">
							<view class="add" v-if="item.type === 1">+{{ item.number }}</view>
							<view class="reduce" v-if="item.type === 0">-{{ item.number }}</view>
						</view>
					</view>
					<UniLoadMore :status="appendMoreStatus"></UniLoadMore>
				</template>
				<template v-else>
					<NoData title="暂无记录"></NoData>
				</template>
			</scroll-view>
		</template>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				pageLoading: true,
				page: 1,
				limit: 20,
				list: [],
				appendMoreStatus: 'more'
			}
		},
		methods: {
			// 加载更多
			appendMore() {
				if (this.appendMoreStatus !== 'more') {
					return
				}
				this.appendMoreStatus = 'loading'
				this.page += 1
				this.getList()
			},
			// 获取积分列表
			async getList() {
				await this.$apiRequest({
					url: '/member.MemberIntegralRecord/list',
					data: {
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
				}).catch(() => {})
				this.pageLoading = false
			}
		},
		mounted() {
			this.getList()
		}
	}
</script>

<style lang="scss">
	@import './index.scss';
</style>
