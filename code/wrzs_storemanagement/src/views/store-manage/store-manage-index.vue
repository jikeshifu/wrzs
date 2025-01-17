<template>
  <div class="store-manage-index">
    <el-button type="primary" icon="el-icon-circle-plus-outline" @click="addStore">添加门店</el-button>
    <el-button type="warning" icon="el-icon-edit" :disabled="Mix_tableData.selectionIds.length !== 1" @click="editStore(Mix_tableData.selectionObj[0])">编辑</el-button>
    <el-button type="danger" icon="el-icon-delete" :disabled="!Mix_tableData.selectionIds.length" @click="delStore(Mix_tableData.selectionIds)">批量删除</el-button>
    <el-input style="width:200px" placeholder="门店名称/联系电话" v-model="tableData.public" maxlength="30" clearable></el-input>
    <el-select v-model="tableData.status" placeholder="请选择状态" clearable>
      <el-option label="正常" :value="1"></el-option>
      <el-option label="停用" :value="0"></el-option>
    </el-select>
    <el-button type="primary" icon="el-icon-search" @click="searchSubmit">查询</el-button>
    <el-button type="primary" icon="el-icon-refresh-right" @click="resetData">重置</el-button>
    <el-table class="mt15" border height="calc(100vh - 225px)" :data="Mix_tableData.list" @selection-change="$selectionChange($event, 'store_id')">
      <el-table-column type="selection" width="50"></el-table-column>
      <el-table-column type="index" label="序号" width="80" align="center"></el-table-column>
      <el-table-column label="门店图片" width="150">
        <template slot-scope="scope">
          <el-image style="width:100%;height:100px" :src="baseUrl + scope.row.store_image" fit="cover" alt="" :preview-src-list="[baseUrl + scope.row.store_image]"></el-image>
        </template>
      </el-table-column>
      <el-table-column label="门店名称" prop="store_name"></el-table-column>
      <el-table-column label="门店类型" prop="store_type">
        <template slot-scope="scope">
          <el-tag v-if="scope.row.store_type === 1">共享茶室</el-tag>
          <el-tag v-if="scope.row.store_type === 2">舞社唱吧</el-tag>
          <el-tag v-if="scope.row.store_type === 3">民宿公寓</el-tag>
        </template>
      </el-table-column>
      <el-table-column label="联系电话" prop="contact" width="120"></el-table-column>
      <el-table-column label="状态" width="80" align="center">
        <template slot-scope="scope">
          <el-switch v-model="scope.row.status" :active-value="1" :inactive-value="0" @change="changeStatus(scope.row)"></el-switch>
        </template>
      </el-table-column>
      <el-table-column label="详细地址" prop="address"></el-table-column>
      <el-table-column label="操作" type="action" width="400">
        <template slot-scope="scope">
          <el-button type="primary" icon="el-icon-notebook-2" size="mini" @click="pageToRoom(scope.row)">房间管理</el-button>
          <el-button type="warning" icon="el-icon-edit" size="mini" @click="editStore(scope.row)">编辑</el-button>
          <el-button type="danger" icon="el-icon-delete" size="mini" @click="delStore([scope.row.store_id])">删除</el-button>
          <el-button type="primary" icon="el-icon-document" size="mini" @click="showDetailInfo(scope.row)">详情</el-button>
        </template>
      </el-table-column>
    </el-table>
    <Pagination :tableData="Mix_tableData" @pageChange="$pageChange" :getList="getStoreList"></Pagination>
    <el-drawer
      title="详情信息"
      direction="ltr"
      :visible.sync="detailInfoDrawer.visible">
      <div class="pd20">
        <el-form>
          <el-form-item label="门店图片">
            <el-image :src="baseUrl + detailInfoDrawer.dataInfo.store_image" fit="cover" alt="" style="width:100px"></el-image>
          </el-form-item>
          <el-form-item label="门店名称">{{ detailInfoDrawer.dataInfo.store_name }}</el-form-item>
          <el-form-item label="门店介绍">{{ detailInfoDrawer.dataInfo.store_about }}</el-form-item>
          <el-form-item label="联系电话">{{ detailInfoDrawer.dataInfo.contact }}</el-form-item>
          <el-form-item label="状态">
            <el-switch :value="detailInfoDrawer.dataInfo.status" :active-value="1" :inactive-value="0"></el-switch>
          </el-form-item>
          <el-form-item label="所属地区">{{ detailInfoDrawer.dataInfo.province + detailInfoDrawer.dataInfo.city + detailInfoDrawer.dataInfo.district }}</el-form-item>
          <el-form-item label="详细地址">{{ detailInfoDrawer.dataInfo.address }}</el-form-item>
          <el-form-item label="经纬度">{{ detailInfoDrawer.dataInfo.longitude + ', ' + detailInfoDrawer.dataInfo.latitude }}</el-form-item>
        </el-form>
      </div>
    </el-drawer>
  </div>
</template>

<script>
import {getStoreList, delStore, editStoreStatus} from "@/api/store-manage/store-manage"

export default {
  name: "store-manage-index",
  data() {
    return {
      tableData: {
        public: '',
        status: ''
      },
      // 详情信息抽屉
      detailInfoDrawer: {
        visible: false,
        dataInfo: ''
      }
    }
  },
  methods: {
    // 修改状态
    async changeStatus(e) {
      await editStoreStatus({
        store_id: e.store_id,
        status: e.status
      }).then(() => {
        this.$message.success('修改成功')
        this.getStoreList()
      })
    },
    // 显示详情
    showDetailInfo(data) {
      this.detailInfoDrawer.visible = true
      this.detailInfoDrawer.dataInfo = data
    },
    // 重置数据
    resetData() {
      this.tableData.status = ''
      this.tableData.public = ''
    },
    // 查询提交
    searchSubmit() {
      this.Mix_tableData.currentPage = 1
      this.getStoreList()
    },
    // 获取门店列表
    async getStoreList() {
      await getStoreList({
        page: this.Mix_tableData.currentPage,
        limit: this.Mix_tableData.pageSize,
        public: this.tableData.public,
        status: this.tableData.status
      }).then((data) => {
        this.Mix_tableData.list = data.list
        this.Mix_tableData.total = data.count
      })
    },
    // 添加门店
    addStore() {
      this.$emit('componentsChange', 'add')
    },
    // 编辑门店
    async editStore(data) {
      await this.$store.dispatch('store-manage/store-manage/setStoreManageData', data)
      this.$emit('componentsChange', 'edit')
    },
    // 跳转到房间管理
    async pageToRoom(data) {
      await this.$store.dispatch('store-manage/store-manage/setStoreManageData', data)
      this.$emit('componentsChange', 'room')
    },
    // 删除门店
    delStore(store_id) {
      this.$confirm('您确定要删除该门店吗?', '温馨提示', {
        type: 'warning'
      }).then(async () => {
        await delStore({
          store_id
        }).then(() => {
          this.$message.success('删除成功')
          this.Mix_tableData.currentPage = 1
          this.getStoreList()
        })
      }).catch(() => {})
    }
  },
  mounted() {
    this.getStoreList()
  }
}
</script>

<style lang="scss" scoped>
.pd20 {
  padding-left: 20px;
  padding-right: 20px;
}
</style>
