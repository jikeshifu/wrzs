<template>
  <div class="admin-store-manage-add">
    <el-button @click="back" icon="el-icon-back">返回门店管理员列表</el-button>
    <el-row type="flex" justify="center">
      <el-col :span="8">
        <el-form ref="form" :model="form" :rules="formRules" label-width="100px">
          <el-form-item label="姓名" prop="user_name">
            <el-input placeholder="请输入姓名" maxlength="25" v-model="form.user_name"></el-input>
          </el-form-item>
          <el-form-item label="手机号" prop="mobile">
            <el-input placeholder="请输入手机号" maxlength="11" v-model="form.mobile"></el-input>
          </el-form-item>
          <el-form-item label="密码" prop="pw">
            <el-input type="password" placeholder="请输入密码" maxlength="30" v-model="form.pw"></el-input>
          </el-form-item>
          <el-form-item label="状态" prop="status">
            <el-radio v-model="form.status" :label="0">禁用</el-radio>
            <el-radio v-model="form.status" :label="1">启用</el-radio>
          </el-form-item>
          <el-form-item label="级别" prop="level">
            <el-radio v-model="form.level" :label="1">普通管理</el-radio>
            <el-radio v-model="form.level" :label="0">超级管理</el-radio>
          </el-form-item>
          <el-form-item label="短信通知" prop="sms_status">
            <el-radio v-model="form.sms_status" :label="0">不通知</el-radio>
            <el-radio v-model="form.sms_status" :label="1">通知</el-radio>
          </el-form-item>
          <el-form-item label="选择门店" prop="store_id_arr">
            <el-select style="width:100%" v-model="form.store_id_arr" multiple placeholder="请选择要管理的门店" filterable>
              <el-option
                v-for="item in storeList"
                :key="item.store_id"
                :label="item.store_name"
                :value="item.store_id">
              </el-option>
            </el-select>
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
import {addStoreAdmin} from "@/api/system-manage/store-admin-manage"
import {getStoreList} from "@/api/store-manage/store-manage"

export default {
  name: "admin-store-manage-add",
  data() {
    // 验证空
    const validateEmpty = (rule, value, callback) => {
      if (!value.trim()) {
        if (rule.field === 'user_name') {
          callback(new Error('请输入姓名'))
        }
      }
      callback()
    }
    // 验证手机号
    const validateMobile = (rule, value, callback) => {
      if (!/^1[3456789]\d{9}$/.test(value)) {
        callback(new Error('请输入11位正确的手机号'))
      }
      callback()
    }
    return {
      // 门店列表
      storeList: [],
      form: {
        user_name: '',
        mobile: '',
        pw: '',
        level: 1,
        status: 1,
        sms_status: 0,
        store_id_arr: []
      },
      formRules: {
        user_name: [{
          required: true,
          message: '请输入姓名'
        }, {
          validator: validateEmpty
        }],
        mobile: [{
          required: true,
          message: '请输入手机号'
        }, {
          validator: validateMobile
        }],
        pw: [{
          required: true,
          message: '请输入密码'
        }, {
          min: 6,
          message: '密码至少输入6位'
        }],
        store_id_arr: [{
          required: true,
          message: '请选择门店'
        }]
      }
    }
  },
  methods: {
    back() {
      this.$emit('componentsChange', 'index')
    },
    // 添加门店管理员
    submit() {
      this.$refs['form'].validate(async valid => {
        if (valid) {
          await addStoreAdmin(this.form).then(() => {
            this.$message.success('添加成功')
            this.back()
          })
        } else {
          return false
        }
      })
    },
    // 获取门店列表
    async getStoreList() {
      await getStoreList().then(data => {
        this.storeList = data.list
      })
    }
  },
  mounted() {
    this.getStoreList()
  }
}
</script>

<style scoped>

</style>
