<template>
  <div class="joinin-manage-index">
    <el-button type="primary" icon="el-icon-plus" @click="addJoinin">添加加盟商账号</el-button>
    <el-button type="warning" icon="el-icon-edit" :disabled="Mix_tableData.selectionObj.length !== 1"
      @click="editJoinin(Mix_tableData.selectionObj[0])">编辑</el-button>
    <el-button type="danger" icon="el-icon-delete" :disabled="!Mix_tableData.selectionIds.length"
      @click="delJoinin(Mix_tableData.selectionIds)">批量删除</el-button>
    <el-input v-model="tableData.public" placeholder="加盟商账号/用户名" maxlength="50" clearable style="width:200px"
      @clear="searchSubmit" @keypress.native.enter="searchSubmit"></el-input>
    <el-button type="primary" icon="el-icon-search" @click="searchSubmit">查询</el-button>
    <el-table ref="table" class="mt15" :data="Mix_tableData.list" border height="calc(100vh - 225px)"
      @selection-change="$selectionChange($event, 'user_id')">
      <el-table-column type="selection"></el-table-column>
      <el-table-column prop="user_id" label="ID"></el-table-column>
      <el-table-column prop="user_name" label="用户名"></el-table-column>
      <el-table-column prop="account" label="账号"></el-table-column>
      <el-table-column prop="store_number" label="允许开店数量"></el-table-column>
      <el-table-column label="创建时间">
        <template
          slot-scope="scope">{{ $dateFormatter(+(scope.row.created_at + '000'), 'yy-mm-dd hh:mm:ss') }}</template>
      </el-table-column>
      <el-table-column label="状态">
        <template slot-scope="scope">
          <el-switch @change="changeStatus(scope.row)" v-model="scope.row.status" :active-value="1" :inactive-value="0">
          </el-switch>
        </template>
      </el-table-column>
      <el-table-column label="操作" align="center">
        <template slot-scope="scope">
          <el-dropdown>
            <span class="el-dropdown-link">
              操作<i class="el-icon-arrow-down el-icon--right"></i>
            </span>
            <el-dropdown-menu slot="dropdown">
              <el-dropdown-item>
                <el-button type="text" icon="el-icon-s-finance" size="mini" @click="checkWithdrawl(scope.row)">提现申请
                </el-button>
              </el-dropdown-item>
              <el-dropdown-item>
                <el-button type="text" icon="el-icon-s-marketing" size="mini" @click="checkProfit(scope.row)">收益明细
                </el-button>
              </el-dropdown-item>
              <el-dropdown-item>
                <el-button type="text" icon="el-icon-lock" size="mini" @click="editPwd(scope.row)">修改密码</el-button>
              </el-dropdown-item>
              <el-dropdown-item>
                <el-button type="text" icon="el-icon-edit" size="mini" @click="editJoinin(scope.row)">编辑</el-button>
              </el-dropdown-item>
              <el-dropdown-item>
                <el-button type="text" icon="el-icon-delete" size="mini" @click="delJoinin([scope.row.user_id])">删除
                </el-button>
              </el-dropdown-item>
            </el-dropdown-menu>
          </el-dropdown>
        </template>
      </el-table-column>
    </el-table>
    <Pagination :tableData="Mix_tableData" @pageChange="$pageChange" :getList="getJoininList"></Pagination>
    <el-dialog :visible.sync="pwdDialog.show" title="修改密码" width="40%" :show-close="false">
      <el-form v-if="pwdDialog.show" ref="pwdDialogForm" :model="pwdDialog.form" :rules="pwdDialog.formRules" label-width="120px">
        <el-form-item prop="password" label="新密码">
          <el-input type="password" placeholder="请输入6位以上的密码" v-model="pwdDialog.form.password" maxlength="35"></el-input>
        </el-form-item>
        <el-form-item>
          <el-button icon="el-icon-cancel" @click="hidePwdDialog">取消</el-button>
          <el-button type="primary" icon="el-icon-check" @click="submitPwdDialog">提交</el-button>
        </el-form-item>
      </el-form>
    </el-dialog>
  </div>
</template>

<script>
  import {
    getJoininList,
    delJoinin,
    changeStatus,
    editJoinPwd
  } from '@/api/joinin-manage.js'

  export default {
    name: 'joinin-manage-index',
    data() {
      return {
        tableData: {
          public: ''
        },
        // 修改密码弹窗
        pwdDialog: {
          show: false,
          form: {
            user_id: '',
            password: ''
          },
          formRules: {
            password: [{
              required: true,
              message: '请输入密码'
            }, {
              min: 6,
              message: '密码长度至少6位'
            }]
          }
        }
      }
    },
    methods: {
      // 关闭修改密码弹窗
      hidePwdDialog() {
        this.pwdDialog.show = false
        this.pwdDialog.form.password = ''
      },
      // 显示修改密码弹窗
      editPwd(data) {
        this.pwdDialog.show = true
        this.pwdDialog.form.user_id = data.user_id
      },
      // 提交修改密码
      submitPwdDialog() {
        this.$refs['pwdDialogForm'].validate(async valid => {
          if (valid) {
            await editJoinPwd(this.pwdDialog.form).then(() => {
              this.$message.success('修改成功')
              this.hidePwdDialog()
            })
          } else {
            return false
          }
        })
      },
      // 修改加盟商账号状态
      async changeStatus(item) {
        await changeStatus({
          user_id: item.user_id,
          status: item.status
        })
        this.$message.success(`${ item.status ? '已开启' : '已关闭' }`)
        this.getJoininList()
      },
      // 添加加盟
      addJoinin() {
        this.$emit('changeComponents', 'add')
      },
      // 编辑加盟
      async editJoinin(data) {
        await this.$store.dispatch('joinin-manage/setJoininData', data)
        this.$emit('changeComponents', 'edit')
      },
      // 查看提现列表
      async checkWithdrawl(data) {
        await this.$store.dispatch('joinin-manage/setJoininData', data)
        this.$emit('changeComponents', 'withdrawal')
      },
      // 查看收益
      async checkProfit(data) {
        await this.$store.dispatch('joinin-manage/setJoininData', data)
        this.$emit('changeComponents', 'profit')
      },
      // 删除加盟
      delJoinin(user_id) {
        this.$confirm('您确定要删除该加盟商吗?', '温馨提示', {
          cancelButtonText: '点错了',
          type: 'warning'
        }).then(async () => {
          await delJoinin({
            user_id
          }).then(() => {
            this.$message.success('删除成功')
            this.Mix_tableData.currentPage = 1
            this.getJoininList()
          })
        }).catch(() => {})
      },
      // 查找
      searchSubmit() {
        this.Mix_tableData.currentPage = 1
        this.getJoininList()
      },
      // 获取加盟商列表
      async getJoininList() {
        await getJoininList({
          page: this.Mix_tableData.currentPage,
          limit: this.Mix_tableData.pageSize,
          public: this.tableData.public
        }).then(data => {
          this.Mix_tableData.list = data.list
          this.Mix_tableData.total = data.count
        })
      }
    },
    mounted() {
      this.getJoininList()
    }
  }
</script>

<style lang="scss" scoped>
  .el-dropdown-link {
    cursor: pointer;
    color: #409EFF;
  }

  .el-icon-arrow-down {
    font-size: 12px;
  }
</style>
