<template>
  <div class="room-order-manage-index">
    <el-select v-model="tableData.store_id" placeholder="请选择门店" filterable clearable @change="getRoomList">
      <el-option v-for="item in storeArr" :key="item.store_id" :value="item.store_id" :label="item.store_name"></el-option>
    </el-select>
    <el-select v-model="tableData.room_id" placeholder="请选择房间" filterable clearable>
      <el-option v-for="item in roomArr" :key="item.room_id" :value="item.room_id" :label="item.room_name"></el-option>
    </el-select>
    <el-input v-model="tableData.order_sn" style="width:300px" maxlength="50" placeholder="订单号查询" clearable></el-input>
    <el-select v-model="tableData.order_status" placeholder="订单状态" filterable clearable>
      <el-option value="1" label="待开始"></el-option>
      <el-option value="2" label="进行中"></el-option>
      <el-option value="3" label="已完成"></el-option>
      <el-option value="4" label="已取消"></el-option>
    </el-select>
    <el-select v-model="tableData.pay_type" placeholder="支付方式" filterable clearable>
      <el-option value="balance" label="钱包余额"></el-option>
      <el-option value="wechat" label="微信"></el-option>
    </el-select>
    <el-select v-model="tableData.pay_status" placeholder="支付状态" filterable clearable>
      <el-option value="0" label="未支付"></el-option>
      <el-option value="1" label="已支付"></el-option>
    </el-select>
    <el-button type="primary" icon="el-icon-search" @click="submitSearch">查询</el-button>
    <el-button type="primary" icon="el-icon-refresh-right" @click="resetData">重置</el-button>
    <el-table class="mt15" border height="calc(100vh - 225px)" :data="Mix_tableData.list">
      <el-table-column label="订单ID" prop="order_id"></el-table-column>
      <el-table-column label="开始时间" width="180">
        <template slot-scope="scope">
          {{ $dateFormatter(scope.row.orderRoom.start_time + '000', 'yy-mm-dd hh:mm:ss') }}
        </template>
      </el-table-column>
      <el-table-column label="结束时间" width="180">
        <template slot-scope="scope">
          {{ $dateFormatter(scope.row.orderRoom.end_time + '000', 'yy-mm-dd hh:mm:ss') }}
        </template>
      </el-table-column>
      <el-table-column label="时长">
        <template slot-scope="scope">
          <!-- 按小时 -->
          <div v-if="scope.row.orderRoom.room_info.room_type === 1">{{ $diffTime(+(scope.row.orderRoom.start_time + '000'), +(scope.row.orderRoom.end_time + '000')).fmt }}</div>
          <!-- 按天数 -->
          <div v-if="scope.row.orderRoom.room_info.room_type === 2">{{ $diffDate(+(scope.row.orderRoom.start_time + '000'), +(scope.row.orderRoom.end_time + '000')) }}天</div>
          <!-- 按月 -->
          <div v-if="scope.row.orderRoom.room_info.room_type === 4">{{ $diffDate(+(scope.row.orderRoom.start_time + '000'), +(scope.row.orderRoom.end_time + '000')) }}天</div>
          <!-- 按分钟 -->
          <div v-if="scope.row.orderRoom.room_info.room_type === 5">{{ $diffTime(+(scope.row.orderRoom.start_time + '000'), +(scope.row.orderRoom.end_time + '000')).fmt }}</div>
        </template>
      </el-table-column>
      <el-table-column label="总收益(元)" prop="order_profit"></el-table-column>
      <el-table-column label="订单状态">
        <template slot-scope="scope">
          <el-tag type="info" v-if="scope.row.order_status === 1">待开始</el-tag>
          <el-tag type="primary" v-if="scope.row.order_status === 2">进行中</el-tag>
          <el-tag type="success" v-if="scope.row.order_status === 3">已完成</el-tag>
          <el-tag type="danger" v-if="scope.row.order_status === 4">已取消</el-tag>
        </template>
      </el-table-column>
      <el-table-column label="支付方式">
        <template slot-scope="scope">
          <div class="pay-type" v-if="scope.row.pay_type === 'balance'"><span class="kj kj-qb"></span>钱包余额</div>
          <div class="pay-type" v-if="scope.row.pay_type === 'wechat'"><span class="kj kj-wechat"></span>微信</div>
        </template>
      </el-table-column>
      <el-table-column label="支付状态">
        <template slot-scope="scope">
          <el-tag type="info" v-if="scope.row.pay_status === '0'">未支付</el-tag>
          <el-tag type="success" v-if="scope.row.pay_status === '1'">已支付</el-tag>
        </template>
      </el-table-column>
      <el-table-column label="下单用户" align="center">
        <template slot-scope="scope">
          <el-button size="mini" type="text" icon="el-icon-view" @click="checkUser(scope.row.member)">查看</el-button>
        </template>
      </el-table-column>
      <el-table-column label="房间信息" align="center">
        <template slot-scope="scope">
          <el-button size="mini" type="text" icon="el-icon-view" @click="checkRoom(scope.row)">查看</el-button>
        </template>
      </el-table-column>
      <el-table-column label="操作" type="action" width="250" align="center">
        <template slot-scope="scope">
          <el-button type="warning" size="mini" @click="cancelOrder(scope.row.order_id)" v-if="scope.row.order_status === 1">取消订单</el-button>
          <el-button type="danger" size="mini" v-if="scope.row.order_status === 3 && scope.row.deposit_status !== 3" @click="returnDeposit(scope.row)">退还押金</el-button>
        </template>
      </el-table-column>
    </el-table>
    <Pagination :tableData="Mix_tableData" @pageChange="$pageChange" :getList="getRoomOrderList"></Pagination>
    <el-drawer
      title="下单用户信息"
      :visible.sync="userDrawer.show"
      direction="ltr">
      <el-form label-position="left" label-width="80px">
        <el-form-item label="微信昵称">{{ userDrawer.data.nick_name }}</el-form-item>
        <el-form-item label="微信头像">
          <el-image :src="userDrawer.data.avatar_url" style="width:100px;height:100px" fit="cover"></el-image>
        </el-form-item>
        <el-form-item label="性别">
          <el-tag type="primary" v-if="userDrawer.data.gender === 1">男</el-tag>
          <el-tag type="danger" v-if="userDrawer.data.gender === 2">女</el-tag>
        </el-form-item>
        <el-form-item label="联系方式">{{ userDrawer.data.mobile }}</el-form-item>
        <el-form-item label="openid">{{ userDrawer.data.openid }}</el-form-item>
        <el-form-item label="地区">{{ userDrawer.data.province }}</el-form-item>
      </el-form>
    </el-drawer>
    <el-drawer
      title="房间信息"
      :visible.sync="roomDrawer.show"
      direction="ltr">
      <el-form label-position="left" label-width="100px">
        <el-form-item label="门店名称">{{ roomDrawer.data.store.store_name }}</el-form-item>
        <el-form-item label="营业时间">
          <div>
            <el-tag v-for="(item, index) in roomDrawer.data.store.work_week" :key="item">{{ item | numTransZh_Week }}</el-tag>
          </div>
          <div>{{ roomDrawer.data.store.work_time_start }} ~ {{ roomDrawer.data.store.work_time_end }}</div>
        </el-form-item>
        <el-form-item label="门店地址">{{ roomDrawer.data.store.address }}</el-form-item>
        <el-form-item label="门店主图">
          <el-image :src="baseUrl + roomDrawer.data.store.store_image" style="width:100px;height:100px" fit="cover"></el-image>
        </el-form-item>
        <el-form-item label="房间名称">{{ roomDrawer.data.orderRoom.room_info.room_name }}</el-form-item>
        <el-form-item label="可容纳人数">{{ roomDrawer.data.orderRoom.room_info.room_people }}人</el-form-item>
        <el-form-item label="押金">{{ roomDrawer.data.orderRoom.room_info.room_deposit }}元</el-form-item>
        <el-form-item label="价格">{{ roomDrawer.data.orderRoom.room_info.room_price }}元/{{ roomDrawer.data.orderRoom.room_info.room_type === 1 ? '小时' : roomDrawer.data.orderRoom.room_info.room_type === 2 ? '天' : roomDrawer.data.orderRoom.room_info.room_type === 3 ? '次' : roomDrawer.data.orderRoom.room_info.room_type === 4 ? '月' : roomDrawer.data.orderRoom.room_info.room_type === 5 ? '分钟' : '' }}</el-form-item>
        <el-form-item label="房间面积">{{ roomDrawer.data.orderRoom.room_info.room_size }}平方米</el-form-item>
        <el-form-item label="房间标签">
          <el-tag>{{ roomDrawer.data.orderRoom.room_info.room_label }}</el-tag>
        </el-form-item>
      </el-form>
    </el-drawer>
    <el-dialog
      title="退款押金"
      :visible.sync="returnDepositDialog.show"
      width="30%">
      <el-form label-width="80px" ref="returnDepositDialogForm" :model="returnDepositDialog.form" :rules="returnDepositDialog.formRules">
        <el-form-item label="扣除金额">
          <el-input-number :step="0.01" v-model="returnDepositDialog.form.deposit_deduct" :min="0" :precision="2" :max="returnDepositDialog.form.deposit"></el-input-number>
        </el-form-item>
        <el-form-item label="备注">
          <el-input type="textarea" v-model="returnDepositDialog.form.deposit_remarks" rows="5" maxlength="200" show-word-limit></el-input>
        </el-form-item>
      </el-form>
      <div slot="footer">
        <el-button @click="hideReturnDepositDialog">取消</el-button>
        <el-button type="primary" @click="submitReturnDeposit">确定</el-button>
      </div>
    </el-dialog>
  </div>
