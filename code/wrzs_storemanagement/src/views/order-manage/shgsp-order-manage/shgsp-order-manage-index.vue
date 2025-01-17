<template>
  <div class="shgsp-order-manage-index">
    <el-table border height="calc(100vh - 170px)" :data="Mix_tableData.list">
      <el-table-column label="订单ID" prop="order_id"></el-table-column>
      <el-table-column label="订单号" prop="order_sn"></el-table-column>
      <el-table-column label="订单价格(元)" prop="order_price"></el-table-column>
      <el-table-column label="支付方式">
        <template slot-scope="scope">
          <div class="pay-type" v-if="scope.row.pay_type === 'balance'"><span class="kj kj-qb"></span>钱包余额</div>
          <div class="pay-type" v-if="scope.row.pay_type === 'wechat'"><span class="kj kj-wechat"></span>微信</div>
        </template>
      </el-table-column>
      <el-table-column label="订单状态">
        <template slot-scope="scope">
          <el-tag type="primary" v-if="scope.row.order_status === 3">已完成</el-tag>
          <el-tag type="danger" v-if="scope.row.order_status === 4">已取消</el-tag>
        </template>
      </el-table-column>
      <el-table-column label="售货柜锁状态">
        <template slot-scope="scope">
          <el-tag type="danger" v-if="scope.row.orderCabinet.lock_status === 0">异常</el-tag>
          <el-tag type="success" v-if="scope.row.orderCabinet.lock_status === 1">正常</el-tag>
        </template>
      </el-table-column>
      <el-table-column label="商品信息">
        <template slot-scope="scope">
          <el-button type="text" size="mini" @click="checkGoods(scope.row)">查看</el-button>
        </template>
      </el-table-column>
      <el-table-column label="购买用户">
        <template slot-scope="scope">
          <el-popover
            placement="top-end"
            width="400"
            trigger="click">
            <el-form label-width="80px">
              <el-form-item label="昵称">{{ scope.row.member.nick_name }}</el-form-item>
              <el-form-item label="头像">
                <el-image :src="scope.row.member.avatar_url" fit="cover" style="width:100px;height:100px;" :preview-src-list="[scope.row.member.avatar_url]"></el-image>
              </el-form-item>
              <el-form-item label="电话">{{ scope.row.member.mobile }}</el-form-item>
              <el-form-item label="地址">{{ scope.row.member.province }}</el-form-item>
            </el-form>
            <el-button slot="reference" type="text" size="mini">查看</el-button>
          </el-popover>
        </template>
      </el-table-column>
      <el-table-column label="操作" type="action" width="250" align="center">
        <template slot-scope="scope">
          <el-button type="primary" v-if="scope.row.order_status === 3" size="mini" @click="cancelOrder(scope.row.order_id)">取消订单</el-button>
        </template>
      </el-table-column>
    </el-table>
    <Pagination :tableData="Mix_tableData" @pageChange="$pageChange" :getList="getSHGSPOrderList"></Pagination>
    <el-drawer
      title="商品信息"
      :visible.sync="drawer.show"
      direction="ltr">
      <el-form label-width="120upx" style="padding-left:20px;padding-right:20px;">
        <el-form-item label="售货柜编码">{{ drawer.data.orderCabinet.goods_info.cabinet_number }}</el-form-item>
        <el-form-item label="售货柜名称">{{ drawer.data.orderCabinet.goods_info.cabinet.cabinet_name }}</el-form-item>
        <el-form-item label="商品图片">
          <el-image :src="baseUrl + drawer.data.orderCabinet.goods_info.goods_image" fit="cover" style="width:100px;height:100px;" :preview-src-list="[baseUrl + drawer.data.orderCabinet.goods_info.goods_image]"></el-image>
        </el-form-item>
        <el-form-item label="商品名称">{{ drawer.data.orderCabinet.goods_info.goods_name }}</el-form-item>
      </el-form>
    </el-drawer>
  </div>
</template>

<script>
  import { getSHGSPOrderList, cancelOrder } from '@/api/order-manage/shgsp-order-manage.js'

  export default {
    name: "shgsp-order-manage-index",
    data() {
      return {
        drawer: {
          show: false,
          data: {
            orderCabinet: {
              goods_info: {
                cabinet: {}
              }
            }
          }
        }
      }
    },
    methods: {
      // 查看商品
      checkGoods(data) {
        this.drawer.data = data
        this.drawer.show = true
      },
      // 取消订单
      cancelOrder(order_id) {
        this.$confirm('您确定要取消该订单吗?', '温馨提示', {
          type: 'warning'
        }).then(async () => {
          await cancelOrder({
            order_id
          }).then(() => {
            this.$message.success('已取消')
            this.Mix_tableData.currentPage = 1
            this.getSHGSPOrderList()
          })
        }).catch(() => {})
      },
      // 获取售货柜商品订单列表
      async getSHGSPOrderList() {
        await getSHGSPOrderList({
          page: this.Mix_tableData.currentPage,
          limit: this.Mix_tableData.pageSize
        }).then(data => {
          this.Mix_tableData.list = data.list
          this.Mix_tableData.total = data.count
        })
      }
    },
    mounted() {
      this.getSHGSPOrderList()
    }
  }
</script>

<style scoped lang="scss">
  .pay-type {
    .kj {
      font-size: 30px;
      vertical-align: -5px;
      margin-right: 5px;
    }
    .kj-qb {
      color: #e6a23c;
    }
    .kj-wechat {
      color: #67c23a;
    }
  }
</style>
