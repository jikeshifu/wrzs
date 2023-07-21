(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-14f1a94d"],{"68a6":function(e,t,r){"use strict";r("d929")},c7c0:function(e,t,r){"use strict";r.r(t);var a=function(){var e=this,t=e.$createElement,r=e._self._c||t;return r("div",{staticClass:"order-manage"},["index"===e.type?r("Index",{on:{changeComponents:e.changeComponents}}):e._e()],1)},o=[],s=function(){var e=this,t=e.$createElement,r=e._self._c||t;return r("div",{staticClass:"order-manage-index"},[r("el-select",{attrs:{placeholder:"订单状态",clearable:"",filterable:""},model:{value:e.tableData.type,callback:function(t){e.$set(e.tableData,"type",t)},expression:"tableData.type"}},[r("el-option",{attrs:{label:"进行中",value:2}}),r("el-option",{attrs:{label:"已完成",value:3}})],1),r("el-input",{staticStyle:{width:"300px"},attrs:{placeholder:"订单号",maxlength:"50"},model:{value:e.tableData.order_sn,callback:function(t){e.$set(e.tableData,"order_sn",t)},expression:"tableData.order_sn"}}),r("el-button",{attrs:{icon:"el-icon-search",type:"primary"},on:{click:e.submitSearch}},[e._v("查询")]),r("el-table",{ref:"table",staticClass:"mt15",attrs:{data:e.Mix_tableData.list,border:"",height:"calc(100vh - 225px)"}},[r("el-table-column",{attrs:{label:"ID",prop:"order_id",width:"80"}}),r("el-table-column",{attrs:{label:"订单号",prop:"order_sn"}}),r("el-table-column",{attrs:{label:"支付方式",prop:"pay_type"},scopedSlots:e._u([{key:"default",fn:function(t){return["balance"===t.row.pay_type?r("el-tag",[e._v("钱包余额")]):e._e(),"wxpay"===t.row.pay_type?r("el-tag",{attrs:{type:"danger"}},[e._v("微信")]):e._e()]}}])}),r("el-table-column",{attrs:{label:"商品列表"},scopedSlots:e._u([{key:"default",fn:function(t){return[r("el-button",{attrs:{type:"text"},on:{click:function(r){return e.checkGoods(t.row)}}},[e._v("查看")])]}}])}),r("el-table-column",{attrs:{label:"下单用户"},scopedSlots:e._u([{key:"default",fn:function(t){return[r("el-popover",{attrs:{trigger:"click",placement:"top-end",width:"400"}},[r("el-form",{attrs:{"label-width":"120px"}},[r("el-form-item"),r("el-form-item",{attrs:{label:"头像"}},[r("el-image",{staticStyle:{width:"100px",height:"100px"},attrs:{src:t.row.memberWechat.avatar_url,fit:"cover"}})],1),r("el-form-item",{attrs:{label:"昵称"}},[e._v(e._s(t.row.memberWechat.nick_name))]),r("el-form-item",{attrs:{label:"联系方式"}},[e._v(e._s(t.row.memberWechat.mobile))]),r("el-form-item",{attrs:{label:"地区"}},[e._v(e._s(t.row.memberWechat.province))])],1),r("el-button",{attrs:{slot:"reference",type:"text"},slot:"reference"},[e._v("查看")])],1)]}}])}),r("el-table-column",{attrs:{label:"收货信息"},scopedSlots:e._u([{key:"default",fn:function(t){return[r("el-popover",{attrs:{trigger:"click",placement:"top-end",width:"400"}},[r("el-row",{attrs:{type:"flex",justify:"space-between"}},[r("el-col",{attrs:{span:12}},[e._v("姓名:"+e._s(t.row.orderAddress.user_name))]),r("el-col",{attrs:{span:12}},[e._v("联系方式:"+e._s(t.row.orderAddress.mobile))])],1),r("el-row",{staticClass:"mt15",attrs:{type:"flex",justify:"space-between"}},[r("el-col",{attrs:{span:12}},[e._v("地址:"+e._s(t.row.orderAddress.address))])],1),r("el-button",{attrs:{slot:"reference",type:"text"},slot:"reference"},[e._v("查看")])],1)]}}])}),r("el-table-column",{attrs:{label:"订单状态"},scopedSlots:e._u([{key:"default",fn:function(t){return[e._v(" "+e._s(1===t.row.order_status?"待发货":2===t.row.order_status?"已发货":3===t.row.order_status?"已完成":"")+" ")]}}])}),r("el-table-column",{attrs:{label:"操作",align:"center"},scopedSlots:e._u([{key:"default",fn:function(t){return[1===t.row.order_status?r("el-button",{attrs:{type:"primary",size:"mini"},on:{click:function(r){return e.showDeliverGoodsDialog(t.row)}}},[e._v("发货")]):e._e(),1===t.row.order_status?r("el-button",{attrs:{type:"danger",size:"mini"},on:{click:function(r){return e.cancelOrder(t.row.order_id)}}},[e._v("取消订单")]):e._e()]}}])})],1),r("Pagination",{attrs:{tableData:e.Mix_tableData,getList:e.getOrderList},on:{pageChange:e.$pageChange}}),r("el-drawer",{attrs:{title:"商品列表",visible:e.drawer.show,direction:"ltr"},on:{"update:visible":function(t){return e.$set(e.drawer,"show",t)}}},[r("div",{staticClass:"drawer-wrap"},e._l(e.drawer.data,(function(t){return r("div",{key:t.id,staticClass:"item"},[r("el-image",{staticStyle:{width:"100px",height:"100px"},attrs:{src:e.baseUrl+t.goods_info.goods_image,fit:"cover"}}),r("div",{staticClass:"info"},[r("h4",[e._v(e._s(t.goods_info.goods_name))]),r("div",{staticClass:"foot"},[r("div",{staticClass:"left"},[e._v("￥"+e._s(t.goods_info.goods_price))]),r("div",{staticClass:"right"},[e._v("x"+e._s(t.goods_number))])])])],1)})),0)]),r("el-dialog",{attrs:{title:"发货信息",visible:e.dialog.show,width:"30%","show-close":!1},on:{"update:visible":function(t){return e.$set(e.dialog,"show",t)}}},[r("el-form",{ref:"form",attrs:{model:e.dialog.form,rules:e.dialog.formRules}},[r("el-form-item",{attrs:{prop:"deliver_sn",label:"发货单号"}},[r("el-input",{attrs:{placeholder:"请输入发货单号",maxlength:"50"},model:{value:e.dialog.form.deliver_sn,callback:function(t){e.$set(e.dialog.form,"deliver_sn","string"===typeof t?t.trim():t)},expression:"dialog.form.deliver_sn"}})],1),r("el-form-item",{attrs:{label:"备注"}},[r("el-input",{attrs:{placeholder:"备注",maxlength:"100","show-word-limit":"",type:"textarea",rows:"5"},model:{value:e.dialog.form.deliver_remarks,callback:function(t){e.$set(e.dialog.form,"deliver_remarks",t)},expression:"dialog.form.deliver_remarks"}})],1)],1),r("span",{attrs:{slot:"footer"},slot:"footer"},[r("el-button",{on:{click:e.hideDialog}},[e._v("取消")]),r("el-button",{attrs:{type:"primary"},on:{click:e.submitDialog}},[e._v("确定")])],1)],1)],1)},l=[],n=r("c7eb"),i=r("1da1"),c=r("b775");function d(e){return Object(c["a"])({url:"/shop.Order/list",method:"post",data:e})}function u(e){return Object(c["a"])({url:"/shop.Order/deliver",method:"post",data:e})}function p(e){return Object(c["a"])({url:"/shop.Order/cancel",method:"post",data:e})}var f={name:"order-manage-index",data:function(){return{tableData:{order_sn:"",type:""},drawer:{show:!1,data:[]},dialog:{show:!1,form:{order_id:"",deliver_sn:"",deliver_remarks:""},formRules:{deliver_sn:[{required:!0,message:"请输入发货单号"}]}}}},methods:{cancelOrder:function(e){var t=this;this.$confirm("您确定要取消该订单吗?","温馨提示",{cancelButtonText:"点错了",type:"warning"}).then(Object(i["a"])(Object(n["a"])().mark((function r(){return Object(n["a"])().wrap((function(r){while(1)switch(r.prev=r.next){case 0:return r.next=2,p({order_id:e}).then((function(){t.$message.success("取消成功"),t.Mix_tableData.currentPage=1,t.getOrderList()}));case 2:case"end":return r.stop()}}),r)})))).catch((function(){}))},submitDialog:function(){var e=this;this.$refs["form"].validate(function(){var t=Object(i["a"])(Object(n["a"])().mark((function t(r){return Object(n["a"])().wrap((function(t){while(1)switch(t.prev=t.next){case 0:if(!r){t.next=5;break}return t.next=3,u(e.dialog.form).then((function(){e.$message.success("发货成功"),e.hideDialog(),e.getOrderList()}));case 3:t.next=6;break;case 5:return t.abrupt("return",!1);case 6:case"end":return t.stop()}}),t)})));return function(e){return t.apply(this,arguments)}}())},hideDialog:function(){this.$refs["form"].resetFields(),this.dialog.show=!1},showDeliverGoodsDialog:function(e){this.dialog.form.order_id=e.order_id,this.dialog.show=!0},checkGoods:function(e){this.drawer.data=e.orderGoods,this.drawer.show=!0},submitSearch:function(){this.Mix_tableData.currentPage=1,this.getOrderList()},getOrderList:function(){var e=this;return Object(i["a"])(Object(n["a"])().mark((function t(){return Object(n["a"])().wrap((function(t){while(1)switch(t.prev=t.next){case 0:return t.next=2,d({page:e.Mix_tableData.currentPage,limit:e.Mix_tableData.pageSize,type:e.tableData.type,order_sn:e.tableData.order_sn}).then((function(t){e.Mix_tableData.list=t.list,e.Mix_tableData.total=t.count}));case 2:case"end":return t.stop()}}),t)})))()}},mounted:function(){this.getOrderList()}},m=f,b=(r("68a6"),r("2877")),_=Object(b["a"])(m,s,l,!1,null,"7b59e086",null),h=_.exports,g={name:"order-manage",components:{Index:h},data:function(){return{type:"index"}},methods:{changeComponents:function(e){this.type=e}}},v=g,w=Object(b["a"])(v,a,o,!1,null,"03e215b6",null);t["default"]=w.exports},d929:function(e,t,r){}}]);