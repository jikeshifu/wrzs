<script>
	export default {
		globalData: {
			// 胶囊按钮信息
			menuButtonBoundingClientRect: {}
		},
		onLaunch() {
			this.getMenuButtonBoundingClientRect()
			this.updateManager()
		},
		onUnload() {
			uni.clearStorageSync()
		},
		methods: {
			// 更新
			updateManager() {
				const updateManager = wx.getUpdateManager()

				updateManager.onCheckForUpdate(function(res) {
					// 请求完新版本信息的回调
					console.log(res.hasUpdate)
				})

				updateManager.onUpdateReady(function() {
					wx.showModal({
						title: '更新提示',
						content: '新版本已经准备好，点击重启更新',
						showCancel: false,
						complete: () => {
							updateManager.applyUpdate()
						}
					})
				})

				updateManager.onUpdateFailed(function() {
					// 新版本下载失败
				})
			},
			// 获取胶囊按钮样式信息
			getMenuButtonBoundingClientRect() {
				this.globalData.menuButtonBoundingClientRect = wx.getMenuButtonBoundingClientRect()
			}
		}
	}
</script>

<style lang="scss">
	@import './styles/reset.css';
	@import './styles/iconfont.css';
</style>
