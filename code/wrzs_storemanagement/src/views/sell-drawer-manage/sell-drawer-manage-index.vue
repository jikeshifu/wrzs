<template>
  <div class="sell-drawer-manage-index">
    <el-button type="primary" icon="el-icon-circle-plus-outline" @click="addSellDrawer">添加售货柜</el-button>
    <el-button type="warning" icon="el-icon-edit" :disabled="Mix_tableData.selectionIds.length !== 1"
               @click="editSellDrawer(Mix_tableData.selectionObj[0])">编辑
    </el-button>
    <el-button type="danger" icon="el-icon-delete" :disabled="!Mix_tableData.selectionIds.length"
               @click="delSellDrawer(Mix_tableData.selectionIds)">批量删除
    </el-button>
    <el-input v-model="tableData.public" placeholder="售货柜名称" style="width:200px" maxlength="20" clearable></el-input>
    <el-select v-model="tableData.store_id" placeholder="请选择门店名称" filterable clearable @change="storeChange">
      <el-option v-for="item in storeArr" :label="item.store_name" :value="item.store_id" :key="item.store_id"></el-option>
    </el-select>
    <el-select v-model="tableData.room_id" placeholder="请选择房间名称" filterable clearable>
      <el-option v-for="item in roomArr" :label="item.room_name" :value="item.room_id" :key="item.room_id"></el-option>
    </el-select>
    <el-button type="primary" icon="el-icon-search" @click="searchSubmit">查询</el-button>
    <el-button type="primary" icon="el-icon-refresh-right" @click="resetData">重置</el-button>
    <el-table ref="table" class="mt15" border height="calc(100vh - 225px)" :data="Mix_tableData.list"
              @selection-change="$selectionChange($event, 'cabinet_id')">
      <el-table-column type="selection" width="50"></el-table-column>
      <el-table-column label="ID" prop="cabinet_id"></el-table-column>
      <el-table-column label="售货柜名称" prop="cabinet_name"></el-table-column>
      <el-table-column label="设备序列号" prop="device_sn"></el-table-column>
      <el-table-column label="所属门店" prop="store_name"></el-table-column>
      <el-table-column label="所属房间" prop="room_name"></el-table-column>
      <el-table-column label="操作" type="action" width="300" align="center">
        <template slot-scope="scope">
          <el-button type="primary" size="mini" icon="el-icon-receiving" @click="pageToLattice(scope.row)">商品格管理</el-button>
          <el-button type="warning" size="mini" icon="el-icon-edit" @click="editSellDrawer(scope.row)">编辑</el-button>
          <el-button type="danger" size="mini" icon="el-icon-delete" @click="delSellDrawer([scope.row.cabinet_id])">删除
          </el-button>
        </template>
      </el-table-column>
    </el-table>
    <Pagination :tableData="Mix_tableData" @pageChange="$pageChange" :getList="getSellDrawerList"></Pagination>
  </div>
</template>

<script>
import {getStoreList} from "@/api/store-manage/store-manage"
import {getRoomList} from "@/api/store-manage/room-manage"
import {delSellDrawer, getSellDrawerList} from "@/api/sell-drawer-manage/sell-drawer-manage"

export default {
  name: "sell-drawer-manage-index",
  data() {
    return {
      // 门店列表数据
      storeArr: [],
      // 房间列表数据
      roomArr: [],
      tableData: {
        store_id: '',
        room_id: '',
        public: ''
      }
    }
  },
  methods: {
    // 重置数据
    resetData() {
      this.tableData.public = ''
      this.tableData.store_id = ''
      this.tableData.room_id = ''
      this.roomArr = []
    },
    // 编辑售货柜
    async editSellDrawer(data) {
      await this.$store.dispatch('sell-drawer-manage/sell-drawer-manage/setSellDrawerManageData', data)
      this.$emit('componentsChange', 'edit')
    },
    // 添加售货柜
    addSellDrawer() {
      this.$emit('componentsChange', 'add')
    },
    // 查询
    searchSubmit() {
      this.Mix_tableData.currentPage = 1
      this.getSellDrawerList()
    },
    // 删除售货柜
    delSellDrawer(cabinet_id) {
      this.$confirm('您确定要删除该售货柜吗?', '温馨提示', {
        type: 'warning'
      }).then(async () => {
        await delSellDrawer({
          cabinet_id
        }).then(() => {
          this.$message.success('删除成功')
          this.Mix_tableData.currentPage = 1
          this.getSellDrawerList()
        })
      }).catch(() => {})
    },
    // 跳转格子管理界面
    async pageToLattice(data) {
      await this.$store.dispatch('sell-drawer-manage/sell-drawer-manage/setSellDrawerManageData', data)
      this.$emit('componentsChange', 'lattice')
    },
    // 获取售货柜列表
    async getSellDrawerList() {
      await getSellDrawerList({
        page: this.Mix_tableData.currentPage,
        limit: this.Mix_tableData.pageSize,
        public: this.tableData.public,
        store_id: this.tableData.store_id,
        room_id: this.tableData.room_id
      }).then((data) => {
        this.Mix_tableData.list = data.list
        this.Mix_tableData.total = data.count
      })
    },
    // 获取门店列表
    async getStoreList() {
      await getStoreList().then((data) => {
        this.storeArr = data.list
      })
    },
    // 获取房间列表
    async storeChange(store_id) {
      this.tableData.room_id = ''
      await getRoomList({
        store_id
      }).then((data) => {
        this.roomArr = data.list
      })
    }
  },
  mounted() {
    this.getSellDrawerList()
    this.getStoreList()
  }
}
</script>

<style scoped>

</style>
