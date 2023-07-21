<template>
  <div class="room-manage-edit">
    <el-row type="flex" justify="center">
      <el-col :span="24">
        <el-form ref="form" :model="form" :rules="formRules" label-width="120px">
          <el-form-item label="当前门店名称">
            <el-tag type="primary">{{ storeManage.store_name }}</el-tag>
          </el-form-item>
          <el-form-item label="房间名称" prop="room_name">
            <el-input placeholder="请输入房间名称" v-model="form.room_name" maxlength="20"></el-input>
          </el-form-item>
          <el-form-item label="房间主图" prop="room_image">
            <el-upload :action="uploadURL" list-type="picture-card" :limit="1" :file-list="fileList"
                       :before-upload="beforeUpload"   :on-remove="removeUpload" :on-success="uploadSuccess">
              <i class="el-icon-plus"></i>
            </el-upload>
            <el-tag type="warning">仅限上传一张</el-tag>
          </el-form-item>
          <el-form-item label="房间单价" prop="room_price">
            <el-input-number v-model="form.room_price" :precision="2" :step="1" :min="0" :max="100000">
            </el-input-number>
            <el-tag type="primary">元</el-tag>
          </el-form-item>
          <el-form-item label="房间押金" prop="room_deposit">
            <el-input-number v-model="form.room_deposit" :precision="2" :step="1" :min="0" :max="100000">
            </el-input-number>
            <el-tag type="primary">元</el-tag>
          </el-form-item>
          <el-form-item label="房间面积" prop="room_size">
            <el-input-number v-model="form.room_size" :precision="2" :step="1" :min="1" :max="10000"
              controls-position="right"></el-input-number>
            <el-tag type="primary">平方米</el-tag>
          </el-form-item>
          <el-form-item label="可容纳人数" prop="room_people">
            <el-input-number v-model="form.room_people" :step="1" :min="1" :max="10000"></el-input-number>
            <el-tag type="primary">人</el-tag>
          </el-form-item>
          <el-form-item label="展示排序" prop="sort">
            <el-input v-model="form.sort" maxlength="5" @input="form.sort = Number(form.sort.replace(/[^\d]/g,''))">
            </el-input>
          </el-form-item>
          <el-form-item label="预定类型" prop="room_type">
            <el-radio-group v-model="form.room_type">
              <el-radio :label="1">按小时</el-radio>
              <el-radio :label="2">按天数</el-radio>
            </el-radio-group>
          </el-form-item>
          <el-form-item label="起订时长" prop="starting_time" v-if="form.room_type !== 3">
            <el-input-number v-model="form.starting_time" :precision="0" :min="1" :max="10000"></el-input-number>
            <el-tag type="primary" v-if="form.room_type === 1">小时</el-tag>
            <el-tag type="primary" v-if="form.room_type === 2">天</el-tag>
          </el-form-item>
          <template v-if="form.room_type === 2">
            <el-form-item label="开始时间" prop="start_time_slot">
              <el-time-select v-model="form.start_time_slot" :picker-options="{
                  start: '00:00',
                  step: '00:30',
                  end: '24:00'
                }" placeholder="开始时间">
              </el-time-select>
            </el-form-item>
            <el-form-item label="结束时间" prop="end_time_slot">
              <el-time-select v-model="form.end_time_slot" :picker-options="{
                  start: '00:00',
                  step: '00:30',
                  end: '24:00'
                }" placeholder="结束时间">
              </el-time-select>
            </el-form-item>
          </template>
          <el-form-item label="自动通电" prop="electric_status">
            <el-radio v-model="form.electric_status" :label="0">否</el-radio>
            <el-radio v-model="form.electric_status" :label="1">是</el-radio>
          </el-form-item>
          <el-form-item label="自动关电" prop="electric_stop_status">
            <el-radio v-model="form.electric_stop_status" :label="0">否</el-radio>
            <el-radio v-model="form.electric_stop_status" :label="1">是</el-radio>
          </el-form-item>
          <!-- <el-form-item label="可否结算" prop="settlement_status">
            <el-radio v-model="form.settlement_status" :label="0">否</el-radio>
            <el-radio v-model="form.settlement_status" :label="1">是</el-radio>
          </el-form-item> -->
          <el-form-item label="大门" prop="dm_status">
            <el-radio v-model="form.dm_status" :label="0">无</el-radio>
            <el-radio v-model="form.dm_status" :label="1">有</el-radio>
          </el-form-item>
          <el-form-item label="已售" prop="room_sold">
            <el-input-number v-model="form.room_sold" :precision="0" :step="1" :min="0" :max="9999999"></el-input-number>
          </el-form-item>
          <!-- <el-form-item prop="room_type">
            <el-tooltip slot="label" effect="dark" content="不同客户是否可在同一时间预定该房间" placement="top-start">
              <div>重复预定 <span class="el-icon-question"></span></div>
            </el-tooltip>
            <el-radio v-model="form.reserve_status" :label="0">不可重复</el-radio>
            <el-radio v-model="form.reserve_status" :label="1">可重复</el-radio>
          </el-form-item> -->
          <el-form-item label="房间标签" prop="room_label">
            <el-input v-model="form.room_label" maxlength="10" placeholder="例如:舞蹈房、健身房等"></el-input>
          </el-form-item>
          <el-form-item label="房间展示图">
            <el-upload :action="uploadURL" list-type="picture-card" multiple :limit="5" :file-list="batchFileList"
                       :before-upload="beforeUpload"   :on-remove="removeBatchUpload" :on-success="batchUploadSuccess">
              <i class="el-icon-plus"></i>
            </el-upload>
            <el-tag type="warning">最多上传5张，建议尺寸 750 x 375</el-tag>
          </el-form-item>
          <!-- <el-form-item label="营业开始时间" prop="work_time_start">
            <el-time-select placeholder="起始时间" v-model="form.work_time_start" :picker-options="{
                start: '00:00',
                step: '00:30',
                end: '24:00',
                maxTime: form.work_time_end
              }">
            </el-time-select>
          </el-form-item>
          <el-form-item label="营业结束时间" prop="work_time_end">
            <el-time-select placeholder="结束时间" v-model="form.work_time_end" :picker-options="{
                start: '00:00',
                step: '00:30',
                end: '24:00',
                minTime: form.work_time_start
              }">
            </el-time-select>
          </el-form-item> -->
          <!-- <el-form-item label="工作日" prop="work_week">
            <el-checkbox-group v-model="form.work_week">
              <el-checkbox label="1">星期一</el-checkbox>
              <el-checkbox label="2">星期二</el-checkbox>
              <el-checkbox label="3">星期三</el-checkbox>
              <el-checkbox label="4">星期四</el-checkbox>
              <el-checkbox label="5">星期五</el-checkbox>
              <el-checkbox label="6">星期六</el-checkbox>
              <el-checkbox label="7">星期日</el-checkbox>
            </el-checkbox-group>
          </el-form-item> -->
          <!-- <el-form-item label="用户可取消房间" prop="cancel_status">
            <el-radio v-model="form.cancel_status" :label="0">否</el-radio>
            <el-radio v-model="form.cancel_status" :label="1">是</el-radio>
          </el-form-item> -->
          <el-form-item label="房间介绍" prop="room_about">
            <tinymce ref="tinymce" v-model="form.room_about" :height="300" />
          </el-form-item>
          <el-form-item>
            <el-button @click="back">返回房间列表</el-button>
            <el-button type="primary" @click="submit">立即修改</el-button>
          </el-form-item>
        </el-form>
      </el-col>
    </el-row>
  </div>
