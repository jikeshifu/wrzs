<template>
  <div class="service-manage-add">
    <el-row type="flex" justify="center">
      <el-col :span="24">
        <el-form ref="form" :model="form" :rules="formRules" label-width="120px">
          <el-form-item label="当前位置">
            <el-tag type="primary">{{ storeManage.store_name }}，{{ roomManage.room_name }}</el-tag>
          </el-form-item>
          <el-form-item label="标题" prop="service_title">
            <el-input placeholder="请输入服务标题" v-model.trim="form.service_title" maxlength="20"></el-input>
          </el-form-item>
          <el-form-item label="房间主图" prop="service_image">
            <el-upload :action="uploadURL" list-type="picture-card" :limit="1" :on-remove="removeUpload"
                       :before-upload="beforeUpload"  :on-success="uploadSuccess">
              <i class="el-icon-plus"></i>
            </el-upload>
            <el-tag type="warning">仅限上传一张</el-tag>
          </el-form-item>
          <el-form-item label="介绍内容" prop="service_content">
            <el-input type="textarea" rows="5" :show-word-limit="true" placeholder="请输入服务介绍内容"
              v-model.trim="form.service_content" maxlength="100"></el-input>
          </el-form-item>
          <el-form-item label="通知人手机" prop="sms_mobile">
            <el-input placeholder="请输入需通知的人的手机号" @input="form.sms_mobile = form.sms_mobile.replace(/[^\d]/g,'')" v-model="form.sms_mobile" maxlength="11"></el-input>
          </el-form-item>
          <el-form-item label="通知开关" prop="sms_status">
            <el-radio v-model="form.sms_status" :label="1">开</el-radio>
            <el-radio v-model="form.sms_status" :label="2">关</el-radio>
          </el-form-item>
          <el-form-item label="服务费用" prop="service_price">
            <el-input-number v-model="form.service_price" :min="0" :max="1000000"></el-input-number>
          </el-form-item>
          <el-form-item>
            <el-button @click="back">返回房间列表</el-button>
            <el-button type="primary" @click="submit">立即添加</el-button>
          </el-form-item>
        </el-form>
      </el-col>
    </el-row>
  </div>
</template>

<script>
  import {
    addService
  } from '@/api/store-manage/service-manage.js'
  import {
    mapState
  } from 'vuex'

  export default {
    name: 'service-manage-add',
    computed: {
      ...mapState({
        storeManage: state => state['store-manage/store-manage'].storeManage,
        roomManage: state => state['store-manage/room-manage'].roomManage
      })
    },
    data() {
      // 验证手机号
      const validateMobile = (rule, value, callback) => {
        if (!/^1[3456789]\d{9}$/.test(value)) {
          callback(new Error('请输入11位正确的手机号'))
        }
        callback()
      }
      return {
        form: {
          service_title: '',
          service_image: '',
          service_content: '',
          sms_mobile: '',
          sms_status: 1,
          service_price: ''
        },
        formRules: {
          service_title: [{
            required: true,
            message: '请输入服务标题'
          }],
          service_image: [{
            required: true,
            message: '请上传服务主图'
          }],
          service_content: [{
            required: true,
            message: '请输入服务介绍内容'
          }],
          sms_mobile: [{
            required: true,
            message: '请输入需通知的人的手机号'
          }, {
            validator: validateMobile
          }],
          service_price: [{
            required: true,
            message: '请输入服务费用'
          }]
        }
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

      // 上传图片成功的钩子
      uploadSuccess(res) {
        this.form.service_image = res.data
      },
      // 删除上传服务主图
      removeUpload() {
        this.form.service_image = ''
      },
      // 返回服务列表
      back() {
        this.$emit('componentsChange', 'index')
      },
      // 提交
      submit() {
        this.$refs['form'].validate(async valid => {
          if (valid) {
            await addService({
              ...this.form,
              room_id: this.roomManage.room_id
            }).then(() => {
							this.$message.success('添加成功')
							this.back()
						})
          } else {
            return false
          }
        })
      }
    }
  }
</script>

<style lang="scss" scoped>

</style>
