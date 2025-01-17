<template>
  <div class="login-container">
    <div class="login-card">
      <vue-particles :clickEffect="false"></vue-particles>
      <div class="form-contain">
        <el-form ref="loginForm" :model="loginForm" :rules="loginRules" class="login-form">
          <div class="title-container">
            <p>无人值守门店管理系统</p>
            <el-image :src="require('@/assets/login_images/logo.png')" fit="cover" alt=""></el-image>
          </div>
          <el-form-item prop="account">
            <span class="svg-container">
              <svg-icon icon-class="user"/>
            </span>
            <el-input
              ref="account"
              v-model="loginForm.account"
              placeholder="请输入账号"
              name="account"
              type="text"
              tabindex="1"
              auto-complete="on"
            />
          </el-form-item>
          <el-form-item prop="password">
            <span class="svg-container">
              <svg-icon icon-class="password"/>
            </span>
            <el-input
              :key="passwordType"
              ref="password"
              v-model="loginForm.password"
              :type="passwordType"
              placeholder="请输入密码"
              name="password"
              tabindex="2"
              auto-complete="on"
              @keyup.enter.native="handleLogin"
            />
            <span class="show-pwd" @click="showPwd">
              <svg-icon :icon-class="passwordType === 'password' ? 'eye' : 'eye-open'"/>
            </span>
          </el-form-item>
          <el-button type="primary" style="width:100%" @click.native.prevent="handleLogin">登录
          </el-button>
        </el-form>
      </div>
      <footer>CopyRight © 2021-2030 智云共享</footer>
    </div>
  </div>
</template>

<script>
export default {
  name: 'login',
  beforeDestroy() {
    // 销毁 particlesJS
    if (pJSDom && pJSDom.length > 0) {
      pJSDom.forEach(pJSDomItem => {
        pJSDomItem.pJS.fn.vendors.destroypJS()
      })
    }
  },
  data() {
    const validateUsername = (rule, value, callback) => {
      if (!value.trim()) {
        callback(new Error('请输入正确的用户名'))
      } else {
        callback()
      }
    }
    const validatePassword = (rule, value, callback) => {
      if (value.length < 6) {
        callback(new Error('密码不能少于6位'))
      } else {
        callback()
      }
    }
    return {
      loginForm: {
        account: '',
        password: ''
      },
      loginRules: {
        account: [{required: true, message: '请输入用户名'}, {validator: validateUsername}],
        password: [{required: true, message: '请输入密码'}, {validator: validatePassword}]
      },
      passwordType: 'password'
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
    handleLogin() {
      this.$refs.loginForm.validate(valid => {
        if (valid) {
          this.$store.dispatch('user/login', this.loginForm).then(() => {
            this.$router.push({path: '/'})
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
.login-card {
  position: absolute;
  z-index: 1;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-image: url("../../assets/login_images/bg.jpg");
  background-size: cover;
  background-position: center;

  .form-contain {
    position: absolute;
    z-index: 1;
    top: 20%;
    left: 50%;
    transform: translate3d(-50%, -20%, 0);

    .login-form {
      background-color: #fff;
      width: 350px;
      margin-left: auto;
      margin-right: auto;
      padding: 30px;
      border-radius: 6px;

      .title-container {
        text-align: center;
        margin-bottom: 20px;
      }

      .svg-container {
        position: absolute;
        z-index: 1;
        left: 20px;
      }

      ::v-deep .el-input__inner {
        padding-left: 50px;
      }

      .show-pwd {
        position: absolute;
        z-index: 1;
        right: 20px;
      }
    }
  }
}

footer {
  position: absolute;
  z-index: 1;
  bottom: 10%;
  left: 50%;
  color: #fff;
  transform: translateX(-50%);
}
</style>
