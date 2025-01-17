<template>
  <div class="withdrawal">
    <template v-if="joininData.user_id">
      <el-button icon="el-icon-back" @click="back">返回加盟商列表</el-button>
      <el-tag>当前加盟商：{{ joininData.user_name }}</el-tag>
    </template>
    <template v-else>
      <el-select v-model="user_id" placeholder="加盟商" clearable>
        <el-option :label="item.user_name" :value="item.user_id" v-for="item in joinArr" :key="item.user_id"></el-option>
      </el-select>
      <el-button type="primary" icon="el-icon-search" @click="$submitSearch(getWithdrawalList)">筛选</el-button>
    </template>
    <el-table ref="table" class="mt15" :data="Mix_tableData.list" border height="calc(100vh - 225px)">
      <el-table-column prop="join_id" label="ID"></el-table-column>
      <el-table-column prop="joinUser.user_name" label="申请用户"></el-table-column>
      <el-table-column prop="money" label="提现金额(元)"></el-table-column>
      <el-table-column label="申请时间">
        <template
          slot-scope="scope">{{ $dateFormatter(+(scope.row.created_at + '000'), 'yy-mm-dd hh:mm:ss') }}</template>
      </el-table-column>
      <el-table-column label="状态">
        <template slot-scope="scope">
          <el-tag type="warning" v-if="scope.row.status === 0">审核中</el-tag>
          <el-tag type="success" v-if="scope.row.status === 1">已通过</el-tag>
          <el-tag type="danger" v-if="scope.row.status === 2">未通过</el-tag>
        </template>
      </el-table-column>
      <el-table-column label="操作" align="center">
        <template slot-scope="scope">
          <el-button type="primary" size="mini" v-if="scope.row.status === 0" @click="toExamine(scope.row)">去审核
          </el-button>
        </template>
      </el-table-column>
    </el-table>
    <Pagination :tableData="Mix_tableData" @pageChange="$pageChange" :getList="getWithdrawalList"></Pagination>
    <el-dialog title="提现审核" :visible.sync="dialog.show" width="30%" :show-close="false">
      <el-form ref="dialogForm" :model="dialog.form" v-if="dialog.show" :rules="dialog.formRules" label-width="120px">
        <el-form-item label="类型">
          <el-radio-group v-model="dialog.form.type">
            <el-radio :label="1">通过</el-radio>
            <el-radio :label="2">不通过</el-radio>
          </el-radio-group>
        </el-form-item>
        <el-form-item label="原因" prop="remarks" v-if="dialog.form.type === 2">
          <el-input type="textarea" rows="5" v-model="dialog.form.remarks" placeholder="未通过原因" maxlength="50" show-word-limit></el-input>
        </el-form-item>
        <el-form-item>
          <el-button @click="hideDialog">取消</el-button>
          <el-button type="primary" icon="el-icon-check" @click="submitDialog">确认提交</el-button>
        </el-form-item>
      </el-form>
    </el-dialog>
  </div>
</template>

<script>
  import {
    getJoininList,
    getWithdrawalList,
    examineSucc
  } from '@/api/joinin-manage.js'

  import {
    mapState
  } from 'vuex'

  export default {
    computed: {
      ...mapState({
        joininData: state => state['joinin-manage'].joininData
      })
    },
    async beforeDestroy() {
      await this.$store.dispatch('joinin-manage/setJoininData', {})
    },
    data() {
      return {
        user_id: '',
        joinArr: [],
        dialog: {
          show: false,
          form: {
            wthdrawal_id: '',
            type: 1,
            remarks: ''
          },
          formRules: {
            remarks: [{
              required: true,
              message: '请输入未通过原因'
            }]
          }
        }
      }
    },
    methods: {
      // 提交审核
      submitDialog() {
        this.$refs['dialogForm'].validate(async (valid) => {
          if (valid) {
            await examineSucc(this.dialog.form).then(async _ => {
              this.hideDialog()
              this.$message.success('提交成功')
              await this.getWithdrawalList()
              this.Mix_tableData.currentPage = 1
            })
          } else {
            return false
          }
        })
      },
      back() {
        this.$emit('changeComponents', 'index')
      },
      // 获取提现申请列表
      async getWithdrawalList() {
        await getWithdrawalList({
          page: this.Mix_tableData.currentPage,
          limit: this.Mix_tableData.pageSize,
          user_id: this.joininData.user_id || this.user_id
        }).then(data => {
          this.Mix_tableData.list = data.list
          this.Mix_tableData.total = data.count
        })
      },
      // 取消弹窗
      hideDialog() {
        this.dialog.form.wthdrawal_id = ''
        this.dialog.form.type = 1
        this.dialog.form.remarks = ''
        this.dialog.show = false
      },
      // 去审核
      toExamine(data) {
        this.dialog.form.wthdrawal_id = data.wthdrawal_id
        this.dialog.show = true
      },
      // 获取加盟商列表
      async getJoininList() {
        await getJoininList().then(data => {
          this.joinArr = data.list
        })
      }
    },
    mounted() {
      this.getWithdrawalList()
      if (!this.joininData.user_id) {
        this.getJoininList()
      }
    }
  }
</script>

<style>
</style>
