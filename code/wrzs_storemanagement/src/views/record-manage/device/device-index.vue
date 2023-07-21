<template>
  <div class="device-index">
    <el-input style="width:300px" v-model="tableData.public" placeholder="设备名称、设备序列号，房间名称" @keypress.native.enter="searchSubmit" @clear="searchSubmit" clearable></el-input>
    <el-button type="primary" icon="el-icon-search" @click="searchSubmit">查询</el-button>
    <el-table class="mt15" border height="calc(100vh - 225px)" :data="Mix_tableData.list">
      <el-table-column label="记录ID" prop="record_id" width="80" align="center"></el-table-column>
      <el-table-column label="设备名称" prop="device_name"></el-table-column>
      <el-table-column label="创建时间">
        <template slot-scope="scope">
          {{ $dateFormatter(scope.row.created_at + '000', 'yy-mm-dd hh:mm:ss') }}
        </template>
      </el-table-column>
      <el-table-column label="设备序列号" prop="device_sn"></el-table-column>
      <el-table-column label="所属房间" prop="room_name"></el-table-column>
      <el-table-column label="操作人" prop="username"></el-table-column>
      <el-table-column label="操作标识" prop="title"></el-table-column>
    </el-table>
    <Pagination :tableData="Mix_tableData" @pageChange="$pageChange" :getList="getDeviceRecordList"></Pagination>
  </div>
</template>

<script>
import {getDeviceRecordList} from "@/api/record-manage/device"

export default {
  name: "device-index",
  data() {
    return {
      tableData: {
        public: ''
      }
    }
  },
  methods: {
    // 查询
    searchSubmit() {
      this.Mix_tableData.currentPage = 1
      this.getDeviceRecordList()
    },
    // 获取设备记录列表
    async getDeviceRecordList() {
      await getDeviceRecordList({
        page: this.Mix_tableData.currentPage,
        limit: this.Mix_tableData.pageSize,
        public: this.tableData.public
      }).then(data => {
        this.Mix_tableData.list = data.list
        this.Mix_tableData.total = data.count
      })
    }
  },
  mounted() {
    this.getDeviceRecordList()
  }
}
</script>

<style scoped>

</style>
