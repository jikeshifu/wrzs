<template>
  <div class="joinin-manage-edit">
    <el-button icon="el-icon-back" @click="back">返回加盟商列表</el-button>
    <el-row type="flex" justify="center">
      <el-col :span="8">
        <el-form ref="form" :model="form" :rules="formRules" label-width="120px">
          <el-form-item prop="account" label="加盟商账号">
            <el-input v-model="form.account" placeholder="请输入加盟商账号" maxlength="10"
              @input="form.account = form.account.replace(/[^\w]/ig, '')"></el-input>
          </el-form-item>
          <el-form-item prop="user_name" label="用户名">
            <el-input v-model.trim="form.user_name" placeholder="请输入加盟商用户名" maxlength="50"></el-input>
          </el-form-item>
          <el-form-item prop="store_number" label="开店数量">
            <el-input-number v-model="form.store_number" :min="0" :max="500000" :precision="0">
            </el-input-number>
          </el-form-item>
          <el-form-item prop="status" label="状态">
            <el-radio v-model="form.status" :label="0">关闭</el-radio>
            <el-radio v-model="form.status" :label="1">启用</el-radio>
          </el-form-item>
          <el-form-item>
            <el-button icon="el-icon-back" @click="back">返回</el-button>
            <el-button type="primary" icon="el-icon-check"
              @click="submit">确定修改</el-button>
          </el-form-item>
        </el-form>
      </el-col>
    </el-row>
  </div>
</template>

<script>
  import {
    editJoinin
  } from '@/api/joinin-manage.js'
  import { mapState } from 'vuex'

  export default {
    name: 'joinin-manage-edit',
    computed: {
      ...mapState({
        joininData: state => state['joinin-manage'].joininData
      })
    },
    data() {
      const validateAct = (rule, value, callback) => {
        if (!/^[\da-zA-Z]+$/.test(value)) {
          callback(new Error('账号仅由数字和字母组成'))
        }
        callback()
      }
      return {
        form: {},
        formRules: {
          account: [{
            required: true,
            message: '请输入加盟商账号'
          }, {
            validator: validateAct
          }, {
            min: 6,
            message: '账号至少6位'
          }],
          user_name: [{
            required: true,
            message: '请输入用户名'
          }],
          store_number: [{
            required: true,
            message: '请输入加盟商允许开店的数量'
          }]
        }
      }
    },
    methods: {
      back() {
        this.$emit('changeComponents', 'index')
      },
      submit() {
        this.$refs['form'].validate(async valid => {
          if (valid) {
            await editJoinin(this.form).then(() => {
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
      this.form = this.joininData
    }
  }
</script>

<style lang="scss" scoped>

</style>
