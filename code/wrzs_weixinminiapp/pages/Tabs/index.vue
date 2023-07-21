<template>
	<view class="content">
		<Index v-show="active === 0" @tabChange="tabChange" @listChange="listChange" @changeTab="changeTab" :list="list" :storeID="storeID" :keyID="keyID" :deviceID="deviceID" :citys="citys"></Index>
		<User v-if="active === 1"></User>
		<List v-if="list" @listChange="listChange"></List>
		<Tabs :active="active" :list="list" @tabChange="tabChange" @listChange="listChange"></Tabs>
	</view>
</template>

<script>
	import Index from '@/pages/Tabs/index/index.vue'
	import User from '@/pages/Tabs/user/user.vue'
	import Tabs from '@/components/Tabs.vue'
	import List from './list/list.vue'
	import { mapState } from 'vuex'
	
	export default {
		computed: {
			...mapState({
				userInfo: state => state.USER.userInfo
			})
		},
		components: {
			Index,
			User,
			Tabs,
			List
		},
		async onShareAppMessage(e) {
			if (e.from === 'button' && e.target.dataset.keyID) {
				await this.$apiRequest({
					url: '/user.Room/shareKey',
					data: {
						order_id: e.target.dataset.share.order_id
					}
				})
				uni.showToast({
					title: '分享成功',
					icon: 'success'
				})
			}
			// 按钮分享
			if (e.target) {
				return {
					title: `您的好友${ this.userInfo.nick_name ? this.userInfo.nick_name : '' }分享了${ e.target.dataset.share.store_name }给您`,
					path: `/pages/Tabs/index?storeID=${ e.target.dataset.share.store_id || 0 }&keyID=${ e.target.dataset.keyID || 0 }`,
					imageUrl: this.baseUrl + e.target.dataset.share.store_image
				}
			} else {
				return {
					title: `您的好友${ this.userInfo.nick_name ? this.userInfo.nick_name : '' }分享了小程序给您`,
					path: '/pages/Tabs/index'
				}
			}
		},
		onShareTimeline() {
			
		},
		data() {
			return {
				// 分享后，朋友拿到的门店ID
				storeID: '',
				// 分享后，朋友拿到的门店房间钥匙
				keyID: '',
				// 扫描设备ID
				deviceID: '',
				active: 0,
				list: false,
				citys: {}
			}
		},
		methods: {
			listChange(data) {
				this.list = data
			},
			tabChange(e) {
				this.active = e
			}
		},
		onLoad(options) {
			if (options.q) {
				let q = decodeURIComponent(options.q)
				this.deviceID = this.$getQueryString(q, 'device_id')
			}
			if (options.keyID) {
				this.keyID = +options.keyID
			}
			if (options.storeID) {
				this.storeID = +options.storeID
			}
		}
	}
</script>

<style lang="scss">
	.content {
		display: flex;
		flex-direction: column;
		justify-content: space-between;
		Index,
		User {
			flex: 1;
			height: 1%;
		}
	}
</style>
