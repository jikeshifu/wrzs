<template>
  <div class="store_manage-edit">
    <el-button @click="back" icon="el-icon-back">返回门店列表</el-button>
    <el-row type="flex" justify="center">
      <el-col :span="8">
        <el-form ref="form" :model="form" :rules="formRules" label-width="120px">
          <el-form-item label="门店图片" prop="store_image">
            <el-upload
              :action="uploadURL"
              list-type="picture-card"
              :limit="1"
              :file-list="fileList"
              :before-upload="beforeUpload"
              :on-remove="removeUpload"
              :on-success="uploadSuccess">
              <i class="el-icon-plus"></i>
            </el-upload>
            <el-tag type="warning">仅限上传一张</el-tag>
          </el-form-item>
          <el-form-item label="门店名称" prop="store_name">
            <el-input placeholder="请输入门店名称" maxlength="100" v-model="form.store_name"></el-input>
          </el-form-item>
          <el-form-item label="门店介绍" prop="store_about">
            <el-input type="textarea" v-model="form.store_about" placeholder="说点什么吧..." maxlength="100" show-word-limit
                      rows="4"></el-input>
          </el-form-item>
          <el-form-item label="门店类型" prop="store_type">
            <el-radio-group v-model="form.store_type">
              <el-radio :label="1">共享茶室</el-radio>
              <!-- <el-radio :label="2">舞社唱吧</el-radio> -->
              <!-- <el-radio :label="3">民宿公寓</el-radio> -->
            </el-radio-group>
          </el-form-item>
          <el-form-item label="腾讯视频vid" prop="vid">
            <el-input placeholder="腾讯视频vid(选填)" v-model.trim="form.vid"></el-input>
          </el-form-item>
          <el-form-item label="门店标签" prop="label">
            <el-input v-model="form.label" maxlength="30" placeholder="多个标签用|分隔,例如:舞蹈房|免费WiFi,最多显示5个"></el-input>
          </el-form-item>
          <el-form-item label="联系电话" prop="contact">
            <el-input v-model="form.contact" placeholder="请输入联系电话" maxlength="20"
                      @input="form.contact = form.contact.replace(/[^\d]/g,'')"></el-input>
          </el-form-item>
          <el-form-item label="详细地址" prop="address">
            <el-input placeholder="请输入您门店的详细地址" v-model="form.address" type="textarea" rows="4"
                      maxlength="100"></el-input>
            <el-button type="success" icon="el-icon-location" size="mini" @click="showMapDialog">定位查询</el-button>
          </el-form-item>
          <el-form-item>
            <el-button @click="back">返回</el-button>
            <el-button type="primary" @click="submit">立即修改</el-button>
          </el-form-item>
        </el-form>
      </el-col>
    </el-row>
    <el-dialog
      title="高德地图"
      :visible.sync="mapDialogObj.visible"
      :show-close="false"
      top="50px"
      width="50%">
      <el-input id="tipinput" autocomplete="off" class="dialog-input" v-model="mapDialogObj.searchText"
                placeholder="请输入查询地址" clearable @clear="clearDialogData"></el-input>
      <div id="map" style="width:100%;height:500px"></div>
      <div class="dialog-ipt-group">
        <el-input :value="mapDialogObj.lat" placeholder="纬度" disabled></el-input>
        <el-input :value="mapDialogObj.lon" placeholder="经度" disabled></el-input>
        <el-input class="full" :value="mapDialogObj.address" placeholder="当前地址" disabled></el-input>
      </div>
      <div slot="footer">
        <el-button @click="mapDialogClose">取消</el-button>
        <el-button type="primary" @click="mapChooseSubmit">确定</el-button>
      </div>
    </el-dialog>
  </div>
</template>

<script>
import {mapState} from 'vuex'
import {editStore} from "@/api/store-manage/store-manage"

