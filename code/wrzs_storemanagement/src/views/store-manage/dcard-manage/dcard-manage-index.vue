<template>
  <div class="dcard-manage-index">
    <el-button icon="el-icon-back" @click="back">返回设备列表</el-button>
    <el-button icon="el-icon-circle-plus-outline" type="primary" @click="addDcard">添加门卡</el-button>
    <el-button :disabled="!Mix_tableData.selectionIds.length" icon="el-icon-delete" type="danger"
               @click="delDcard(Mix_tableData.selectionIds)">批量删除
    </el-button>
    <el-input v-model="tableData.card_sn" clearable maxlength="30" placeholder="门卡序列号"
              style="width:250px" @keypress.native.enter="searchSubmit" @clear="searchSubmit"></el-input>
    <el-button icon="el-icon-search" type="primary" @click="searchSubmit">查询</el-button>
    <el-tag type="primary">{{ `当前位置：${storeManage.store_name}，${roomManage.room_name}，${ dcardManage.device_name }` }}</el-tag>
    <el-table ref="table" :data="Mix_tableData.list" border class="mt15" height="calc(100vh - 225px)"
              @selection-change="$selectionChange($event, 'card_id')">
      <el-table-column type="selection" width="50"></el-table-column>
      <el-table-column align="center" label="ID" prop="card_id" width="80"></el-table-column>
      <el-table-column label="门卡序列号" prop="card_sn"></el-table-column>
      <el-table-column label="过期时间">
        <template slot-scope="scope">
          {{ $dateFormatter(scope.row.end_time + '000', 'yy-mm-dd hh:mm:ss') }}
        </template>
      </el-table-column>
      <el-table-column align="center" label="操作" type="action">
        <template slot-scope="scope">
          <el-button size="mini" type="danger" icon="el-icon-delete" @click="delDcard([scope.row.card_id])">删除</el-button>
        </template>
      </el-table-column>
    </el-table>
    <Pagination :tableData="Mix_tableData" @pageChange="$pageChange" :getList="getDcardList"></Pagination>
    <el-dialog
      title="添加门卡"
      :visible.sync="addDcardDialog.show"
      width="30%">
      <el-form ref="addDcardDialog" :model="addDcardDialog.form" :rules="addDcardDialog.formRules" label-width="100px">
        <el-form-item label="门卡序列号" prop="card_sn">
          <el-input placeholder="请输入门卡序列号" v-model.trim="addDcardDialog.form.card_sn" maxlength="50"></el-input>
        </el-form-item>
        <el-form-item label="结束时间" prop="end_time">
          <el-date-picker
            v-model="addDcardDialog.form.end_time"
            type="datetime"
            :picker-options="pickerOptions"
            placeholder="选择过期时间">
          </el-date-picker>
        </el-form-item>
      </el-form>
      <span slot="footer" class="dialog-footer">
        <el-button @click="hideDialog">取消</el-button>
        <el-button type="primary" @click="submitAdd">确定</el-button>
      </span>
    </el-dialog>
  </div>
</template>

<script>
import {mapState} from "vuex"
import {getDcardList, addDcard, delDcard} from "@/api/store-manage/dcard-manage"

export default {
  name: "dcard-manage-index",
  computed: {
    ...mapState({
      storeManage: state => state['store-manage/store-manage'].storeManage,
      roomManage: state => state['store-manage/room-manage'].roomManage,
      dcardManage: state => state['store-manage/dcard-manage'].dcardManage
    })
  },
  data() {
    return {
      addDcardDialog: {
        show: false,
        form: {
          card_sn: '',
          end_time: ''
        },
        formRules: {
          card_sn: [{
            required: true,
            message: '请输入门卡序列号'
          }],
          end_time: [{
            required: true,
            message: '请选择门卡过期时间'
          }]
        }
      },
      pickerOptions: {
        disabledDate(time) {
          return time.getTime() < Date.now()
        }
      },
      tableData: {
        card_sn: ''
      },
    }
  },
  methods: {
    // 取消弹窗
    hideDialog() {
      this.addDcardDialog.show = false
      this.$refs['addDcardDialog'].resetFields()
    },
    // 添加提交
    submitAdd() {
      this.$refs['addDcardDialog'].validate(async valid => {
        if (valid) {
          await addDcard({
            device_id: this.dcardManage.device_id,
            card_sn: this.addDcardDialog.form.card_sn,
            end_time: new Date(this.addDcardDialog.form.end_time).getTime().toString().slice(0, 10)
          }).then(() => {
            this.$message.success('添加成功')
            this.addDcardDialog.show = false
            this.searchSubmit()
            this.$refs['addDcardDialog'].resetFields()
          })
        } else {
          return false
        }
      })
    },
    // 查询提交
    searchSubmit() {
      this.Mix_tableData.currentPage = 1
      this.getDcardList()
    },
    // 添加门卡
    addDcard() {
      this.addDcardDialog.show = true
    },
    // 删除门卡
    delDcard(card_id) {
      this.$confirm('您确定要删除该门卡吗?', '温馨提示', {
        type: 'warning'
      }).then(async () => {
        await delDcard({
          card_id
        }).then(() => {
          this.$message.success('删除成功')
          this.Mix_tableData.currentPage = 1
          this.getDcardList()
        })
      }).catch(() => {})
    },
    // 返回上一页
    back() {
      this.$emit('back', 'device')
    },
    // 获取门卡列表
    async getDcardList() {
      await getDcardList({
        page: this.Mix_tableData.currentPage,
        limit: this.Mix_tableData.pageSize,
        device_id: this.dcardManage.device_id,
        card_sn: this.tableData.card_sn
      }).then(data => {
        this.Mix_tableData.list = data.list
        this.Mix_tableData.total = data.count
      })
    }
  },
  mounted() {
    this.getDcardList()
  }
}
</script>

<style scoped>

</style>
