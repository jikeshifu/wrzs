<template>
  <div class="room-manage-index">
    <el-button @click="back" icon="el-icon-back">返回门店列表</el-button>
    <el-button type="primary" icon="el-icon-circle-plus-outline" @click="addRoom">添加房间</el-button>
    <el-button type="warning" icon="el-icon-edit" :disabled="Mix_tableData.selectionIds.length !== 1"
      @click="editRoom(Mix_tableData.selectionObj[0])">编辑
    </el-button>
    <el-button type="danger" icon="el-icon-delete" :disabled="!Mix_tableData.selectionIds.length"
      @click="delRoom(Mix_tableData.selectionIds)">批量删除
    </el-button>
    <el-input style="width:200px" placeholder="房间名称" v-model="tableData.public" maxlength="30" clearable></el-input>
    <el-select v-model="tableData.status" placeholder="请选择状态" clearable>
      <el-option label="正常" :value="1"></el-option>
      <el-option label="停用" :value="0"></el-option>
    </el-select>
    <el-button type="primary" icon="el-icon-search" @click="searchSubmit">查询</el-button>
    <el-button type="primary" icon="el-icon-refresh-right" @click="resetData">重置</el-button>
    <el-tag type="primary">当前门店名称：{{ storeManage.store_name }}</el-tag>
    <el-table ref="table" class="mt15" border height="calc(100vh - 225px)" :data="Mix_tableData.list"
      @selection-change="$selectionChange($event, 'room_id')">
      <el-table-column type="selection" width="50"></el-table-column>
      <el-table-column label="房间名称" prop="room_name"></el-table-column>
      <el-table-column label="房间标签" align="center">
        <template slot-scope="scope">
          <el-tag>{{ scope.row.room_label }}</el-tag>
        </template>
      </el-table-column>
      <el-table-column label="房间主图" width="100">
        <template slot-scope="scope">
          <el-image style="width:100%;height:100px;" :src="baseUrl + scope.row.room_image" fit="cover" alt=""
            :preview-src-list="[baseUrl + scope.row.room_image]"></el-image>
        </template>
      </el-table-column>
      <el-table-column label="押金" align="center">
        <template slot-scope="scope">
          {{ scope.row.room_deposit }}元
        </template>
      </el-table-column>
      <el-table-column label="排序(大到小)" width="120">
        <template slot-scope="scope">
          <el-input @blur="changeSort(scope.row)" v-model="scope.row.sort" maxlength="5"
            @input="scope.row.sort = Number(scope.row.sort.replace(/[^\d]/g,''))"></el-input>
        </template>
      </el-table-column>
      <el-table-column label="可容纳人数" align="center">
        <template slot-scope="scope">
          {{ scope.row.room_people }}人
        </template>
      </el-table-column>
      <el-table-column label="房间单价" align="center">
        <template slot-scope="scope">
          {{ scope.row.room_price }}元
        </template>
      </el-table-column>
      <el-table-column label="电源情况" align="center">
        <template slot-scope="scope">
          <el-popover placement="top-start" trigger="hover">
            <el-form class="light-form" label-width="80px">
              <el-form-item label="自动通电">
                <el-switch v-model="scope.row.electric_status" :active-value="1" :inactive-value="0"
                  @change="changeOnStatus(scope.row)">
                </el-switch>
              </el-form-item>
              <el-form-item label="自动关电">
                <el-switch v-model="scope.row.electric_stop_status" :active-value="1" :inactive-value="0"
                  @change="changeOffStatus(scope.row)">
                </el-switch>
              </el-form-item>
            </el-form>
            <el-button type="text" slot="reference">查看</el-button>
          </el-popover>
        </template>
      </el-table-column>
      <el-table-column label="起订时长" align="center">
        <template slot-scope="scope">
          {{ scope.row.starting_time }}
          <template v-if="scope.row.room_type === 1">小时</template>
          <template v-if="scope.row.room_type === 2">天</template>
        </template>
      </el-table-column>
      <el-table-column label="预定类型" align="center">
        <template slot-scope="scope">
          <el-tag type="primary" v-if="scope.row.room_type === 1">按小时</el-tag>
          <el-tag type="success" v-if="scope.row.room_type === 2">按天数</el-tag>
        </template>
      </el-table-column>
      <el-table-column label="状态" align="center">
        <template slot-scope="scope">
          <el-switch v-model="scope.row.status" :active-value="1" :inactive-value="0" @change="changeStatus(scope.row)">
          </el-switch>
        </template>
      </el-table-column>
      <!-- <el-table-column label="用户可取消" width="100" align="center">
        <template slot-scope="scope">
          <el-switch v-model="scope.row.cancel_status" :active-value="1" :inactive-value="0"
            @change="changeCancelStatus(scope.row)">
          </el-switch>
        </template>
      </el-table-column> -->
      <!-- <el-table-column label="可否结算" width="100" align="center">
        <template slot-scope="scope">
          <el-switch v-model="scope.row.settlement_status" :active-value="1" :inactive-value="0"
            @change="changeSettlStatus(scope.row)">
          </el-switch>
        </template>
      </el-table-column> -->
      <!-- <el-table-column label="工作时间" align="center">
        <template slot-scope="scope">
          <el-popover placement="top-end" title="工作日-营业时间" trigger="hover">
            <el-tag class="week-tag" v-for="item in scope.row.work_week" :key="item" type="primary">
              {{ item | numTransZh_Week }}
            </el-tag>
            <div class="mt15">
              <el-tag type="danger">{{ scope.row.work_time_start + ' ~ ' + scope.row.work_time_end }}</el-tag>
            </div>
            <el-button type="text" slot="reference">查看</el-button>
          </el-popover>
        </template>
      </el-table-column> -->
      <el-table-column label="房间面积" align="center">
        <template slot-scope="scope">
          {{ scope.row.room_size }}平方米
        </template>
      </el-table-column>
      <el-table-column label="已售" align="center" prop="room_sold"></el-table-column>
      <el-table-column label="操作" type="action" align="center">
        <template slot-scope="scope">
          <el-dropdown>
            <span class="el-dropdown-link">
              操作<i class="el-icon-arrow-down el-icon--right"></i>
            </span>
            <el-dropdown-menu slot="dropdown">
              <!-- <el-dropdown-item>
                <el-button type="text" size="mini" icon="kj kj-service" @click="service(scope.row)">房间服务
                </el-button>
              </el-dropdown-item> -->
              <el-dropdown-item>
                <el-button type="text" size="mini" icon="el-icon-document-copy" @click="copyRoom(scope.row.room_id)">
                  复制房间</el-button>
              </el-dropdown-item>
              <el-dropdown-item>
                <el-button type="text" size="mini" icon="el-icon-set-up" @click="pageToDevice(scope.row)">设备管理
                </el-button>
              </el-dropdown-item>
              <!-- <el-dropdown-item>
                <el-button type="text" size="mini" icon="kj kj-money" @click="manJian(scope.row)">满减管理
                </el-button>
              </el-dropdown-item> -->
              <el-dropdown-item>
                <el-button type="text" size="mini" icon="el-icon-edit" @click="editRoom(scope.row)">编辑</el-button>
              </el-dropdown-item>
              <el-dropdown-item>
                <el-button type="text" size="mini" icon="el-icon-delete" @click="delRoom([scope.row.room_id])">删除
                </el-button>
              </el-dropdown-item>
            </el-dropdown-menu>
          </el-dropdown>
        </template>
      </el-table-column>
    </el-table>
    <Pagination :tableData="Mix_tableData" @pageChange="$pageChange" :getList="getRoomList"></Pagination>
  </div>
