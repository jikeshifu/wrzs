(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-2d0d0196"],{6710:function(t,e,a){"use strict";a.r(e);var n=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",{staticClass:"profit"},["index"===t.type?a("Index",{on:{componentsChange:t.componentsChange}}):t._e()],1)},i=[],r=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",{staticClass:"profit-index"},[a("el-date-picker",{attrs:{type:"daterange","range-separator":"至","start-placeholder":"开始日期","end-placeholder":"结束日期","picker-options":t.pickerOptions},on:{change:t.datePickerChange},model:{value:t.dateRangeValue,callback:function(e){t.dateRangeValue=e},expression:"dateRangeValue"}}),a("el-button",{attrs:{type:"primary",icon:"el-icon-search"},on:{click:t.submitSearch}},[t._v("筛选")]),a("el-tag",{attrs:{type:"danger"}},[t._v("总收益："+t._s(t.profit)+"元")]),a("el-table",{ref:"table",staticClass:"mt15",attrs:{data:t.Mix_tableData.list,border:"",height:"calc(100vh - 225px)"}},[a("el-table-column",{attrs:{prop:"order_id",label:"订单ID"}}),a("el-table-column",{attrs:{prop:"order_profit",label:"收益金额(元)"}}),a("el-table-column",{attrs:{prop:"order_type",label:"订单类型"}}),a("el-table-column",{attrs:{label:"购买时间"},scopedSlots:t._u([{key:"default",fn:function(e){return[t._v(t._s(t.$dateFormatter(+(e.row.created_at+"000"),"yy-mm-dd hh:mm:ss")))]}}])})],1),a("Pagination",{attrs:{tableData:t.Mix_tableData,getList:t.getProfitList},on:{pageChange:t.$pageChange}})],1)},o=[],l=a("c7eb"),s=a("1da1"),c=(a("fb6a"),a("d3b7"),a("25f0"),a("b775"));function d(t){return Object(c["a"])({url:"/join.User/profit",method:"post",data:t})}var p={name:"profit-index",data:function(){return{pickerOptions:{disabledDate:function(t){return t.getTime()>Date.now()}},dateRangeValue:"",tableData:{start_time:"",end_time:""},profit:"0.00"}},methods:{datePickerChange:function(t){t?(this.tableData.start_time=t[0].getTime().toString().slice(0,10),this.tableData.end_time=t[1].getTime().toString().slice(0,10)):(this.tableData.start_time="",this.tableData.end_time="")},submitSearch:function(){this.Mix_tableData.currentPage=1,this.getProfitList()},getProfitList:function(){var t=this;return Object(s["a"])(Object(l["a"])().mark((function e(){return Object(l["a"])().wrap((function(e){while(1)switch(e.prev=e.next){case 0:return e.next=2,d({page:t.Mix_tableData.currentPage,limit:t.Mix_tableData.pageSize,start_time:t.tableData.start_time,end_time:t.tableData.end_time}).then((function(e){t.Mix_tableData.list=e.list,t.Mix_tableData.total=e.count,t.profit=e.profit}));case 2:case"end":return e.stop()}}),e)})))()}},mounted:function(){this.getProfitList()}},u=p,b=a("2877"),m=Object(b["a"])(u,r,o,!1,null,"706a75de",null),f=m.exports,h={name:"profit",components:{Index:f},data:function(){return{type:"index"}},methods:{componentsChange:function(t){this.type=t}}},g=h,_=Object(b["a"])(g,n,i,!1,null,"49cc07ee",null);e["default"]=_.exports}}]);