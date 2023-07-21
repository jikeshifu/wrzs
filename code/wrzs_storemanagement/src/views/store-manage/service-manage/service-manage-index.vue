<template>
  <div class="service-manage-index">
    <el-button icon="el-icon-back" @click="back">返回房间列表</el-button>
    <el-button type="primary" icon="el-icon-circle-plus-outline" @click="addService">添加服务</el-button>
    <el-button type="warning" icon="el-icon-edit" :disabled="tableData.selectionIds.length !== 1"
      @click="editManJian(tableData.selectionObj[0])">编辑</el-button>
    <el-button type="danger" icon="el-icon-delete" :disabled="!tableData.selectionIds.length"
      @click="delManJian(tableData.selectionIds)">批量删除</el-button>
    <el-tag type="primary">{{ `当前位置：${storeManage.store_name}，${roomManage.room_name}` }}</el-tag>
    <el-table ref="table" :data="tableData.list" border class="mt15" height="calc(100vh - 225px)"
      @selection-change="$selectionChange($event, 'service_id')">
      <el-table-column type="selection" width="50"></el-table-column>
      <el-table-column align="center" label="ID" prop="service_id" width="80"></el-table-column>
      <el-table-column label="服务标题" prop="service_title"></el-table-column>
      <el-table-column label="服务主图" width="100">
        <template slot-scope="scope">
          <el-image :src="baseUrl + scope.row.service_image" fit="cover" style="width:100%;height:100px;" :preview-src-list="[baseUrl + scope.row.service_image]"></el-image>
        </template>
      </el-table-column>
      <el-table-column label="服务内容" prop="service_content"></el-table-column>
      <el-table-column label="通知手机" prop="sms_mobile"></el-table-column>
      <el-table-column label="通知开关">
        <template slot-scope="scope">
          <el-switch v-model="scope.row.sms_status" :active-value="1" :inactive-value="0"></el-switch>
        </template>
      </el-table-column>
      <el-table-column label="服务费用(元)" prop="service_price"></el-table-column>
      <el-table-column label="操作" type="action">
        <template slot-scope="scope">
          <el-button type="warning" icon="el-icon-edit" size="mini" @click="editService(scope.row)">编辑</el-button>
          <el-button type="danger" icon="el-icon-delete" size="mini" @click="delService([scope.row.service_id])">删除
          </el-button>
        </template>
      </el-table-column>
    </el-table>
    <Pagination :tableData="tableData" @pageChange="$pageChange" :getList="getServiceList"></Pagination>
  </div>
</template>

<script>
  import {
    mapState
  } from 'vuex'
  import { getServiceList } from '@/api/store-manage/service-manage.js'

  export default {
    name: 'service-manage-index',
    computed: {
      ...mapState({
        storeManage: state => state['store-manage/store-manage'].storeManage,
        roomManage: state => state['store-manage/room-manage'].roomManage
      })
    },
    data() {
      return {
        tableData: {
          list: [],
          currentPage: 1,
          pageSize: 10,
          total: 0,
          selectionObj: [],
          selectionIds: []
        }
      }
    },
    methods: {
      // 返回上一页
      back() {
        this.$emit('back', 'room')
      },
      // 添加服务
      addService() {
        this.$emit('componentsChange', 'add')
      },
      // 获取服务列表
      async getServiceList() {
        await getServiceList({
          room_id: this.roomManage.room_id,
          limit: this.tableData.pageSize,
          page: this.tableData.currentPage
        }).then(data => {
					this.tableData.list = data.list
					this.tableData.total = data.count
				})
      }
    },
    mounted() {
      this.getServiceList()
    }
  }
</script>

<style lang="scss" scoped>

</style>
