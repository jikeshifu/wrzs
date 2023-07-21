<template>
  <div class="device-manage-index">
    <el-button icon="el-icon-back" @click="back">返回房间列表</el-button>
    <el-button icon="el-icon-circle-plus-outline" type="primary" @click="addDevice">添加设备</el-button>
    <el-button :disabled="!Mix_tableData.selectionIds.length" icon="el-icon-delete" type="danger"
      @click="delDevice(Mix_tableData.selectionIds)">批量删除
    </el-button>
    <el-input v-model="tableData.public" clearable maxlength="30" placeholder="设备名称/序列号/网关SN" style="width:250px">
    </el-input>
    <el-select v-model="tableData.device_type" clearable placeholder="请选择设备类型">
      <el-option label="门锁" value="1"></el-option>
      <el-option label="大门" value="2"></el-option>
      <el-option label="空开" value="3"></el-option>
    </el-select>
    <el-button icon="el-icon-search" type="primary" @click="searchSubmit">查询</el-button>
    <el-button icon="el-icon-refresh-right" type="primary" @click="resetData">重置</el-button>
    <el-tag type="primary">{{ `当前位置：${storeManage.store_name}，${roomManage.room_name}` }}</el-tag>
    <el-table ref="table" :data="Mix_tableData.list" border class="mt15" height="calc(100vh - 225px)"
      @selection-change="$selectionChange($event, 'device_id')">
      <el-table-column type="selection" width="50"></el-table-column>
      <el-table-column align="center" label="ID" prop="device_id" width="80"></el-table-column>
      <el-table-column label="设备名称" prop="device_name"></el-table-column>
      <el-table-column label="设备类型">
        <template slot-scope="scope">
          <el-tag type="primary">{{ scope.row.device_type | numTransZh_deviceType }}</el-tag>
        </template>
      </el-table-column>
      <el-table-column label="设备序列号" prop="device_sn"></el-table-column>
      <el-table-column label="二维码" prop="qr_img" align="center">
        <template slot-scope="scope">
          <el-image :preview-src-list="[baseUrl + scope.row.qr_img]" style="width:100px;height:100px"
            :src="baseUrl + scope.row.qr_img" fit="cover"></el-image>
        </template>
      </el-table-column>
      <el-table-column label="网关SN" prop="gw_sn"></el-table-column>
      <el-table-column :filter-method="filterOnline" :filters="[{ text: '在线', value: 1 }, { text: '离线', value: 0 }]"
        filter-placement="bottom-end" label="状态" prop="on_line">
        <template slot-scope="scope">
          <el-tag v-if="scope.row.on_line === 1" type="primary">在线</el-tag>
          <el-tag v-if="scope.row.on_line === 0" type="danger">离线</el-tag>
        </template>
      </el-table-column>
      <el-table-column label="语音播报文本" prop="voice"></el-table-column>
      <el-table-column label="语音音量" prop="volume"></el-table-column>
      <el-table-column label="设备二维码">
        <el-table-column label="自动更新" prop="qr_status">
          <template slot-scope="scope">
            <el-row align="middle" justify="space-between" type="flex">
              <el-col>
                <el-switch :active-value="1" :inactive-value="0" :value="scope.row.qr_status"></el-switch>
              </el-col>
              <el-col>
                <el-button icon="el-icon-edit" size="mini" type="text" @click="editDeviceEwm(scope.row)">编辑</el-button>
              </el-col>
            </el-row>
          </template>
        </el-table-column>
        <el-table-column label="开始更新时间" prop="qr_time"></el-table-column>
      </el-table-column>
      <el-table-column align="center" label="操作" type="action">
        <template slot-scope="scope">
          <el-dropdown>
            <span class="el-dropdown-link">
              操作<i class="el-icon-arrow-down el-icon--right"></i>
            </span>
            <el-dropdown-menu slot="dropdown">
              <el-dropdown-item v-if="scope.row.device_type == 1">
                <el-button icon="el-icon-bank-card" size="mini" type="primary" @click="dcardManage(scope.row)">房卡管理
                </el-button>
              </el-dropdown-item>
              <el-dropdown-item v-if="scope.row.device_type == 2">
                <el-button icon="el-icon-bank-card" size="mini" type="primary" @click="dcardManage(scope.row)">门卡管理
                </el-button>
              </el-dropdown-item>
              <el-dropdown-item>
                <el-button icon="el-icon-edit" size="mini" type="warning" @click="editDevice(scope.row)">编辑
                </el-button>
              </el-dropdown-item>
              <el-dropdown-item>
                <el-button icon="el-icon-delete" size="mini" type="danger" @click="delDevice([scope.row.device_id])">删除
                </el-button>
              </el-dropdown-item>
            </el-dropdown-menu>
          </el-dropdown>
        </template>
      </el-table-column>
    </el-table>
    <Pagination :tableData="Mix_tableData" @pageChange="$pageChange" :getList="getDeviceList"></Pagination>
    <el-dialog :visible.sync="ewmDialog.show" title="设备二维码设置" width="30%">
      <el-form ref="ewmDialog" :model="ewmDialog.data" :rules="ewmDialog.rules" label-position="left"
        label-width="80px">
        <el-form-item label="设备名称">{{ ewmDialog.data.device_name }}</el-form-item>
        <el-form-item label="序列号">{{ ewmDialog.data.device_sn }}</el-form-item>
        <el-form-item label="设备类型">
          <el-tag type="primary">{{ ewmDialog.data.device_type | numTransZh_deviceType }}</el-tag>
        </el-form-item>
        <el-form-item label="自动更新">
          <el-switch v-model="ewmDialog.data.qr_status" :active-value="1" :inactive-value="0"></el-switch>
        </el-form-item>
        <el-form-item v-if="ewmDialog.data.qr_status" label="更新时间" prop="qr_time">
          <el-time-select v-model="ewmDialog.data.qr_time" :picker-options="{
    start: '00:00',
    step: '01:00',
    end: '24:00'
  }" placeholder="选择更新时间">
          </el-time-select>
        </el-form-item>
      </el-form>
      <span slot="footer">
        <el-button @click="ewmDialog.show = false">取消</el-button>
        <el-button type="primary"
          @click="submitEwmSet">确定</el-button>
      </span>
    </el-dialog>
  </div>
