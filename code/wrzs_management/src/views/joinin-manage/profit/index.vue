<template>
  <div class="joinin-manage-profit">
    <el-button icon="el-icon-back" @click="back">返回加盟商列表</el-button>
    <el-date-picker v-model="dateRangeValue" type="daterange" range-separator="至" start-placeholder="开始日期"
      end-placeholder="结束日期" :picker-options="pickerOptions" @change="datePickerChange">
    </el-date-picker>
    <el-button type="primary" icon="el-icon-search" @click="$submitSearch(getProfitList)">筛选</el-button>
    <el-tag>当前加盟商：{{ joininData.user_name }}</el-tag>
    <el-tag type="danger">总收益：{{ profit }}元</el-tag>
    <el-table ref="table" class="mt15" :data="Mix_tableData.list" border height="calc(100vh - 225px)">
      <el-table-column prop="order_id" label="订单ID"></el-table-column>
      <el-table-column prop="order_profit" label="收益金额(元)"></el-table-column>
      <el-table-column prop="order_type" label="订单类型"></el-table-column>
      <el-table-column label="时间">
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
  } from '@/api/joinin-manage.js'
  import {
    mapState
  } from 'vuex'

  export default {
    name: 'joinin-manage-profit',
    computed: {
      ...mapState({
        joininData: state => state['joinin-manage'].joininData
      })
    },
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
      back() {
        this.$emit('changeComponents', 'index')
      },
      // 获取收益列表
      async getProfitList() {
        await getProfitList({
          page: this.Mix_tableData.currentPage,
          limit: this.Mix_tableData.pageSize,
          join_id: this.joininData.user_id,
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
  .el-tag+.el-tag,
  .el-date-editor,
  .el-date-editor + .el-button {
    margin-left: 10px;
  }
</style>