</template>

<script>
  import {
    mapState
  } from 'vuex'
  import Tinymce from '@/components/Tinymce'
  import {
    editRoom
  } from "@/api/store-manage/room-manage"

  export default {
    name: "room-manage-edit",
    components: {
      Tinymce
    },
    computed: {
      ...mapState({
        storeManage: state => state['store-manage/store-manage'].storeManage,
        roomManage: state => state['store-manage/room-manage'].roomManage
      })
    },
    data() {
      // 验证空
      const validateEmpty = (rule, value, callback) => {
        if (!value.trim()) {
          if (rule.field === 'room_name') {
            callback(new Error('请输入房间名称'))
          }
          if (rule.field === 'room_label') {
            callback(new Error('请输入房间标签'))
          }
        }
        callback()
      }
      return {
        form: {
          work_week: []
        },
        fileList: [],
        batchFileList: [],
        formRules: {
          start_time_slot: [{
            required: true,
            message: '请选择营业开始时间'
          }],
          end_time_slot: [{
            required: true,
            message: '请选择营业结束时间'
          }],
          work_week: [{
            required: true,
            message: '请选择工作日'
          }],
          room_label: [{
            required: true,
            message: '请输入房间标签'
          }, {
            validator: validateEmpty
          }],
          starting_time: [{
            required: true,
            message: '请输入起订时长'
          }],
          room_name: [{
            required: true,
            message: '请输入房间名称'
          }, {
            validator: validateEmpty
          }],
          room_price: [{
            required: true,
            message: '请输入房间单价'
          }],
          room_deposit: [{
            required: true,
            message: '请输入房间押金'
          }],
          room_size: [{
            required: true,
            message: '请输入房间面积'
          }],
          room_people: [{
            required: true,
            message: '请输入房间容纳人数'
          }],
          room_sold: [{
            required: true,
            message: '请输入已售数量'
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
        this.form.room_image = res.data
      },
      // 删除上传房间主图
      removeUpload() {
        this.form.room_image = ''
      },
      // 删除批量上传的图片
      removeBatchUpload(res) {
        let idx = 0
        if (res.response) {
          idx = this.form.room_images.indexOf(res.response.data)
        } else {
          idx = this.form.room_images.indexOf(res.url.split(this.baseUrl)[1])
        }
        this.form.room_images.splice(idx, 1)
      },
      // 批量上传成功的钩子
      batchUploadSuccess(res) {
        this.form.room_images.push(res.data)
      },
      // 返回房间列表
      back() {
        this.$emit('componentsChange', 'index')
      },
      // 提交
      submit() {
        this.$refs['form'].validate(async valid => {
          if (valid) {
            await editRoom({
              ...this.form,
              store_id: this.storeManage.store_id
            }).then(() => {
              this.$message.success('修改成功')
              this.back()
            })
          } else {
            return false
          }
        })
      },
      // 初始化图片
      initFileList() {
        this.fileList = [{
          name: '',
          url: this.baseUrl + this.form.room_image
        }]
        this.form.room_images.forEach(item => {
          this.batchFileList.push({
            name: '',
            url: this.baseUrl + item
          })
        })
      }
    },
    mounted() {
      this.form = this.roomManage
      this.initFileList()
    }
  }
</script>

<style scoped>

</style>
