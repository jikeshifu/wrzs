<template>
  <div class="user-manage-index">
    <el-input v-model="tableData.public" clearable placeholder="用户昵称、手机号" style="width:200px" @clear="searchSubmit"
              @keypress.native.enter="searchSubmit"></el-input>
    <el-button icon="el-icon-search" type="primary" @click="searchSubmit">查询</el-button>
    <el-table :data="Mix_tableData.list" border class="mt15" height="calc(100vh - 225px)">
      <el-table-column align="center" label="编号" type="index" width="80"></el-table-column>
      <el-table-column label="用户头像" width="120" align="center">
        <template slot-scope="scope">
          <el-avatar :size="60" :src="scope.row.avatar_url"></el-avatar>
        </template>
      </el-table-column>
      <el-table-column label="用户昵称" prop="nick_name"></el-table-column>
      <!-- <el-table-column label="性别">
        <template slot-scope="scope">
          <el-tag v-if="scope.row.gender === 1" type="primary">男</el-tag>
          <el-tag v-if="scope.row.gender === 2" type="danger">女</el-tag>
        </template>
      </el-table-column> -->
      <el-table-column label="联系方式" prop="mobile"></el-table-column>
      <!-- <el-table-column label="地区" prop="province"></el-table-column> -->
      <el-table-column label="用户余额" prop="memberWallet.money"></el-table-column>
      <el-table-column label="积分" prop="memberWallet.integral"></el-table-column>
      <el-table-column align="center" label="操作" type="action">
        <template slot-scope="scope">
          <el-button type="text" @click="showRechargeDialog(scope.row)">充值</el-button>
        </template>
      </el-table-column>
    </el-table>
    <Pagination :tableData="Mix_tableData" @pageChange="$pageChange" :getList="getUserList"></Pagination>
    <el-dialog
      :visible.sync="rechargeFormDialog.show"
      title="用户充值"
      width="50%">
      <el-form ref="rechargeFormDialog" :model="rechargeFormDialog.form" :rules="rechargeFormDialog.formRules" label-width="100px">
        <el-form-item label="用户昵称">{{ rechargeFormDialog.userInfo.nick_name }}</el-form-item>
        <el-form-item label="用户头像">
          <el-image style="width:200px;height:200px" :src="rechargeFormDialog.userInfo.avatar_url" fit="cover"></el-image>
        </el-form-item>
        <el-form-item label="联系方式">{{ rechargeFormDialog.userInfo.mobile }}</el-form-item>
        <el-form-item label="当前余额">{{ rechargeFormDialog.userInfo.memberWallet.money }}</el-form-item>
        <el-form-item label="充值金额" prop="money">
          <el-input-number v-model="rechargeFormDialog.form.money" :min="0.01" :max="5000000" :precision="2"></el-input-number>
        </el-form-item>
      </el-form>
      <span slot="footer" class="dialog-footer">
        <el-button @click="hideRechargeFormDialog">取消</el-button>
        <el-button type="primary" @click="submitRechargeFormDialog" :loading="rechargeFormDialog.form.btnLoading" :disabled="rechargeFormDialog.form.btnLoading">确定</el-button>
      </span>
    </el-dialog>
  </div>
</template>

<script>
import {getUserList, rechargeAPI} from "@/api/user-manage"

export default {
  name: "user-manage-index",
  data() {
    return {
      tableData: {
        public: ''
      },
      // 充值金额弹窗
      rechargeFormDialog: {
        show: false,
        userInfo: {
          memberWallet: {
            money: ''
          }
        },
        form: {
          money: 0.01,
          btnLoading: false
        },
        formRules: {
          money: [{
            required: true,
            message: '请输入充值金额'
          }]
        }
      }
    }
  },
  methods: {
    // 查询
    searchSubmit() {
      this.Mix_tableData.currentPage = 1
      this.getUserList()
    },
    // 获取用户列表
    async getUserList() {
      await getUserList({
        page: this.Mix_tableData.currentPage,
        limit: this.Mix_tableData.pageSize,
        public: this.tableData.public
      }).then(data => {
        this.Mix_tableData.list = data.list
        this.Mix_tableData.total = data.count
      })
    },
    // 显示弹窗
    showRechargeDialog(data) {
      this.rechargeFormDialog.show = true
      this.rechargeFormDialog.userInfo = data
    },
    hideRechargeFormDialog() {
      this.rechargeFormDialog.show = false
      this.$refs['rechargeFormDialog'].resetFields()
    },
    // 提交
    submitRechargeFormDialog() {
      this.$refs['rechargeFormDialog'].validate(async valid => {
        if (valid) {
          this.rechargeFormDialog.form.btnLoading = true
          await rechargeAPI({
            price: this.rechargeFormDialog.form.money,
            member_id: this.rechargeFormDialog.userInfo.member_id
          }).then(() => {
            this.$message.success('充值成功')
            this.$refs['rechargeFormDialog'].resetFields()
            this.rechargeFormDialog.form.btnLoading = false
            this.rechargeFormDialog.show = false
            this.getUserList()
          }).catch(() => {
            this.rechargeFormDialog.form.btnLoading = false
          })
        } else {
          return false
        }
      })
    }
  },
  mounted() {
    this.getUserList()
  }
}
</script>

<style scoped>

</style>
