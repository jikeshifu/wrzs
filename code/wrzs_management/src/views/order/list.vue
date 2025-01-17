<template>
  <div class="order-manage-index">
    <el-select v-model="tableData.type" placeholder="订单状态" clearable filterable>
      <el-option label="进行中" :value="2"></el-option>
      <el-option label="已完成" :value="3"></el-option>
    </el-select>
    <el-input placeholder="订单号" v-model="tableData.order_sn" maxlength="50" style="width:300px;"></el-input>
    <el-button icon="el-icon-search" type="primary" @click="submitSearch">查询</el-button>
    <el-table ref="table" class="mt15" :data="Mix_tableData.list" border height="calc(100vh - 225px)">
      <el-table-column label="ID" prop="order_id" width="80"></el-table-column>
      <el-table-column label="订单号" prop="order_sn"></el-table-column>
      <el-table-column label="订单价格" prop="order_price"></el-table-column>
      <el-table-column label="支付方式" prop="pay_type"> </el-table-column>、
      <el-table-column label="订单状态" prop="order_status"> </el-table-column>
      <el-table-column label="订单类别" prop="order_type"> </el-table-column>
      <el-table-column label="手机号">
        <template slot-scope="scope">
           {{ scope.row.memberWechat.mobile}}
        </template>
      </el-table-column>
      <el-table-column label="支付时间">
        <template slot-scope="scope" v-if="scope.row&&scope.row.pay_time>0">
           {{ getLocalTime(scope.row.pay_time)}}
        </template>
      </el-table-column>

      <el-table-column label="操作" align="center"  fixed="right">
        <template slot-scope="scope">
          <el-button type="danger"  size="mini" @click="cancelOrder(scope.row.order_id)">取消订单</el-button>
        </template>
      </el-table-column>
    </el-table>
    <Pagination :tableData="Mix_tableData" @pageChange="$pageChange" :getList="getOrderList"></Pagination>

  </div>
</template>

<script>
  import {
    getOrderList,
    deliverHandle,
    cancelOrder
  } from '@/api/order/order'

  export default {
    name: 'order-index',
    data() {
      return {
        tableData: {
          order_sn: '',
          type: ''
        },
      }
    },
    methods: {
      //转换时间
      getLocalTime(val) {
        var date = new Date(val*1000);
        var year = date.getFullYear();
        var month = date.getMonth() + 1;
        var day = date.getDate();
        var hour = date.getHours();
        var minute = date.getMinutes();
        var second = date.getSeconds();
        //这样写显示时间在1~9会挤占空间；所以要在1~9的数字前补零;
        if (month < 10) {
          month = '0' + month;
        }
        if (day < 10) {
          day = '0' + day;
        }
        if (hour < 10) {
          hour = '0' + hour;
        }
        if (minute < 10) {
          minute = '0' + minute;
        }
        if (second < 10) {
          second = '0' + second;
        }
        var x = date.getDay(); //获取星期
        var time = year + '-' + month + '-' + day + ' ' + hour + ':' + minute + ':' + second
        // var time = year + '-' + month + '-' + day
        return time;
      },
      // 取消订单
      cancelOrder(order_id) {
        this.$confirm('您确定要取消该订单吗?', '温馨提示', {
          cancelButtonText: '点错了',
          type: 'warning'
        }).then(async () => {
          await cancelOrder({
            order_id
          }).then(() => {
            this.$message.success('取消成功')
            this.Mix_tableData.currentPage = 1
            this.getOrderList()
          })
        }).catch(() => {})
      },
      // 查询
      submitSearch() {
        this.Mix_tableData.currentPage = 1
        this.getOrderList()
      },
      // 获取商品订单列表
      async getOrderList() {
        await getOrderList({
          page: this.Mix_tableData.currentPage,
          limit: this.Mix_tableData.pageSize,
          type: this.tableData.type,
          order_sn: this.tableData.order_sn
        }).then(data => {
          this.Mix_tableData.list = data.list;
          this.Mix_tableData.total = data.count;
        })
      }
    },
    mounted() {
      this.getOrderList()
    }
  }
</script>

<style lang="scss" scoped>
  ::v-deep .el-drawer__body {
    overflow-y: auto;
  }
  .drawer-wrap {
    padding-left: 30px;
    padding-right: 30px;
    .item {
      display: flex;
      .info {
        flex: 1;
        width: 1%;
        margin-left: 20px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        h4 {
          font-size: 20px;
          margin-top: 0;
          margin-bottom: 0;
        }
        .foot {
          font-size: 16px;
          display: flex;
          justify-content: space-between;
          .right {
            color: #ff7a08;
          }
        }
      }
      & + .item {
        margin-top: 20px;
      }
    }
  }
</style>
