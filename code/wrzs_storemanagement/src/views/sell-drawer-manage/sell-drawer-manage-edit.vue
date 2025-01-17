<template>
  <div class="sell-drawer-manage-edit">
    <el-button @click="back" icon="el-icon-back">返回售货柜列表</el-button>
    <el-row type="flex" justify="center">
      <el-col :span="8">
        <el-form ref="form" :model="form" :rules="formRules" label-width="100px">
          <el-form-item label="售货柜名称" prop="cabinet_name">
            <el-input placeholder="请输入售货柜名称" maxlength="25" v-model="form.cabinet_name"></el-input>
          </el-form-item>
          <el-form-item label="设备序列号" prop="device_sn">
            <el-input placeholder="请输入设备序列号" maxlength="25" v-model="form.device_sn"></el-input>
          </el-form-item>
          <el-form-item label="所属门店" prop="store_id">
            <el-select v-model="form.store_id" placeholder="请选择门店名称" filterable clearable @change="storeChange">
              <el-option v-for="item in storeArr" :label="item.store_name" :value="item.store_id" :key="item.store_id"></el-option>
            </el-select>
          </el-form-item>
          <el-form-item label="所属房间" prop="room_id">
            <el-select v-model="form.room_id" placeholder="请选择房间名称" filterable clearable>
              <el-option v-for="item in roomArr" :label="item.room_name" :value="item.room_id" :key="item.room_id"></el-option>
            </el-select>
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
import {getStoreList} from "@/api/store-manage/store-manage"
import {getRoomList} from "@/api/store-manage/room-manage"
import {editSellDrawer} from "@/api/sell-drawer-manage/sell-drawer-manage"
import {mapState} from 'vuex'

export default {
  name: "sell-drawer-manage-edit",
  computed: {
    ...mapState({
      sellDrawerManage: state => state['sell-drawer-manage/sell-drawer-manage'].sellDrawerManage
    })
  },
  data() {
    // 验证空
    const validateEmpty = (rule, value, callback) => {
      if (!value.trim()) {
        if (rule.field === 'cabinet_name') {
          callback(new Error('请输入售货柜名称'))
        }
        if (rule.field === 'device_sn') {
          callback(new Error('请输入设备序列号'))
        }
      }
      callback()
    }
    return {
      // 门店列表数据
      storeArr: [],
      // 房间列表数据
      roomArr: [],
      form: {},
      formRules: {
        cabinet_name: [{
          required: true,
          message: '请输入售货柜名称'
        }, {
          validator: validateEmpty
        }],
        device_sn: [{
          required: true,
          message: '请输入设备序列号'
        }, {
          validator: validateEmpty
        }],
        store_id: [{
          required: true,
          message: '请选择门店名称'
        }],
        room_id: [{
          required: true,
          message: '请输入房间名称'
        }]
      }
    }
  },
  methods: {
    // 提交
    submit() {
      this.$refs['form'].validate(async valid => {
        if (valid) {
          await editSellDrawer(this.form).then(() => {
            this.$message.success('修改成功')
            this.back()
          })
        } else {
          return false
        }
      })
    },
    // 返回
    back() {
      this.$emit('componentsChange', 'index')
    },
    // 获取门店列表
    async getStoreList() {
      await getStoreList().then(data => {
        this.storeArr = data.list
      })
    },
    // 获取房间列表
    async getRoomList() {
      await getRoomList({
        store_id: this.sellDrawerManage.store_id
      }).then((data) => {
        this.roomArr = data.list
      })
    },
    // 获取房间列表
    async storeChange(store_id) {
      this.form.room_id = ''
      await getRoomList({
        store_id
      }).then((data) => {
        this.roomArr = data.list
      })
    }
  },
  async mounted() {
    this.form = this.sellDrawerManage
    await this.getStoreList()
    await this.getRoomList()
  }
}
</script>

<style scoped>

</style>
