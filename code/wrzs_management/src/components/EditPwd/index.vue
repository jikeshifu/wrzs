<template>
  <el-dialog
    title="修改密码"
    :visible.sync="dialogVisible"
    width="30%"
    :show-close="false">
    <el-form :model="form" ref="form" :rules="formRules" label-width="120px">
      <el-form-item label="旧密码" prop="oldPass">
        <el-input type="password" v-model="form.oldPass" placeholder="请输入旧密码" maxlength="30"></el-input>
      </el-form-item>
      <el-form-item label="新密码" prop="newPass">
        <el-input ref="password" v-model="form.newPass" placeholder="请输入新密码" maxlength="30" :type="passwordType" :readonly="!passwordType"></el-input>
        <span class="show-pwd" @click="showPwd">
          <svg-icon :icon-class="passwordType === 'password' ? 'eye' : 'eye-open'"/>
        </span>
      </el-form-item>
      <el-form-item label="重复新密码" prop="newPass2">
        <el-input type="password" v-model="form.newPass2" placeholder="请输入重复新密码" maxlength="30"></el-input>
      </el-form-item>
    </el-form>
    <span slot="footer">
      <el-button @click="closeDialog">取消</el-button>
      <el-button type="primary" @click="submit" :loading="btnLoading" :disabled="btnLoading">确定修改</el-button>
    </span>
  </el-dialog>
</template>

<script>
import {editPwd} from "@/api/user"

export default {
  name: "editPwd",
  data() {
    // 验证新密码
    const validateNewPass = (rule, value, callback) => {
      if (value.length < 6) {
        callback(new Error('新密码不能至少6位'))
      } else {
        callback()
      }
    }
    // 验证重复新密码
    const validateNewPass2 = (rule, value, callback) => {
      if (value !== this.form.newPass) {
        callback(new Error('两次新密码输入不一致'))
      } else {
        callback()
      }
    }
    return {
      btnLoading: false,
      passwordType: 'password',
      dialogVisible: false,
      form: {
        oldPass: '',
        newPass: '',
        newPass2: ''
      },
      formRules: {
        oldPass: [{
          required: true,
          message: '请输入原密码'
        }],
        newPass: [{
          required: true,
          message: '请输入新密码'
        }, {
          validator: validateNewPass
        }],
        newPass2: [{
          required: true,
          message: '请输入重复新密码'
        }, {
          validator: validateNewPass2
        }]
      }
    }
  },
  methods: {
    showPwd() {
      if (this.passwordType === 'password') {
        this.passwordType = ''
      } else {
        this.passwordType = 'password'
      }
      this.$nextTick(() => {
        this.$refs.password.focus()
      })
    },
    submit() {
      this.$refs['form'].validate(async valid => {
        if (valid) {
          this.btnLoading = true
          const data = await editPwd({
            old_password: this.form.oldPass,
            password: this.form.newPass
          }).catch(() => {})
          if (data) {
            this.$confirm('修改成功，请重新登录', '温馨提示', {
              confirmButtonText: '重新登录',
              showCancelButton: false,
              type: 'success',
              showClose: false,
            }).then(async () => {
              this.dialogVisible = false
              this.callback()
            }).catch(() => {})
          }
          this.btnLoading = false
        } else {
          return false
        }
      })
    },
    // 关闭弹窗
    closeDialog() {
      this.dialogVisible = false
      this.$refs['form'].resetFields()
    }
  }
}
</script>

<style lang="scss" scoped>
$dark_gray: #889aa4;

.show-pwd {
  position: absolute;
  right: 10px;
  top: 2px;
  font-size: 16px;
  color: $dark_gray;
  cursor: pointer;
  user-select: none;
}
</style>
