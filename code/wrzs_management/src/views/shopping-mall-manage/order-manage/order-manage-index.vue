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
      <el-table-column label="支付方式" prop="pay_type">
        <template slot-scope="scope">
          <el-tag v-if="scope.row.pay_type === 'balance'">钱包余额</el-tag>
          <el-tag type="danger" v-if="scope.row.pay_type === 'wxpay'">微信</el-tag>
        </template>
      </el-table-column>
      <el-table-column label="商品列表">
        <template slot-scope="scope">
          <el-button type="text" @click="checkGoods(scope.row)">查看</el-button>
        </template>
      </el-table-column>
      <el-table-column label="下单用户">
        <template slot-scope="scope">
          <el-popover trigger="click" placement="top-end" width="400">
            <el-form label-width="120px">
              <el-form-item></el-form-item>
              <el-form-item label="头像">
                <el-image style="width:100px;height:100px;" :src="scope.row.memberWechat.avatar_url" fit="cover"></el-image>
              </el-form-item>
              <el-form-item label="昵称">{{ scope.row.memberWechat.nick_name }}</el-form-item>
              <el-form-item label="联系方式">{{ scope.row.memberWechat.mobile }}</el-form-item>
              <el-form-item label="地区">{{ scope.row.memberWechat.province }}</el-form-item>
            </el-form>
            <el-button type="text" slot="reference">查看</el-button>
          </el-popover>
        </template>
      </el-table-column>
      <el-table-column label="收货信息">
        <template slot-scope="scope">
          <el-popover trigger="click" placement="top-end" width="400">
            <el-row type="flex" justify="space-between">
              <el-col :span="12">姓名:{{ scope.row.orderAddress.user_name }}</el-col>
              <el-col :span="12">联系方式:{{ scope.row.orderAddress.mobile }}</el-col>
            </el-row>
            <el-row class="mt15" type="flex" justify="space-between">
              <el-col :span="12">地址:{{ scope.row.orderAddress.address }}</el-col>
            </el-row>
            <el-button type="text" slot="reference">查看</el-button>
          </el-popover>
        </template>
      </el-table-column>
      <el-table-column label="订单状态">
        <template slot-scope="scope">
          {{ scope.row.order_status === 1 ? '待发货' : scope.row.order_status === 2 ? '已发货' : scope.row.order_status === 3 ? '已完成' : '' }}
        </template>
      </el-table-column>
      <el-table-column label="操作" align="center">
        <template slot-scope="scope">
          <el-button type="primary" v-if="scope.row.order_status === 1" size="mini" @click="showDeliverGoodsDialog(scope.row)">发货</el-button>
          <el-button type="danger" v-if="scope.row.order_status === 1" size="mini" @click="cancelOrder(scope.row.order_id)">取消订单</el-button>
        </template>
      </el-table-column>
    </el-table>
    <Pagination :tableData="Mix_tableData" @pageChange="$pageChange" :getList="getOrderList"></Pagination>
    <el-drawer
      title="商品列表"
      :visible.sync="drawer.show"
      direction="ltr">
      <div class="drawer-wrap">
        <div class="item" v-for="item in drawer.data" :key="item.id">
          <el-image style="width:100px;height:100px;" :src="baseUrl + item.goods_info.goods_image" fit="cover"></el-image>
          <div class="info">
            <h4>{{ item.goods_info.goods_name }}</h4>
            <div class="foot">
              <div class="left">￥{{ item.goods_info.goods_price }}</div>
              <div class="right">x{{ item.goods_number }}</div>
            </div>
          </div>
        </div>
      </div>
    </el-drawer>
    <el-dialog
      title="发货信息"
      :visible.sync="dialog.show"
      width="30%"
      :show-close="false">
      <el-form ref="form" :model="dialog.form" :rules="dialog.formRules">
        <el-form-item prop="deliver_sn" label="发货单号">
          <el-input v-model.trim="dialog.form.deliver_sn" placeholder="请输入发货单号" maxlength="50"></el-input>
        </el-form-item>
        <el-form-item label="备注">
          <el-input v-model="dialog.form.deliver_remarks" placeholder="备注" maxlength="100" show-word-limit type="textarea" rows="5"></el-input>
        </el-form-item>
      </el-form>
      <span slot="footer">
        <el-button @click="hideDialog">取消</el-button>
        <el-button type="primary" @click="submitDialog">确定</el-button>
      </span>
    </el-dialog>
  </div>
</template>

<script>
  import {
    getOrderList,
    deliverHandle,
    cancelOrder
  } from '@/api/shopping-mall-manage/order-manage.js'

  export default {
    name: 'order-manage-index',
    data() {
      return {
        tableData: {
          order_sn: '',
          type: ''
        },
        drawer: {
          show: false,
          data: []
        },
        dialog: {
          show: false,
          form: {
            order_id: '',
            deliver_sn: '',
            deliver_remarks: ''
          },
          formRules: {
            deliver_sn: [{
              required: true,
              message: '请输入发货单号'
            }]
          }
        }
      }
    },
    methods: {
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
      // 弹窗提交
      submitDialog() {
        this.$refs['form'].validate(async (valid) => {
          if (valid) {
            await deliverHandle(this.dialog.form).then(() => {
              this.$message.success('发货成功')
              this.hideDialog()
              this.getOrderList()
            })
          } else {
            return false
          }
        })
      },
      // 关闭弹窗
      hideDialog() {
        this.$refs['form'].resetFields()
        this.dialog.show = false
      },
      // 发货操作
      showDeliverGoodsDialog(data) {
        this.dialog.form.order_id = data.order_id
        this.dialog.show = true
      },
      // 查看商品列表
      checkGoods(data) {
        this.drawer.data = data.orderGoods
        this.drawer.show = true
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
          this.Mix_tableData.list = data.list
          this.Mix_tableData.total = data.count
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