</template>

<script>
import {getRoomOrderList} from "@/api/order-manage/room-order-manage"
import {getStoreList} from "@/api/store-manage/store-manage"
import {getRoomList} from "@/api/store-manage/room-manage"
import {returnDeposit} from "@/api/order-manage/room-order-manage"
import {cancelOrder} from "@/api/order-manage/room-order-manage"

export default {
  name: "room-order-manage-index",
  data() {
    return {
      // 门店数据
      storeArr: [],
      // 房间数据
      roomArr: [],
      tableData: {
        store_id: '',
        room_id: '',
        order_status: '',
        order_sn: '',
        pay_type: '',
        pay_status: ''
      },
      // 下单用户信息
      userDrawer: {
        show: false,
        data: {}
      },
      // 房间信息
      roomDrawer: {
        show: false,
        data: {
          store: {
            work_week: []
          },
          orderRoom: {
            room_info: {}
          }
        }
      },
      // 退还押金弹窗
      returnDepositDialog: {
        show: false,
        form: {
          order_id: '',
          deposit_deduct: '',
          deposit: '',
          deposit_remarks: ''
        },
        formRules: {
          deposit_deduct: [{
            required: true,
            message: '请输入退款金额'
          }]
        }
      }
    }
  },
  methods: {
    // 取消退款弹窗
    hideReturnDepositDialog() {
      this.returnDepositDialog.show = false
      this.$refs['returnDepositDialogForm'].resetFields()
    },
    // 退款提交弹窗
    submitReturnDeposit() {
      this.$refs['returnDepositDialogForm'].validate(async valid => {
        if (valid) {
          await returnDeposit({
            order_id: this.returnDepositDialog.form.order_id,
            deposit_deduct: this.returnDepositDialog.form.deposit_deduct,
            deposit_remarks: this.returnDepositDialog.form.deposit_remarks
          }).then(() => {
            this.$message.success('退款成功')
            this.returnDepositDialog.show = false
            this.getRoomOrderList()
          })
        } else {
          return false
        }
      })
    },
    // 查看下单用户
    checkUser(data) {
      this.userDrawer.show = true
      this.userDrawer.data = data
    },
    // 查看房间
    checkRoom(data) {
      this.roomDrawer.show = true
      this.roomDrawer.data = data
      this.roomDrawer.data.store.work_week = this.roomDrawer.data.store.work_week.sort()
    },
    // 查询
    submitSearch() {
      this.Mix_tableData.currentPage = 1
      this.getRoomOrderList()
    },
    // 重置数据
    resetData() {
      this.roomArr = []
      this.tableData.store_id = ''
      this.tableData.room_id = ''
      this.tableData.pay_status = ''
      this.tableData.pay_type = ''
      this.tableData.order_sn = ''
      this.tableData.order_status = ''
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
          this.getRoomOrderList()
        })
      }).catch(() => {})
    },
    // 退还押金
    returnDeposit(item) {
      this.returnDepositDialog.form.order_id = item.order_id
      this.returnDepositDialog.form.deposit_deduct = item.deposit_deduct
      this.returnDepositDialog.form.deposit = item.deposit
      this.returnDepositDialog.show = true
    },
    // 获取房间订单列表
    async getRoomOrderList() {
      await getRoomOrderList({
        page: this.Mix_tableData.currentPage,
        limit: this.Mix_tableData.pageSize,
        store_id: this.tableData.store_id,
        room_id: this.tableData.room_id,
        order_sn: this.tableData.order_sn,
        order_status: this.tableData.order_status,
        pay_type: this.tableData.pay_type,
        pay_status: this.tableData.pay_status
      }).then(data => {
        this.Mix_tableData.list = data.list
        this.Mix_tableData.total = data.count
      })
    },
    // 获取门店列表
    async getStoreList() {
      await getStoreList().then(data => {
        this.storeArr = data.list
      })
    },
    // 获取房间列表
    async getRoomList(store_id) {
      this.roomArr = []
      this.tableData.room_id = ''
      await getRoomList({
        store_id
      }).then(data => {
        this.roomArr = data.list
      })
    }
  },
  mounted() {
    this.getRoomOrderList()
    this.getStoreList()
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

::v-deep .el-drawer__body {
  padding: 20px;
}

.el-tag + .el-tag {
  margin-left: 10px;
}
</style>
