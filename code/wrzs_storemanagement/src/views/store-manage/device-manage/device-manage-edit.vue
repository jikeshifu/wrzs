<template>
  <div class="device-manage-edit">
    <el-button @click="back" icon="el-icon-back">返回设备列表</el-button>
    <el-row type="flex" justify="center">
      <el-col :span="8">
        <el-form ref="form" :model="form" :rules="formRules" label-width="100px">
          <el-form-item label="当前位置" prop="device_name">
            <el-tag type="primary">{{ `${storeManage.store_name}，${roomManage.room_name}` }}</el-tag>
          </el-form-item>
          <el-form-item label="语音播报文本">
            <el-input type="textarea" placeholder="设备语音文本" maxlength="30" rows="4" show-word-limit v-model="form.voice"></el-input>
          </el-form-item>
          <el-form-item label="语音音量" prop="volume">
            <el-input-number v-model="form.volume" :min="1" :max="7"></el-input-number>
          </el-form-item>
          <el-form-item>
            <el-button @click="back">返回</el-button>
            <el-button type="primary" @click="submit">立即修改</el-button>
          </el-form-item>
        </el-form>
      </el-col>
    </el-row>
  </div>
</template>

<script>
import {mapState} from "vuex"
import {editDevice} from "@/api/store-manage/device-manage"

export default {
  name: "device-manage-edit",
  computed: {
    ...mapState({
      storeManage: state => state['store-manage/store-manage'].storeManage,
      roomManage: state => state['store-manage/room-manage'].roomManage,
      deviceManage: state => state['store-manage/device-manage'].deviceManage
    })
  },
  data() {
    return {
      form: {},
      formRules: {
        volume: [{
          required: true,
          message: '请输入语音音量'
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
          await editDevice({
            room_id: this.roomManage.room_id,
            ...this.form
          }).then(() => {
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
    this.form = this.deviceManage
  }
}
</script>

<style scoped>

</style>
