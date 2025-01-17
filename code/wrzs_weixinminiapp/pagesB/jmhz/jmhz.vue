<template>
	<view class="content">
		<view class="fixed-topbar">
			<image :src="baseUrl + '/static/imgs/jmhz/bg.png'" mode="widthFix"></image>
			<view class="fixed-item">
				<view :style="{ height: menuButtonBoundingClientRect.top + 'px' }"></view>
				<view class="title" :style="{ height: menuButtonBoundingClientRect.top + 'px' }">
					<text class="iconfont icon-left" @click="back"></text>加盟合作
				</view>
				<view :style="{ height: (menuButtonBoundingClientRect.top / 2) + 'px' }"></view>
			</view>
		</view>
		<view class="form-group">
			<view class="title">合作意向</view>
			<view class="form">
				<view class="input-group">
					<view class="left">姓名<text>*</text></view>
					<input type="text" v-model.trim="form.name" maxlength="20" placeholder="请输入您的姓名"
						placeholder-style="color:#c9c9c9" />
				</view>
				<view class="input-group">
					<view class="left">联系方式<text>*</text></view>
					<input type="number" v-model="form.mobile" maxlength="11" placeholder="请输入您的手机号"
						placeholder-style="color:#c9c9c9" />
				</view>
				<view class="input-group">
					<view class="left">所在城市<text>*</text></view>
					<picker mode="region" :value="pickerV" @change="pickerChange">
						<view :class="form.city ? 'active' : ''">{{ form.city ? form.city : '请选择城市' }}</view>
					</picker>
				</view>
			</view>
			<view class="title mt30">留言备注</view>
			<view class="textarea">
				<textarea v-model.trim="form.remarks" placeholder="请写下您想说的话" rows="5" placeholder-style="color:#c9c9c9" />
			</view>
			<view class="h190"></view>
			<view class="fixed-bottom-btn">
				<view class="p">请保持电话畅通，我们收到信息后会第一时间与您联系</view>
				<button type="primary" @click="submit">提交</button>
			</view>
		</view>
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
		data() {
			return {
				menuButtonBoundingClientRect: app.globalData.menuButtonBoundingClientRect,
				pickerV: [],
				form: {
					name: '',
					mobile: '',
					city: '',
					remarks: ''
				}
			}
		},
		methods: {
			async submit() {
				if (!this.form.name) {
					this.$showMsg('请输入姓名')
					return
				}
				if (!/^1[3456789]\d{9}$/.test(this.form.mobile)) {
					this.$showMsg('请输入11位正确的手机号')
					return
				}
				uni.showLoading({
					title: '正在提交...',
					mask: true
				})
				
				await this.$apiRequest({
					url: '/join.Join/apply',
					data: this.form
				}).then(() => {
					uni.hideLoading()
					uni.showModal({
						title: '温馨提示',
						content: '申请成功，请保持电话畅通',
						showCancel: false,
						complete: () => {
							uni.navigateBack({
								delta: 1
							})
						}
					})
				})
			},
			pickerChange(e) {
				this.pickerV = e.detail.value
				this.form.city = this.pickerV[1]
			},
			back() {
				uni.navigateBack({
					delta: 1
				})
			}
		},
		mounted() {
			this.form.city = this.currentLocationInfo.city
			this.pickerV[0] = this.currentLocationInfo.province
			this.pickerV[1] = this.currentLocationInfo.city
			this.pickerV[2] = this.currentLocationInfo.district
		}
	}
</script>

<style lang="scss">
	@import 'index.scss';
</style>