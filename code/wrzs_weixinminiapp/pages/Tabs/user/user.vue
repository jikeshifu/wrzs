<template>
	<view class="user">
		<view class="fixed-topbar">
			<image class="bg" :src="baseUrl + '/static/imgs/topbar_bg.png'" mode="widthFix"></image>
			<view class="fixed-item">
				<view :style="{ height: menuButtonBoundingClientRect.top + 'px' }"></view>
				<view :style="{ height: menuButtonBoundingClientRect.height + 'px' }"></view>
				<view :style="{ height: (menuButtonBoundingClientRect.top / 2) + 'px' }"></view>
			</view>
			<view class="user-avatar" @click="getAvatar">
				<view class="left">
					<view class="h4">{{ isLogin ? userInfo.nick_name : '游客' }}</view>
					<view class="p">{{ isLogin ? (userInfo.mobile ? userInfo.mobile : '') : '注册/登录' }}</view>
				</view>
				<view class="right">
					<image
						:src="isLogin ? userInfo.avatar_url : 'https://img1.baidu.com/it/u=95101203,2700595309&fm=26&fmt=auto&gp=0.jpg'"
						mode="aspectFill"></image>
					<text class="iconfont icon-right"></text>
				</view>
			</view>
		</view>
		<view class="asset-contain">
			<view class="item" @click="pageJump('/pagesB/myAsset/yue/yue')">
				<view class="h4">
					<text class="code">￥</text>{{ myAsset.money }}
				</view>
				<view class="p">钱包余额</view>
			</view>
			<view class="item" @click="pageJump('/pagesB/myAsset/coupon/coupon')">
				<view class="h4">{{ isLogin ? myAsset.coupon_number : 0 }}</view>
				<view class="p">优惠卡券</view>
			</view>
			<view class="item" @click="pageJump('/pagesB/integral/integral')">
				<view class="h4">{{ isLogin ? myAsset.integral : 0 }}</view>
				<view class="p">消费积分</view>
			</view>
		</view>
		<view class="order-status">
			<image class="bg" :src="baseUrl + '/static/imgs/Tabs/user/card.png'" mode="widthFix"></image>
			<view class="card-cnt">
				<view class="head">
					<view class="left">我的订单</view>
					<navigator class="right" url="/pagesB/List/order/order?current=0">所有订单<text
							class="iconfont icon-right"></text></navigator>
				</view>
				<view class="items-flex">
					<view class="item" @click="pageJump('/pagesB/List/order/order?current=0')">
						<text class="badge">{{ dzfCount }}</text>
						<image :src="baseUrl + '/static/imgs/Tabs/user/dzf.png'" mode="aspectFill"></image>
						<view class="p">待支付</view>
					</view>
					<view class="item" @click="pageJump('/pagesB/List/order/order?current=1')">
						<image :src="baseUrl + '/static/imgs/Tabs/user/yyy.png'" mode="aspectFill"></image>
						<view class="p">已预约</view>
					</view>
					<view class="item" @click="pageJump('/pagesB/List/order/order?current=2')">
						<image :src="baseUrl + '/static/imgs/Tabs/user/ywc.png'" mode="aspectFill"></image>
						<view class="p">已完成</view>
					</view>
					<view class="item" @click="pageJump('/pagesB/List/order/order?current=3')">
						<image :src="baseUrl + '/static/imgs/Tabs/user/yqx.png'" mode="aspectFill"></image>
						<view class="p">已取消</view>
					</view>
				</view>
			</view>
		</view>
		<view class="panel-items">
			<view class="item" @click="pageJump('/pagesC/index/index')">
				<view class="left">
					<image :src="baseUrl + '/static/imgs/Tabs/user/yxsc.png'" mode="aspectFill"></image>商城
				</view>
				<view class="right">
					<text class="iconfont icon-right"></text>
				</view>
			</view>
			<view class="item" @click="pageJump('/pagesB/jmhz/jmhz')">
				<view class="left">
					<image :src="baseUrl + '/static/imgs/Tabs/user/jmhz.png'" mode="aspectFill"></image>加盟合作
				</view>
				<view class="right">
					<text class="iconfont icon-right"></text>
				</view>
			</view>
			<button class="item" open-type="contact">
				<view class="left">
					<image :src="baseUrl + '/static/imgs/Tabs/user/zxkf.png'" mode="aspectFill"></image>在线客服
				</view>
				<view class="right">
					<text class="iconfont icon-right"></text>
				</view>
			</button>
			<view class="item" @click="pageJump('/pagesB/feedback/feedback')">
				<view class="left">
					<image :src="baseUrl + '/static/imgs/Tabs/user/yjfk.png'" mode="aspectFill"></image>意见反馈
				</view>
				<view class="right">
					<text class="iconfont icon-right"></text>
				</view>
			</view>
			<view class="item" v-if="myAsset.is_admin===1" @click="pageJump('/pages/mendianguanli/index')">
				<view class="left">
					<image :src="baseUrl + '/static/imgs/Tabs/user/mdgl.png'" mode="aspectFill"></image>门店管理
				</view>
				<view class="right">
					<text class="iconfont icon-right"></text>
				</view>
			</view>
		</view>
	</view>
</template>

<script>
	import {
		mapGetters,
		mapState
	} from 'vuex'

	const app = getApp()

	export default {
		computed: {
			...mapGetters({
				isLogin: 'USER/isLogin'
			}),
			...mapState({
				userInfo: state => state.USER.userInfo,
				myAsset: state => state.USER.myAsset
			})
		},
		data() {
			return {
				menuButtonBoundingClientRect: app.globalData.menuButtonBoundingClientRect,
				dzfCount: 0
			}
		},
		methods: {
			// 页面跳转
			async pageJump(url) {
				if (!this.isLogin) {
					await this.$store.dispatch('USER/editUserInfo')
				} else {
					uni.navigateTo({
						url
					})
				}
			},
			mendianguanli() {
				uni.navigateTo({
					url: 'pages/mendianguanli/index'
				});
			},
			// 登录
			async getAvatar() {


			},
			// 初始化
			async init() {
				uni.showLoading({
					title: '加载中...',
					mask: true
				})
				if (!uni.getStorageSync('token')) {
					await this.$store.dispatch('USER/initUserInfo')
				}
				await this.$store.dispatch('USER/getAssetInfo')
				await this.getDzfCount()
				uni.hideLoading()
			},
			// 待支付条数
			async getDzfCount() {
				await this.$apiRequest({
					url: '/user.Order/list',
					data: {
						page: 1,
						limit: 10,
						type: 1
					}
				}).then(data => {
					this.dzfCount = data.count
				})
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