<template>
	<view class="content">
		<Topbar title="文化商城" class="fixed" :style="{ top: 0 }"></Topbar>
		<Topbar title="文化商城"></Topbar>
		<view class="search-box fixed"
			:style="{ top: (menuButtonBoundingClientRect.height + menuButtonBoundingClientRect.top + menuButtonBoundingClientRect.top / 2) + 'px' }">
			<navigator :url="`/pagesC/search/search?searchTxt=${searchTxt}`" :class="searchTxt ? 'active' : ''">
				<text class="iconfont icon-search"></text>{{ searchTxt ? searchTxt : '搜索' }}
			</navigator>
		</view>
		<view class="search-box">
			<navigator :url="`/pagesC/search/search?searchTxt=${searchTxt}`" :class="searchTxt ? 'active' : ''">
				<text class="iconfont icon-search"></text>{{ searchTxt ? searchTxt : '搜索' }}
			</navigator>
		</view>
		<view class="swiper-pics">
			<swiper circular :autoplay="true" :interval="5000" :duration="500">
				<swiper-item v-for="(item, index) in recommendList" :key="index">
					<view class="swiper-item" @click="pageJump(item)">
						<image :src="baseUrl + item.goods_image" mode="aspectFill"></image>
					</view>
				</swiper-item>
			</swiper>
			<view class="foot-tabs" id="scrollX">
				<scroll-view scroll-with-animation scroll-x :scroll-left="scrollLeft">
					<view :id="'item' + index" @click="tabChange(index, item.type_id)"
						:class="['item', (swiper.current === index ? 'active' : '')]"
						v-for="(item, index) in swiper.tabs" :key="item.type_id">{{ item.type_name }}</view>
				</scroll-view>
			</view>
			<view class="foot-tabs fixed" v-if="scrollXFixed"
				:style="{ top: menuButtonBoundingClientRect.height + menuButtonBoundingClientRect.top + menuButtonBoundingClientRect.top / 2 + 50 + 'px' }">
				<scroll-view scroll-with-animation scroll-x :scroll-left="scrollLeft">
					<view :id="'item' + index" @click="tabChange(index, item.type_id)"
						:class="['item', (swiper.current === index ? 'active' : '')]"
						v-for="(item, index) in swiper.tabs" :key="index">{{ item }}</view>
				</scroll-view>
			</view>
		</view>
		<view class="swiper-list">
			<w-loading v-if="swiper.pageLoading"></w-loading>
			<template v-else>
				<template v-if="swiper.list.length">
					<view class="row">
						<view class="col" v-for="item in swiper.list" :key="item.goods_id">
							<view class="item" @click="pageJump(item)">
								<image :src="baseUrl + item.goods_image" mode="aspectFill"></image>
								<view class="caption">
									<text>新品</text>{{ item.goods_name }}
								</view>
								<view class="foot"><text>￥</text>{{ item.goods_price }}</view>
							</view>
						</view>
					</view>
					<UniLoadMore :status="swiper.appendMoreStatus"></UniLoadMore>
				</template>
				<template v-else>
					<NoData title="还没有商品"></NoData>
				</template>
			</template>
		</view>
		<FixedIcons></FixedIcons>
	</view>
</template>

<script>
	const app = getApp()
	import FixedIcons from '@/components/pagesC/fixed-icons.vue'

	export default {
		components: {
			FixedIcons
		},
		data() {
			return {
				menuButtonBoundingClientRect: app.globalData.menuButtonBoundingClientRect,
				searchTxt: '',
				// 推荐列表
				recommendList: [],
				swiper: {
					current: 0,
					type_id: '',
					tabs: [{
						type_id: '',
						type_name: '全部'
					}],
					list: [],
					page: 1,
					limit: 20,
					pageLoading: true,
					appendMoreStatus: 'more'
				},
				scrollLeft: 0,
				scrollXFixed: false
			}
		},
		onPageScroll(e) {
			uni.createSelectorQuery().select('#scrollX').boundingClientRect(rect => {
				if (rect.top <= 118) {
					this.scrollXFixed = true
				} else {
					this.scrollXFixed = false
				}
			}).exec(() => {

			})
		},
		methods: {
			// tabs 变动
			tabChange(idx, type_id) {
				if (this.swiper.current === idx) {
					return
				}
				this.swiper.current = idx
				uni.createSelectorQuery().select(`#item${idx}`).boundingClientRect(rect => {
					this.scrollLeft = rect.left - 10
				}).exec(() => {

				})
				this.swiper.type_id = type_id
				this.swiper.list = []
				this.swiper.page = 1
				this.swiper.pageLoading = true
				this.getGoodsList()
			},
			// 页面跳转
			async pageJump(data) {
				await this.$store.dispatch('pagesC_GOODS/initGoodsData', data)
				uni.navigateTo({
					url: '/pagesC/goodsDetail/goodsDetail'
				})
			},
			// 商品分类列表
			async getGoodsTypeList() {
				const data = await this.$apiRequest({
					url: '/shop.Goods/goodsType'
				})
				data.list.forEach(item => {
					this.swiper.tabs.push(item)
				})
			},
			// 获取商品列表
			async getGoodsList() {
				await this.$apiRequest({
					url: '/shop.Goods/list',
					data: {
						type_id: this.swiper.type_id,
						page: this.swiper.page,
						limit: this.swiper.limit,
						goods_name: this.searchTxt
					}
				}).then(data => {
					if (data.list.length) {
						data.list.forEach(item => {
							this.swiper.list.push(item)
						})
						this.swiper.appendMoreStatus = 'more'
						if (this.swiper.list.length < this.swiper.limit) {
							this.swiper.appendMoreStatus = 'noMore'
						}
					} else {
						this.swiper.appendMoreStatus = 'noMore'
					}
				}).catch(() => {})
				this.swiper.pageLoading = false
			},
			// 获取推荐列表
			async getRecommendList() {
				const data = await this.$apiRequest({
					url: '/shop.Goods/recommend'
				})
				this.recommendList = data.list
			}
		},
		async mounted() {
			if (!uni.getStorageSync('token')) {
				await this.$store.dispatch('USER/initUserInfo')
			}
			await this.getGoodsTypeList();
			await this.getRecommendList();
			await this.$store.dispatch('USER/getAssetInfo')
			await this.getGoodsList()
		}
	}
</script>

<style lang="scss">
	@import 'index.scss';
</style>
