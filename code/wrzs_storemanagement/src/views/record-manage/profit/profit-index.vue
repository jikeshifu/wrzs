<template>
  <div class="profit-index">
    <el-date-picker v-model="dateRangeValue" type="daterange" range-separator="至" start-placeholder="开始日期"
      end-placeholder="结束日期" :picker-options="pickerOptions" @change="datePickerChange">
    </el-date-picker>
    <el-button type="primary" icon="el-icon-search" @click="submitSearch">筛选</el-button>
    <el-tag type="danger">总收益：{{ profit }}元</el-tag>
    <el-table ref="table" class="mt15" :data="Mix_tableData.list" border height="calc(100vh - 225px)">
      <el-table-column prop="order_id" label="订单ID"></el-table-column>
      <el-table-column prop="order_profit" label="收益金额(元)"></el-table-column>
      <el-table-column prop="order_type" label="订单类型"></el-table-column>
      <el-table-column label="购买时间">
        <template
          slot-scope="scope">{{ $dateFormatter(+(scope.row.created_at + '000'), 'yy-mm-dd hh:mm:ss') }}</template>
      </el-table-column>
    </el-table>
    <Pagination :tableData="Mix_tableData" @pageChange="$pageChange" :getList="getProfitList"></Pagination>
  </div>
</template>

<script>
  import {
    getProfitList
  } from '@/api/record-manage/profit.js'

  export default {
    name: 'profit-index',
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
        profit: '0.00'
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
        this.getProfitList()
      },
      // 获取收益列表
      async getProfitList() {
        await getProfitList({
          page: this.Mix_tableData.currentPage,
          limit: this.Mix_tableData.pageSize,
          start_time: this.tableData.start_time,
          end_time: this.tableData.end_time
        }).then(data => {
          this.Mix_tableData.list = data.list
          this.Mix_tableData.total = data.count
          this.profit = data.profit
        })
      }
    },
    mounted() {
      this.getProfitList()
    }
  }
</script>

<style scoped>
  
</style>
