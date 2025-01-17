<template>
  <div class="region-manage-edit">
    <el-row>
      <el-col :span="12">
        <el-form ref="form" :model="form" :rules="formRules" label-width="120px">
          <el-form-item prop="city_name" label="区域名称">
            <el-input v-model.trim="form.city_name" placeholder="请输入区域名称，如北京市、贵阳市等" maxlength="20" style="width:300px;"></el-input>
          </el-form-item>
          <el-form-item prop="longitude" label="经度">
            <el-input v-model="form.longitude" placeholder="经度" readonly disabled style="width:300px;"></el-input>
          </el-form-item>
          <el-form-item prop="latitude" label="纬度">
            <el-input v-model="form.latitude" placeholder="纬度" readonly disabled style="width:300px;"></el-input>
          </el-form-item>
          <el-form-item>
            <el-button type="default" icon="el-icon-back" @click="back">返回</el-button>
            <el-button type="primary" icon="el-icon-check" @click="submitRegion">确定修改</el-button>
          </el-form-item>
        </el-form>
      </el-col>
      <el-col :span="12">
        <el-input id="tipinput" v-model="searchText" placeholder="请输入查询地址" clearable></el-input>
        <div class="mt15" id="map" style="width:100%;height:500px;"></div>
      </el-col>
    </el-row>
  </div>
</template>

<script>
  import { mapState } from 'vuex'
  import { editRegion } from '@/api/region-manage.js'
  export default {
    name: 'region-manage-edit',
    computed: {
      ...mapState({
        regionData: state => state['region-manage'].regionData
      })
    },
    data() {
      return {
        searchText: '',
        form: {},
        formRules: {
          city_name: [{
            required: true,
            message: '请输入区域名称'
          }],
          longitude: [{
            required: true,
            message: '请点击地图选择坐标'
          }],
          latitude: [{
            required: true,
            message: '请点击地图选择坐标'
          }]
        },
        MAP: null
      }
    },
    methods: {
      back() {
        this.$emit('changeComponents', 'index')
      },
      // 地图模糊查询实例
      initMapFilterPlace() {
        const auto = new AMap.Autocomplete({
          input: 'tipinput'
        })
        // 构造地点查询类
        const placeSearch = new AMap.PlaceSearch({
          map: this.MAP
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
          // this.mapDialogObj.address = e.poi.district + e.poi.name
          this.form.latitude = e.poi.location.lat
          this.form.longitude = e.poi.location.lng
          geocoder.getAddress(e.poi.location.lng + ',' + e.poi.location.lat, (status, result) => {
            if (status === 'complete' && result.regeocode) {
              // this.form.province = result.regeocode.addressComponent.province
              this.form.city_name = result.regeocode.addressComponent.city
              // this.form.district = result.regeocode.addressComponent.district
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

        this.MAP.on('click', (e) => {
          this.form.longitude = e.lnglat.getLng()
          this.form.latitude = e.lnglat.getLat()
          marker = new AMap.Marker({
            position: [this.form.longitude, this.form.latitude]
          })
          if (marker2) {
            this.MAP.remove(marker2)
          }
          marker2 = marker

          marker.setMap(this.MAP)
          geocoder.getAddress(e.lnglat.getLng() + ',' + e.lnglat.getLat(), (status, result) => {
            if (status === 'complete' && result.regeocode) {
              // this.form.province = result.regeocode.addressComponent.province
              this.form.city_name = result.regeocode.addressComponent.city
              // this.form.district = result.regeocode.addressComponent.district
              // this.mapDialogObj.address = result.regeocode.formattedAddress
            } else {
              this.$message.error('根据经纬度查询地址失败')
            }
          })
        })
      },
      // 实例化地图
      initMap() {
        this.MAP = new AMap.Map('map', {
          resizeEnable: true,
          center: [106.630153, 26.647661],
          zoom: 13
        })
        this.initMapFilterPlace()
        this.initMapMarkerPlace()
      },
      // 提交
      submitRegion() {
        this.$refs['form'].validate(async valid => {
          if (valid) {
            await editRegion(this.form).then(() => {
              this.$message.success('修改成功')
              this.back()
            })
          } else {
            return false
          }
        })
      }
    },
    mounted() {
      this.initMap()
      this.form = this.regionData
    }
  }
</script>

<style lang="scss">

</style>
