<template>
	<view class="content-body">
		<Topbar title="广告内容"></Topbar>
		<view class="scroll-view">
			<scroll-view scroll-y>
				<uParse :content="advData.content" :noData="' '"></uParse>
			</scroll-view>
		</view>
	</view>
</template>

<script>
	import { mapState } from 'vuex'
	import uParse from '@/components/u-parse/u-parse.vue'
	
	export default {
		computed: {
			...mapState({
				advData: state => state['ADV'].advData
			})
		},
		components: {
			uParse
		},
		data() {
			return {
				
			}
		}
	}
</script>

<style lang="scss">
	@import './index.scss';
</style>