</template>

<script>
  import {
    mapState
  } from "vuex"
  import {
    getRoomList,
    editRoomStatus,
    editRoomSort,
    editRoomOnStatus,
    editRoomOffStatus,
    editRoomSettlStatus,
    delRoom,
    copyRoom,
    editRoomCancelStatus
  } from "@/api/store-manage/room-manage"

  export default {
    name: "room-manage-index",
    computed: {
      ...mapState({
        storeManage: state => state['store-manage/store-manage'].storeManage
      })
    },
    data() {
      return {
        tableData: {
          public: '',
          status: ''
        }
      }
    },
    methods: {
      // 自动通电
      async changeOnStatus(row) {
        await editRoomOnStatus({
          room_id: row.room_id,
          status: row.electric_status
        }).then(() => {
          this.$message.success('修改成功')
          this.getRoomList()
        })
      },
      // 自动关电
      async changeOffStatus(row) {
        await editRoomOffStatus({
          room_id: row.room_id,
          status: row.electric_stop_status
        }).then(() => {
          this.$message.success('修改成功')
          this.getRoomList()
        })
      },
      // 返回上一页
      back() {
        this.$emit('back', 'index')
      },
      // 复制房间
      async copyRoom(room_id) {
        await copyRoom({
          room_id
        })
        this.$message.success('复制成功')
        await this.getRoomList()
      },
      // 获取房间列表
      async getRoomList() {
        this.$refs.table.clearFilter()
        await getRoomList({
          page: this.Mix_tableData.currentPage,
          limit: this.Mix_tableData.pageSize,
          status: this.tableData.status,
          public: this.tableData.public,
          store_id: this.storeManage.store_id
        }).then(data => {
          this.Mix_tableData.list = data.list
          this.Mix_tableData.total = data.count
        })
      },
      // 房间排序
      async changeSort(rows) {
        await editRoomSort({
          room_id: rows.room_id,
          sort: rows.sort
        }).then(() => {
          this.$message.success('修改成功')
          this.getRoomList()
        })
      },
      // 查询
      searchSubmit() {
        this.Mix_tableData.currentPage = 1
        this.getRoomList()
      },
      // 结算功能改变事件
      async changeSettlStatus(row) {
        await editRoomSettlStatus({
          room_id: row.room_id,
          settlement_status: row.settlement_status
        }).then(() => {
          this.$message.success('修改成功')
          this.getRoomList()
        })
      },
      // 用户可否取消该房间
      async changeCancelStatus(row) {
        await editRoomCancelStatus({
          room_id: row.room_id,
          cancel_status: row.cancel_status
        }).then(() => {
          this.$message.success('修改成功')
          this.getRoomList()
        })
      },
      // 改变房间状态
      async changeStatus(row) {
        await editRoomStatus({
          room_id: row.room_id,
          status: row.status
        }).then(() => {
          this.$message.success('修改成功')
          this.getRoomList()
        })
      },
      // 重置数据
      resetData() {
        this.tableData.status = ''
        this.tableData.public = ''
      },
      // 添加房间
      addRoom() {
        this.$emit('componentsChange', 'add')
      },
      // 跳转到设备管理
      async pageToDevice(data) {
        await this.$store.dispatch('store-manage/room-manage/setRoomData', data)
        this.$emit('back', 'device')
      },
      // 房间服务
      async service(data) {
        await this.$store.dispatch('store-manage/room-manage/setRoomData', data)
        this.$emit('back', 'service')
      },
      // 满减管理
      async manJian(data) {
        await this.$store.dispatch('store-manage/room-manage/setRoomData', data)
        this.$emit('back', 'manJian')
      },
      // 编辑房间
      async editRoom(data) {
        await this.$store.dispatch('store-manage/room-manage/setRoomData', data)
        this.$emit('componentsChange', 'edit')
      },
      // 删除房间
      delRoom(room_id) {
        this.$confirm('删除后，该房间所有设备、售货等信息都将清空，您确定要删除该房间吗?', '温馨提示', {
          type: 'warning'
        }).then(async () => {
          await delRoom({
            room_id
          }).then(() => {
            this.$message.success('删除成功')
            this.Mix_tableData.currentPage = 1
            this.getRoomList()
          })
        }).catch(() => {})
      }
    },
    mounted() {
      this.getRoomList()
    }
  }
</script>

<style scoped lang="scss">
  .week-tag {
    &+.week-tag {
      margin-left: 10px;
    }
  }

  .el-dropdown-link {
    cursor: pointer;
    color: #409EFF;
  }

  .light-form {
    .el-form-item {
      margin-bottom: 0;
    }
  }
</style>
