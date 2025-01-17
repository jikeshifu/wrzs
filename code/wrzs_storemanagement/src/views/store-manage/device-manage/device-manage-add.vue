<template>
  <div class="device-manage-add">
    <el-button @click="back" icon="el-icon-back">返回设备列表</el-button>
    <el-row type="flex" justify="center">
      <el-col :span="8">
        <el-form ref="form" :model="form" :rules="formRules" label-width="100px">
          <el-form-item label="房间信息" prop="device_name">
            <el-tag type="primary">{{ `${storeManage.store_name}，${roomManage.room_name}` }}</el-tag>
          </el-form-item>
          <el-form-item label="设备名称" prop="device_name">
            <el-input placeholder="请输入设备名称" maxlength="25" v-model="form.device_name"></el-input>
          </el-form-item>
          <el-form-item label="设备序列号" prop="device_sn">
            <el-input placeholder="请输入设备序列号" maxlength="30" v-model="form.device_sn"></el-input>
          </el-form-item>
          <el-form-item label="设备类型" prop="device_type">
            <el-select v-model="form.device_type" placeholder="请选择设备类型">
              <el-option label="门锁/门禁(W89/W76)" :value="1"></el-option>
              <el-option label="大门/公共门(W89/W76)" :value="2"></el-option>
              <el-option label="空开/断路器(W71/W72)" :value="3"></el-option>
              <el-option label="云喇叭(W70)" :value="4"></el-option>
            </el-select>
          </el-form-item>
          <!-- <el-form-item label="网关SN" v-if="form.device_type !== 3">
            <el-input placeholder="选填" maxlength="30" v-model="form.gw_sn"></el-input>
          </el-form-item> -->
          <el-form-item label="语音播报文本" v-if="form.device_type == 1">
            <el-input type="textarea" placeholder="设备语音文本" maxlength="30" rows="4" show-word-limit v-model="form.voice"></el-input>
          </el-form-item>
          <el-form-item label="语音音量" prop="volume" v-if="form.device_type == 1">
            <el-input-number v-model="form.volume" :min="1" :max="10"></el-input-number>
          </el-form-item>
          <el-form-item label="语音播报文本" v-if="form.device_type == 4">
            <el-input type="textarea" placeholder="设备语音文本" maxlength="30" rows="4" show-word-limit v-model="form.voice"></el-input>
          </el-form-item>
          <el-form-item label="语音音量" prop="volume" v-if="form.device_type == 4">
            <el-input-number v-model="form.volume" :min="1" :max="10"></el-input-number>
          </el-form-item>
          <el-form-item>
            <el-button @click="back">返回</el-button>
            <el-button type="primary" @click="submit">立即添加</el-button>
          </el-form-item>
        </el-form>
      </el-col>
    </el-row>
  </div>
</template>

<script>
import {mapState} from "vuex"
import {addDevice} from "@/api/store-manage/device-manage"

export default {
  name: "device-manage-add",
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
        if (rule.field === 'device_name') {
          callback(new Error('请输入设备名称'))
        }
        if (rule.field === 'device_sn') {
          callback(new Error('请输入设备序列号'))
        }
      }
      callback()
    }
    return {
      form: {
        device_name: '',
        device_sn: '',
        device_type: 1,
        voice: '',
        gw_sn: '',
        volume: ''
      },
      formRules: {
        volume: [{
          required: true,
          message: '请输入语音音量'
        }],
        device_name: [{
          required: true,
          message: '请输入设备名称'
        }, {
          validator: validateEmpty
        }],
        device_sn: [{
          required: true,
          message: '请输入设备序列号'
        }, {
          validator: validateEmpty
        }]
      }
    }
  },
  methods: {
    // 返回上一页
    back() {
      this.$emit('componentsChange', 'index')
    },
    // 提交
    submit() {
      this.$refs['form'].validate(async valid => {
        if (valid) {
          await addDevice({
            room_id: this.roomManage.room_id,
            ...this.form
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

<style scoped>

</style>
