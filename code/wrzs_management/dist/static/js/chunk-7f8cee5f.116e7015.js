(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-7f8cee5f"],{"129f":function(e,t){e.exports=Object.is||function(e,t){return e===t?0!==e||1/e===1/t:e!=e&&t!=t}},"841c":function(e,t,n){"use strict";var a=n("d784"),i=n("825a"),o=n("1d80"),r=n("129f"),l=n("14c3");a("search",1,(function(e,t,n){return[function(t){var n=o(this),a=void 0==t?void 0:t[e];return void 0!==a?a.call(t,n):new RegExp(t)[e](String(n))},function(e){var a=n(t,e,this);if(a.done)return a.value;var o=i(e),c=String(this),s=o.lastIndex;r(s,0)||(o.lastIndex=0);var u=l(o,c);return r(o.lastIndex,s)||(o.lastIndex=s),null===u?-1:u.index}]}))},c9c6:function(e,t,n){"use strict";n.r(t);var a=function(){var e=this,t=e.$createElement,n=e._self._c||t;return n("div",{staticClass:"region-manage"},["index"===e.type?n("Index",{on:{changeComponents:e.changeComponents}}):e._e(),"add"===e.type?n("Add",{on:{changeComponents:e.changeComponents}}):e._e(),"edit"===e.type?n("Edit",{on:{changeComponents:e.changeComponents}}):e._e()],1)},i=[],o=function(){var e=this,t=e.$createElement,n=e._self._c||t;return n("div",{staticClass:"region-manage-index"},[n("el-button",{attrs:{type:"primary",icon:"el-icon-plus"},on:{click:e.addRegion}},[e._v("添加区域")]),n("el-button",{attrs:{type:"warning",icon:"el-icon-edit",disabled:1!==e.Mix_tableData.selectionObj.length},on:{click:function(t){return e.editRegion(e.Mix_tableData.selectionObj[0])}}},[e._v("编辑")]),n("el-button",{attrs:{type:"danger",icon:"el-icon-delete",disabled:!e.Mix_tableData.selectionIds.length},on:{click:function(t){return e.delRegion(e.Mix_tableData.selectionIds)}}},[e._v("批量删除")]),n("el-input",{staticStyle:{width:"200px"},attrs:{placeholder:"区域名称",maxlength:"50",clearable:""},on:{clear:e.searchSubmit},nativeOn:{keypress:function(t){return!t.type.indexOf("key")&&e._k(t.keyCode,"enter",13,t.key,"Enter")?null:e.searchSubmit(t)}},model:{value:e.tableData.public,callback:function(t){e.$set(e.tableData,"public",t)},expression:"tableData.public"}}),n("el-button",{attrs:{type:"primary",icon:"el-icon-search"},on:{click:e.searchSubmit}},[e._v("查询")]),n("el-table",{ref:"table",staticClass:"mt15",attrs:{data:e.Mix_tableData.list,border:"",height:"calc(100vh - 225px)"},on:{"selection-change":function(t){return e.$selectionChange(t,"city_id")}}},[n("el-table-column",{attrs:{type:"selection"}}),n("el-table-column",{attrs:{label:"ID",prop:"city_id"}}),n("el-table-column",{attrs:{prop:"city_name",label:"区域名称"}}),n("el-table-column",{attrs:{prop:"longitude",label:"经度"}}),n("el-table-column",{attrs:{prop:"latitude",label:"纬度"}}),n("el-table-column",{attrs:{label:"操作",align:"center"},scopedSlots:e._u([{key:"default",fn:function(t){return[n("el-button",{attrs:{type:"warning",size:"mini",icon:"el-icon-edit"},on:{click:function(n){return e.editRegion(t.row)}}},[e._v("编辑")]),n("el-button",{attrs:{type:"danger",size:"mini",icon:"el-icon-delete"},on:{click:function(n){return e.delRegion([t.row.city_id])}}},[e._v("删除")])]}}])})],1),n("Pagination",{attrs:{tableData:e.Mix_tableData,getList:e.getRegionList},on:{pageChange:e.$pageChange}})],1)},r=[],l=n("c7eb"),c=n("1da1"),s=n("b775");function u(e){return Object(s["a"])({url:"/region.City/list",method:"post",data:e})}function d(e){return Object(s["a"])({url:"/region.City/add",method:"post",data:e})}function p(e){return Object(s["a"])({url:"/region.City/edit",method:"post",data:e})}function m(e){return Object(s["a"])({url:"/region.City/del",method:"post",data:e})}var f={name:"region-manage-index",data:function(){return{tableData:{public:""}}},methods:{addRegion:function(){this.$emit("changeComponents","add")},editRegion:function(e){var t=this;return Object(c["a"])(Object(l["a"])().mark((function n(){return Object(l["a"])().wrap((function(n){while(1)switch(n.prev=n.next){case 0:return n.next=2,t.$store.dispatch("region-manage/setRegionData",e);case 2:t.$emit("changeComponents","edit");case 3:case"end":return n.stop()}}),n)})))()},delRegion:function(e){var t=this;this.$confirm("您确定要删除该区域吗?","温馨提示",{cancelButtonText:"点错了",type:"warning"}).then(Object(c["a"])(Object(l["a"])().mark((function n(){return Object(l["a"])().wrap((function(n){while(1)switch(n.prev=n.next){case 0:return n.next=2,m({city_id:e}).then((function(){t.$message.success("删除成功"),t.Mix_tableData.currentPage=1,t.getRegionList()}));case 2:case"end":return n.stop()}}),n)})))).catch((function(){}))},searchSubmit:function(){this.Mix_tableData.currentPage=1,this.getRegionList()},getRegionList:function(){var e=this;return Object(c["a"])(Object(l["a"])().mark((function t(){return Object(l["a"])().wrap((function(t){while(1)switch(t.prev=t.next){case 0:return t.next=2,u({page:e.Mix_tableData.currentPage,limit:e.Mix_tableData.pageSize,public:e.tableData.public}).then((function(t){e.Mix_tableData.list=t.list,e.Mix_tableData.total=t.count}));case 2:case"end":return t.stop()}}),t)})))()}},mounted:function(){this.getRegionList()}},g=f,h=n("2877"),b=Object(h["a"])(g,o,r,!1,null,"16f657ba",null),x=b.exports,y=function(){var e=this,t=e.$createElement,n=e._self._c||t;return n("div",{staticClass:"region-manage-add"},[n("el-row",[n("el-col",{attrs:{span:12}},[n("el-form",{ref:"form",attrs:{model:e.form,rules:e.formRules,"label-width":"120px"}},[n("el-form-item",{attrs:{prop:"city_name",label:"区域名称"}},[n("el-input",{staticStyle:{width:"300px"},attrs:{placeholder:"请输入区域名称，如北京市、贵阳市等",maxlength:"20"},model:{value:e.form.city_name,callback:function(t){e.$set(e.form,"city_name","string"===typeof t?t.trim():t)},expression:"form.city_name"}})],1),n("el-form-item",{attrs:{prop:"longitude",label:"经度"}},[n("el-input",{staticStyle:{width:"300px"},attrs:{placeholder:"经度",readonly:"",disabled:""},model:{value:e.form.longitude,callback:function(t){e.$set(e.form,"longitude",t)},expression:"form.longitude"}})],1),n("el-form-item",{attrs:{prop:"latitude",label:"纬度"}},[n("el-input",{staticStyle:{width:"300px"},attrs:{placeholder:"纬度",readonly:"",disabled:""},model:{value:e.form.latitude,callback:function(t){e.$set(e.form,"latitude",t)},expression:"form.latitude"}})],1),n("el-form-item",[n("el-button",{attrs:{type:"default",icon:"el-icon-back"},on:{click:e.back}},[e._v("返回")]),n("el-button",{attrs:{type:"primary",icon:"el-icon-check"},on:{click:e.submitRegion}},[e._v("确定添加")])],1)],1)],1),n("el-col",{attrs:{span:12}},[n("el-input",{attrs:{id:"tipinput",placeholder:"请输入查询地址",clearable:""},model:{value:e.searchText,callback:function(t){e.searchText=t},expression:"searchText"}}),n("div",{staticClass:"mt15",staticStyle:{width:"100%",height:"500px"},attrs:{id:"map"}})],1)],1)],1)},v=[],M=(n("ac1f"),n("841c"),n("b0c0"),{name:"region-manage-add",data:function(){return{searchText:"",form:{city_name:"",longitude:"",latitude:""},formRules:{city_name:[{required:!0,message:"请输入区域名称"}],longitude:[{required:!0,message:"请点击地图选择坐标"}],latitude:[{required:!0,message:"请点击地图选择坐标"}]},MAP:null}},methods:{back:function(){this.$emit("changeComponents","index")},initMapFilterPlace:function(){var e=this,t=new AMap.Autocomplete({input:"tipinput"}),n=new AMap.PlaceSearch({map:this.MAP}),a=new AMap.Geocoder({});AMap.event.addListener(t,"select",(function(t){n.setCity(t.poi.adcode),n.search(t.poi.name),e.form.latitude=t.poi.location.lat,e.form.longitude=t.poi.location.lng,a.getAddress(t.poi.location.lng+","+t.poi.location.lat,(function(t,n){"complete"===t&&n.regeocode?e.form.city_name=n.regeocode.addressComponent.city:e.$message.error("根据经纬度查询地址失败")}))}))},initMapMarkerPlace:function(){var e=this,t=null,n=null,a=new AMap.Geocoder({});this.MAP.on("click",(function(i){e.form.longitude=i.lnglat.getLng(),e.form.latitude=i.lnglat.getLat(),t=new AMap.Marker({position:[e.form.longitude,e.form.latitude]}),n&&e.MAP.remove(n),n=t,t.setMap(e.MAP),a.getAddress(i.lnglat.getLng()+","+i.lnglat.getLat(),(function(t,n){"complete"===t&&n.regeocode?e.form.city_name=n.regeocode.addressComponent.city:e.$message.error("根据经纬度查询地址失败")}))}))},initMap:function(){this.MAP=new AMap.Map("map",{resizeEnable:!0,center:[106.630153,26.647661],zoom:13}),this.initMapFilterPlace(),this.initMapMarkerPlace()},submitRegion:function(){var e=this;this.$refs["form"].validate(function(){var t=Object(c["a"])(Object(l["a"])().mark((function t(n){return Object(l["a"])().wrap((function(t){while(1)switch(t.prev=t.next){case 0:if(!n){t.next=5;break}return t.next=3,d(e.form).then((function(){e.$message.success("添加成功"),e.back()}));case 3:t.next=6;break;case 5:return t.abrupt("return",!1);case 6:case"end":return t.stop()}}),t)})));return function(e){return t.apply(this,arguments)}}())}},mounted:function(){this.initMap()}}),k=M,_=Object(h["a"])(k,y,v,!1,null,null,null),w=_.exports,A=function(){var e=this,t=e.$createElement,n=e._self._c||t;return n("div",{staticClass:"region-manage-edit"},[n("el-row",[n("el-col",{attrs:{span:12}},[n("el-form",{ref:"form",attrs:{model:e.form,rules:e.formRules,"label-width":"120px"}},[n("el-form-item",{attrs:{prop:"city_name",label:"区域名称"}},[n("el-input",{staticStyle:{width:"300px"},attrs:{placeholder:"请输入区域名称，如北京市、贵阳市等",maxlength:"20"},model:{value:e.form.city_name,callback:function(t){e.$set(e.form,"city_name","string"===typeof t?t.trim():t)},expression:"form.city_name"}})],1),n("el-form-item",{attrs:{prop:"longitude",label:"经度"}},[n("el-input",{staticStyle:{width:"300px"},attrs:{placeholder:"经度",readonly:"",disabled:""},model:{value:e.form.longitude,callback:function(t){e.$set(e.form,"longitude",t)},expression:"form.longitude"}})],1),n("el-form-item",{attrs:{prop:"latitude",label:"纬度"}},[n("el-input",{staticStyle:{width:"300px"},attrs:{placeholder:"纬度",readonly:"",disabled:""},model:{value:e.form.latitude,callback:function(t){e.$set(e.form,"latitude",t)},expression:"form.latitude"}})],1),n("el-form-item",[n("el-button",{attrs:{type:"default",icon:"el-icon-back"},on:{click:e.back}},[e._v("返回")]),n("el-button",{attrs:{type:"primary",icon:"el-icon-check"},on:{click:e.submitRegion}},[e._v("确定修改")])],1)],1)],1),n("el-col",{attrs:{span:12}},[n("el-input",{attrs:{id:"tipinput",placeholder:"请输入查询地址",clearable:""},model:{value:e.searchText,callback:function(t){e.searchText=t},expression:"searchText"}}),n("div",{staticClass:"mt15",staticStyle:{width:"100%",height:"500px"},attrs:{id:"map"}})],1)],1)],1)},C=[],O=n("5530"),j=n("2f62"),$={name:"region-manage-edit",computed:Object(O["a"])({},Object(j["c"])({regionData:function(e){return e["region-manage"].regionData}})),data:function(){return{searchText:"",form:{},formRules:{city_name:[{required:!0,message:"请输入区域名称"}],longitude:[{required:!0,message:"请点击地图选择坐标"}],latitude:[{required:!0,message:"请点击地图选择坐标"}]},MAP:null}},methods:{back:function(){this.$emit("changeComponents","index")},initMapFilterPlace:function(){var e=this,t=new AMap.Autocomplete({input:"tipinput"}),n=new AMap.PlaceSearch({map:this.MAP}),a=new AMap.Geocoder({});AMap.event.addListener(t,"select",(function(t){n.setCity(t.poi.adcode),n.search(t.poi.name),e.form.latitude=t.poi.location.lat,e.form.longitude=t.poi.location.lng,a.getAddress(t.poi.location.lng+","+t.poi.location.lat,(function(t,n){"complete"===t&&n.regeocode?e.form.city_name=n.regeocode.addressComponent.city:e.$message.error("根据经纬度查询地址失败")}))}))},initMapMarkerPlace:function(){var e=this,t=null,n=null,a=new AMap.Geocoder({});this.MAP.on("click",(function(i){e.form.longitude=i.lnglat.getLng(),e.form.latitude=i.lnglat.getLat(),t=new AMap.Marker({position:[e.form.longitude,e.form.latitude]}),n&&e.MAP.remove(n),n=t,t.setMap(e.MAP),a.getAddress(i.lnglat.getLng()+","+i.lnglat.getLat(),(function(t,n){"complete"===t&&n.regeocode?e.form.city_name=n.regeocode.addressComponent.city:e.$message.error("根据经纬度查询地址失败")}))}))},initMap:function(){this.MAP=new AMap.Map("map",{resizeEnable:!0,center:[106.630153,26.647661],zoom:13}),this.initMapFilterPlace(),this.initMapMarkerPlace()},submitRegion:function(){var e=this;this.$refs["form"].validate(function(){var t=Object(c["a"])(Object(l["a"])().mark((function t(n){return Object(l["a"])().wrap((function(t){while(1)switch(t.prev=t.next){case 0:if(!n){t.next=5;break}return t.next=3,p(e.form).then((function(){e.$message.success("修改成功"),e.back()}));case 3:t.next=6;break;case 5:return t.abrupt("return",!1);case 6:case"end":return t.stop()}}),t)})));return function(e){return t.apply(this,arguments)}}())}},mounted:function(){this.initMap(),this.form=this.regionData}},P=$,D=Object(h["a"])(P,A,C,!1,null,null,null),R=D.exports,S={name:"region-manage",components:{Index:x,Add:w,Edit:R},data:function(){return{type:"index"}},methods:{changeComponents:function(e){this.type=e}}},L=S,E=Object(h["a"])(L,a,i,!1,null,"377a7bbc",null);t["default"]=E.exports}}]);