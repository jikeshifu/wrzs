<template>
	<view class="content">
		<view 
			class="popup" 
			:class="actionConfig.specClass"
			@touchmove.stop.prevent="stopPrevent"
			@click="toggleSpec"
		>
			<!-- 遮罩层 -->
			<view class="mask"></view>
			<view class="layer attr-content" @click.stop="stopPrevent">
				<!-- 内容区域 -->
				<view class="action-title" 
				v-if="actionConfig.title&&actionConfig.title.length" 
				@click="titleClick(actionConfig.type)" 
				:style='{"border-bottom-color":actionConfig.isBorderColor?"#EBEBEB":"#fff"}'
				>{{actionConfig.title}}</view>
				<view class="action-summary" v-if="actionConfig.summary && actionConfig.summary.length"><view class="summary-center">{{actionConfig.summary}}</view></view>
				<view class="action-list">
					<view class="action-item"  v-for="(item,index) of actionConfig.list" :key="index" @click="itemClick(index,actionConfig.type)" >
						<view v-if='typeof(item)=="string"'>{{item}}</view>
						<view v-else :style='{"color":item.color,"background":item.background?item.background:"#fff"}'>{{item.title}}</view>
					</view>
				</view>
				<view class="cancel" @click="cancelClick" v-if="!actionConfig.isCloseCancel" :style="{color:actionConfig.cancelColor}">取消</view>
			</view>
		</view>
	</view>
</template>
<script>
	export default {
		data() {
			return {
				
				actionConfig:{
					title:'',
					summary:'',
					list:[],
					type:null,
					isCloseCancel:false,
					cancelColor:"#333333",
					isBorderColor:false,
					titleColor:"#333333",
					specClass: 'none',
				}
			}
		},
		methods: {
			toggleSpec() {
				if(this.actionConfig.specClass === 'show'){
					this.actionConfig.specClass = 'hide';
					setTimeout(() => {
						this.actionConfig.specClass = 'none';
					}, 250);
				}else if(this.actionConfig.specClass === 'none'){
					this.actionConfig.specClass = 'show';
				}
			},
			stopPrevent(){},
			itemClick(index,type){
				this.$emit("itemClick",index,type)
				this.toggleSpec()
			},
			cancelClick(){
				this.toggleSpec()
			},
			titleClick(type){
				this.$emit("titleClick",type)
				this.toggleSpec()
			}
		}
	}
</script>
<style lang="scss" scoped>
	.popup {
		position: fixed;
		left: 0;
		top: 0;
		right: 0;
		bottom: 0;
		z-index: 99;
		
		&.show {
			display: block;
			.mask{
				animation: showPopup 0.2s linear both;
			}
			.layer {
				animation: showLayer 0.2s linear both;
			}
		}
		&.hide {
			.mask{
				animation: hidePopup 0.2s linear both;
			}
			.layer {
				animation: hideLayer 0.2s linear both;
			}
		}
		&.none {
			display: none;
		}
		.mask{
			position: fixed;
			top: 0;
			width: 100%;
			height: 100%;
			z-index: 1;
			background-color: rgba(0, 0, 0, 0.5);
		}
		.layer {
			position: fixed;
			z-index: 99;
			bottom: var(--window-bottom);
			width: 100%;
			background-color: #F7F7F7;
			.action-title{
				height: 88upx;
				line-height: 88upx;
				text-align: center;
				color: #333;
				background-color: #fff;
				border-bottom-width: 2upx; 
				border-bottom-style: solid; 
			}
			.action-summary{
				text-align: center;
				color: #888;
				background-color: #fff;
				box-sizing: border-box;
				padding:  30upx 72upx;
				display: flex;
				justify-content: center;
				align-items: center;
				
				.summary-center{
					font-size: 28upx;
					color: #666666;
				}
			}
			.action-list{
				background-color: #fff;
				.action-item{
					height: 88upx;
					line-height: 88upx;
					text-align: center;
					color: #333;
					border-top: 2upx solid #EBEBEB;
				}
			}
			.cancel{
				height: 88upx;
				line-height: 88upx;
				text-align: center;
				color: #333;
				background-color: #fff;
				margin-top: 12upx;
			}
		}
		@keyframes showPopup {
			0% {
				opacity: 0;
			}
			100% {
				opacity: 1;
			}
		}
		@keyframes hidePopup {
			0% {
				opacity: 1;
			}
			100% {
				opacity: 0;
			}
		}
		@keyframes showLayer {
			0% {
				transform: translateY(120%);
			}
			100% {
				transform: translateY(0%);
			}
		}
		@keyframes hideLayer {
			0% {
				transform: translateY(0);
			}
			100% {
				transform: translateY(120%);
			}
		}
	}
</style>