</template>

<script>
  import {
    mapState
  } from "vuex"
  import {
    getDeviceList,
    delDevice,
    editDeviceEwm
  } from "@/api/store-manage/device-manage"

  export default {
    name: "device-manage-index",
    computed: {
      ...mapState({
        storeManage: state => state['store-manage/store-manage'].storeManage,
        roomManage: state => state['store-manage/room-manage'].roomManage
      })
    },
    data() {
      return {
        tableData: {
          public: '',
          device_type: ''
        },
        // 二维码弹窗
        ewmDialog: {
          show: false,
          data: {},
          rules: {
            qr_time: [{
              required: true,
              message: '请选择更新时间'
            }]
          }
        }
      }
    },
    methods: {
      // 门卡管理
      async dcardManage(data) {
        await this.$store.dispatch('store-manage/dcard-manage/setDcardManageData', data)
        this.$emit('back', 'dcard')
      },
      // 提交二维码设置
      submitEwmSet() {
        this.$refs['ewmDialog'].validate(async valid => {
          if (valid) {
            await editDeviceEwm(this.ewmDialog.data).then(() => {
              this.$message.success('修改成功')
              this.ewmDialog.show = false
              this.Mix_tableData.currentPage = 1
              this.getDeviceList()
            })
          } else {
            return false
          }
        })
      },
      // 编辑设备二维码
      editDeviceEwm(e) {
        this.ewmDialog.data = JSON.parse(JSON.stringify(e))
        this.ewmDialog.show = true
      },
      // 编辑设备信息
      async editDevice(data) {
        await this.$store.dispatch('store-manage/device-manage/setDeviceManageData', data)
        this.$emit('componentsChange', 'edit')
      },
      // 返回上一页
      back() {
        this.$emit('back', 'room')
      },
      // 获取设备列表
      async getDeviceList() {
        this.$refs.table.clearFilter()
        await getDeviceList({
          page: this.Mix_tableData.currentPage,
          limit: this.Mix_tableData.pageSize,
          public: this.tableData.public,
          room_id: this.roomManage.room_id,
          device_type: this.tableData.device_type
        }).then(data => {
          this.Mix_tableData.list = data.list
          this.Mix_tableData.total = data.count
        })
      },
      // 筛选在线/离线
      filterOnline(value, row) {
        return row.on_line === value
      },
      // 添加设备
      addDevice() {
        this.$emit('componentsChange', 'add')
      },
      // 删除设备
      delDevice(device_id) {
        this.$confirm('您确定要删除该设备吗?', '温馨提示', {
          type: 'warning'
        }).then(async () => {
          await delDevice({
            device_id
          }).then(() => {
            this.$message.success('删除成功')
            this.Mix_tableData.currentPage = 1
            this.getDeviceList()
          })
        }).catch(() => {})
      },
      // 查询
      searchSubmit() {
        this.Mix_tableData.currentPage = 1
        this.getDeviceList()
      },
      // 重置数据
      resetData() {
        this.tableData.public = ''
        this.tableData.device_type = ''
      }
    },
    mounted() {
      this.getDeviceList()
    }
  }
</script>

<style lang="scss" scoped>
  .el-dropdown-link {
    cursor: pointer;
    color: #409EFF;
  }
</style>