export default {
  name: "store-manage-edit",
  computed: {
    ...mapState({
      storeManage: state => state['store-manage/store-manage'].storeManage
    })
  },
  data() {
    // 验证空
    const validateEmpty = (rule, value, callback) => {
      if (!value.trim()) {
        if (rule.field === 'store_name') {
          callback(new Error('请输入门店名称'))
        }
        if (rule.field === 'address') {
          callback(new Error('请输入门店详细地址'))
        }
      }
      callback()
    }
    return {
      form: {},
      formRules: {
        label: [{
          required: true,
          message: '请输入门店标签'
        }],
        store_image: [{
          required: true,
          message: '请上传门店主图'
        }],
        store_name: [{
          required: true,
          message: '请输入门店名称'
        }, {
          validator: validateEmpty
        }],
        contact: [{
          required: true,
          message: '请输入联系方式'
        }],
        address: [{
          required: true,
          message: '请输入门店详细地址'
        }, {
          validator: validateEmpty
        }]
      },
      // 弹窗对象
      mapDialogObj: {
        visible: false,
        searchText: '',
        lat: '',
        lon: '',
        address: '',
        MAP: null
      },
      fileList: []
    }
  },
  methods: {
    beforeUpload(file) { // 文件上传前钩子
      const isJpgOrPng = file.type === 'image/jpeg' || file.type === 'image/png' //图片格式是否为png或jpg
      const isLt10M = file.size / 1024 / 1024 < 10 //判断图片大小是否超过10MB
      if (!isJpgOrPng) {
        this.$message.error('文件格式错误！')
      } else if(!isLt10M) {
        this.$message.error('文件不能超过10M！')
      } else {
        const _this = this
        return new Promise(resolve => {
          const reader = new FileReader()
          const image = new Image()
          image.onload = (imageEvent) => {
            const canvas = document.createElement('canvas');
            const context = canvas.getContext('2d');
            const originWidth = image.width
            const originHeight = image.height
            // 最大尺寸限制，可通过设置宽高来实现图片压缩程度
            let maxWidth = 1125, maxHeight = 1125
            // 目标尺寸
            let targetWidth = originWidth, targetHeight = originHeight
            // 图片尺寸超过800x800的限制
            if (originWidth > maxWidth || originHeight > maxHeight) {
              if (originWidth / originHeight > maxWidth / maxHeight) {
                // 更宽，按照宽度限定尺寸
                targetWidth = maxWidth;
                targetHeight = Math.round(maxWidth * (originHeight / originWidth));
              } else {
                targetHeight = maxHeight;
                targetWidth = Math.round(maxHeight * (originWidth / originHeight));
              }
            }
            canvas.width = targetWidth
            canvas.height = targetHeight
            context.clearRect(0, 0, targetWidth, targetHeight)
            context.drawImage(image, 0, 0, targetWidth, targetHeight)
            const dataUrl = canvas.toDataURL('image/jpeg', 0.9)
            const blobData = _this.dataURItoBlob(dataUrl, 'image/jpeg')
            resolve(blobData)
          }
          reader.onload = (e => { image.src = e.target.result; })
          reader.readAsDataURL(file)
        })
      }
      return isJpgOrPng && isLt10M
    },
    dataURItoBlob(dataURI, type) {
      var binary = atob(dataURI.split(',')[1]);
      var array = [];
      for(var i = 0; i < binary.length; i++) {
        array.push(binary.charCodeAt(i));
      }
      return new Blob([new Uint8Array(array)], {type: type});
    },
    // 返回上一页
    back() {
      this.$emit('componentsChange', 'index')
    },
    // 提交
    submit() {
      this.$refs['form'].validate(async valid => {
        if (valid) {
          await editStore(this.form).then(() => {
            this.$message.success('修改成功')
            this.back()
          })
        } else {
          return false
        }
      })
    },
    // 上传图片成功的钩子
    uploadSuccess(res) {
      this.form.store_image = res.data
    },
    // 删除上传门店图片
    removeUpload() {
      this.form.store_image = ''
    },
    // 地图模糊查询实例
    initMapFilterPlace() {
      const auto = new AMap.Autocomplete({
        input: 'tipinput'
      })
      // 构造地点查询类
      const placeSearch = new AMap.PlaceSearch({
        map: this.mapDialogObj.MAP
      })
      const geocoder = new AMap.Geocoder({
        // city: "010", //城市设为北京，默认：“全国”
        // radius: 1000 //范围，默认：500
      })
      // 注册监听，当选中某条记录时会触发
      AMap.event.addListener(auto, "select", (e) => {
        placeSearch.setCity(e.poi.adcode)
        // 关键字查询查询
        placeSearch.search(e.poi.name)
        this.mapDialogObj.address = e.poi.district + e.poi.name
        this.mapDialogObj.lat = e.poi.location.lat
        this.mapDialogObj.lon = e.poi.location.lng
        geocoder.getAddress(e.poi.location.lng + ',' + e.poi.location.lat, (status, result) => {
          if (status === 'complete' && result.regeocode) {
            this.form.province = result.regeocode.addressComponent.province
            this.form.city = result.regeocode.addressComponent.city
            this.form.district = result.regeocode.addressComponent.district
          } else {
            this.$message.error('根据经纬度查询地址失败')
          }
        })
      })
    },
    // 地图标记查询实例
    initMapMarkerPlace() {
      let marker = null
      let marker2 = null

      const geocoder = new AMap.Geocoder({
        // city: "010", //城市设为北京，默认：“全国”
        // radius: 1000 //范围，默认：500
      })

      this.mapDialogObj.MAP.on('click', (e) => {
        this.mapDialogObj.lon = e.lnglat.getLng()
        this.mapDialogObj.lat = e.lnglat.getLat()
        marker = new AMap.Marker({
          position: [this.mapDialogObj.lon, this.mapDialogObj.lat]
        })
        if (marker2) {
          this.mapDialogObj.MAP.remove(marker2)
        }
        marker2 = marker

        marker.setMap(this.mapDialogObj.MAP)
        geocoder.getAddress(e.lnglat.getLng() + ',' + e.lnglat.getLat(), (status, result) => {
          if (status === 'complete' && result.regeocode) {
            this.form.province = result.regeocode.addressComponent.province
            this.form.city = result.regeocode.addressComponent.city
            this.form.district = result.regeocode.addressComponent.district
            this.mapDialogObj.address = result.regeocode.formattedAddress
          } else {
            this.$message.error('根据经纬度查询地址失败')
          }
        })
      })
    },
    // 实例化地图
    initMap() {
      this.mapDialogObj.MAP = new AMap.Map('map', {
        resizeEnable: true,
        center: [106.630153, 26.647661],
        zoom: 13
      })
      this.initMapFilterPlace()
      this.initMapMarkerPlace()
    },
    // 地图弹窗
    showMapDialog() {
      this.mapDialogObj.visible = true
      this.$nextTick(() => {
        this.initMap()
      })
    },
    // 关闭地图弹窗
    mapDialogClose() {
      this.mapDialogObj.visible = false
      this.mapDialogObj.MAP.destroy()
      this.mapDialogObj.searchText = ''
      this.mapDialogObj.address = ''
      this.mapDialogObj.lat = ''
      this.mapDialogObj.lon = ''
    },
    // 地图选择完成
    mapChooseSubmit() {
      this.form.longitude = this.mapDialogObj.lon
      this.form.latitude = this.mapDialogObj.lat
      this.form.address = this.mapDialogObj.address
      this.mapDialogClose()
    },
    // 清空地图查询数据
    clearDialogData() {
      this.mapDialogObj.address = ''
      this.mapDialogObj.lat = ''
      this.mapDialogObj.lon = ''
    }
  },
  mounted() {
    this.form = this.storeManage
    this.fileList = [{
      name: '',
      url: this.baseUrl + this.form.store_image
    }]
  }
}
</script>


<style lang="scss" scoped>
::v-deep .el-textarea .el-input__count {
  line-height: normal;
}

.amap-wrapper {
  height: 500px;
}

.dialog-input {
  width: 300px;
  margin-bottom: 20px;
}

.dialog-ipt-group {
  margin-top: 20px;

  .el-input {
    width: 200px;
  }

  .el-input + .el-input {
    margin-left: 10px;
  }

  .full {
    display: block;
    width: 100%;
    margin-left: 0 !important;
    margin-top: 20px;
  }
}
</style>

<style>
.amap-sug-result {
  z-index: 10000
}
</style>
