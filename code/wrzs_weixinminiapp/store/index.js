import Vue from 'vue'
import Vuex from 'vuex'
// 当前坐标
import LOCATION from './modules/location.js'
// 用户信息
import USER from './modules/user.js'
// 门店信息
import STORE from './modules/store.js'
// 房间信息
import ROOM from './modules/room.js'
// 广告信息
import ADV from './modules/adv.js'
// 商城商品信息
import pagesC_GOODS from './modules/pagesC/goods.js'
// 购物车信息
import pagesC_GWCAR from './modules/pagesC/gwcar.js'
// 优选商城待支付数目
import pagesC_DZF from './modules/pagesC/dzf.js'

Vue.use(Vuex)

const store = new Vuex.Store({
	modules: {
		LOCATION,
		USER,
		ADV,
		STORE,
		ROOM,
		pagesC_GOODS,
		pagesC_GWCAR,
		pagesC_DZF
	}
});

export default store