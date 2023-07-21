<template>
  <div class="manJian-manage-index">
    <el-button icon="el-icon-back" @click="back">返回房间列表</el-button>
    <el-button type="primary" icon="el-icon-circle-plus-outline" @click="addManJian">添加满减</el-button>
    <el-button type="warning" icon="el-icon-edit" :disabled="tableData.selectionIds.length !== 1"
      @click="editManJian(tableData.selectionObj[0])">编辑</el-button>
    <el-button type="danger" icon="el-icon-delete" :disabled="!tableData.selectionIds.length"
      @click="delManJian(tableData.selectionIds)">批量删除</el-button>
    <el-tag type="primary">{{ `当前位置：${storeManage.store_name}，${roomManage.room_name}` }}</el-tag>
    <el-table ref="table" :data="tableData.list" border class="mt15" height="calc(100vh - 225px)"
      @selection-change="$selectionChange($event, 'reduce_id')">
      <el-table-column type="selection" width="50"></el-table-column>
      <el-table-column align="center" label="ID" prop="reduce_id" width="80"></el-table-column>
      <el-table-column label="标题" prop="title"></el-table-column>
      <el-table-column label="满" prop="full"></el-table-column>
      <el-table-column label="送" prop="reduce"></el-table-column>
      <el-table-column label="操作" type="action">
        <template slot-scope="scope">
          <el-button type="warning" icon="el-icon-edit" size="mini" @click="editManJian(scope.row)">编辑</el-button>
          <el-button type="danger" icon="el-icon-delete" size="mini" @click="delManJian([scope.row.reduce_id])">删除
          </el-button>
        </template>
      </el-table-column>
    </el-table>
    <Pagination :tableData="tableData" @pageChange="$pageChange" :getList="getManJianList"></Pagination>
    <el-dialog :show-close="false" :title="manJianDialog.title" :visible.sync="manJianDialog.show" width="30%">
      <el-form ref="manJianDialogForm" :model="manJianDialog.form" label-width="90px" :rules="manJianDialog.formRules">
        <el-form-item label="标题" prop="title">
          <el-input placeholder="请输入标题" v-model.trim="manJianDialog.form.title" maxlength="20"></el-input>
        </el-form-item>
        <el-form-item label="满" prop="full">
          <el-input-number v-model="manJianDialog.form.full" :min="1" :max="50000"></el-input-number>
        </el-form-item>
        <el-form-item label="减" prop="reduce">
          <el-input-number v-model="manJianDialog.form.reduce" :min="1" :max="50000"></el-input-number>
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
    mapState
  } from 'vuex'
  import {
    getManJianList,
    addManJian,
    editManJian,
    delManJian
  } from '@/api/store-manage/manJian-manage.js'

  export default {
    name: 'manJian-manage-index',
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
        },
        // 添加/编辑弹窗
        manJianDialog: {
          title: '',
          form: {
            title: '',
            full: '',
            reduce: '',
            reduce_id: ''
          },
          formRules: {
            title: [{
              required: true,
              message: '请输入标题'
            }],
            full: [{
              required: true,
              message: '请输入满足金额'
            }],
            reduce: [{
              required: true,
              message: '请输入优惠金额'
            }]
          },
          show: false
        }
      }
    },
    methods: {
      // 取消弹窗
      hideDialog() {
        this.manJianDialog.show = false
        this.manJianDialog.title = ''
        this.manJianDialog.full = ''
        this.manJianDialog.reduce = ''
        this.manJianDialog.reduce_id = ''
        this.$refs['manJianDialogForm'].resetFields()
      },
      // 弹窗提交
      submitDialog() {
        this.$refs['manJianDialogForm'].validate(async valid => {
          if (valid) {
            if (this.manJianDialog.title === '添加满减') {
              await addManJian({
                ...this.manJianDialog.form,
                room_id: this.roomManage.room_id
              }).then(() => {
                this.$message.success('添加成功')
                this.hideDialog()
                this.tableData.currentPage = 1
                this.getManJianList()
              })
            }
            if (this.manJianDialog.title === '编辑满减') {
              await editManJian(this.manJianDialog.form).then(() => {
                this.$message.success('修改成功')
                this.hideDialog()
                this.tableData.currentPage = 1
                this.getManJianList()
              })
            }
          } else {
            return false
          }
        })
      },
      // 返回上一页
      back() {
        this.$emit('back', 'room')
      },
      // 添加满减
      addManJian() {
        this.manJianDialog.title = '添加满减'
        this.manJianDialog.show = true
      },
      // 编辑满减
      editManJian(data) {
        this.manJianDialog.title = '编辑满减'
        this.manJianDialog.form.title = data.title
        this.manJianDialog.form.full = data.full
        this.manJianDialog.form.reduce = data.reduce
        this.manJianDialog.form.reduce_id = data.reduce_id
        this.manJianDialog.show = true
      },
      // 删除满减
      delManJian(reduce_id) {
        this.$confirm('您确定要删除该满减吗?', '温馨提示', {
          type: 'warning'
        }).then(async () => {
          await delManJian({
            reduce_id
          }).then(() => {
            this.$message.success('删除成功')
            this.tableData.currentPage = 1
            this.getManJianList()
          })
        }).catch(() => {})
      },
      // 获取满减列表
      async getManJianList() {
        await getManJianList({
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
      this.getManJianList()
    }
  }
</script>

<style lang="scss">

</style>
