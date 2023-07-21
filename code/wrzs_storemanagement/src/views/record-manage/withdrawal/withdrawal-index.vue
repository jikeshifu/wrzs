<template>
  <div class="withdrawal-index">
    <el-date-picker v-model="dateRangeValue" type="daterange" range-separator="至" start-placeholder="开始日期"
      end-placeholder="结束日期" :picker-options="pickerOptions" @change="datePickerChange">
    </el-date-picker>
    <el-button type="primary" icon="el-icon-search" @click="submitSearch">筛选</el-button>
    <el-table ref="table" class="mt15" :data="Mix_tableData.list" border height="calc(100vh - 225px)">
      <el-table-column prop="withdrawal_id" label="ID"></el-table-column>
      <el-table-column prop="money" label="提现金额(元)"></el-table-column>
      <el-table-column label="提现时间">
        <template
          slot-scope="scope">{{ $dateFormatter(+(scope.row.created_at + '000'), 'yy-mm-dd hh:mm:ss') }}</template>
      </el-table-column>
      <el-table-column prop="status" label="状态">
        <template slot-scope="scope">
          <el-tag type="warnig" v-if="scope.row.status === 0">审核中</el-tag>
          <el-tag type="success" v-if="scope.row.status === 1">已提现</el-tag>
          <el-tag type="danger" v-if="scope.row.status === 0">提现失败</el-tag>
        </template>
      </el-table-column>
    </el-table>
    <Pagination :tableData="Mix_tableData" @pageChange="$pageChange" :getList="getWithdrawalList"></Pagination>
  </div>
</template>

<script>
  import {
    getWithdrawalList
  } from '@/api/record-manage/withdrawal.js'

  export default {
    name: 'withdrawal-index',
    data() {
      return {
        pickerOptions: {
          disabledDate(time) {
            return time.getTime() > Date.now()
          }
        },
        // 日期数据
        dateRangeValue: '',
        tableData: {
          start_time: '',
          end_time: ''
        },
        withdrawal: '0.00'
      }
    },
    methods: {
      // 日期选择
      datePickerChange(e) {
        if (e) {
          this.tableData.start_time = e[0].getTime().toString().slice(0, 10)
          this.tableData.end_time = e[1].getTime().toString().slice(0, 10)
        } else {
          this.tableData.start_time = ''
          this.tableData.end_time = ''
        }
      },
      // 查询
      submitSearch() {
        this.Mix_tableData.currentPage = 1
        this.getWithdrawalList()
      },
      // 获取提现列表
      async getWithdrawalList() {
        await getWithdrawalList({
          page: this.Mix_tableData.currentPage,
          limit: this.Mix_tableData.pageSize,
          start_time: this.tableData.start_time,
          end_time: this.tableData.end_time
        }).then(data => {
          this.Mix_tableData.list = data.list
          this.Mix_tableData.total = data.count
          this.withdrawal = data.withdrawal
        })
      }
    },
    mounted() {
      this.getWithdrawalList()
    }
  }
</script>

<style>
</style>
