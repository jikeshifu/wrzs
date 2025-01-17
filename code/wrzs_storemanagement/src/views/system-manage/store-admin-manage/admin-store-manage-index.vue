<template>
  <div class="admin-store-manage-index">
    <el-button type="primary" icon="el-icon-circle-plus-outline" @click="addStoreAdm">添加门店管理员</el-button>
    <el-button type="warning" icon="el-icon-edit" :disabled="Mix_tableData.selectionIds.length !== 1" @click="editStoreAdm(Mix_tableData.selectionObj[0])">编辑</el-button>
    <el-button type="danger" icon="el-icon-delete" :disabled="!Mix_tableData.selectionIds.length" @click="delStoreAdm(Mix_tableData.selectionIds)">批量删除</el-button>
    <el-select v-model="tableData.store_id" placeholder="选择门店" filterable clearable>
      <el-option
        v-for="item in storeList"
        :key="item.store_id"
        :label="item.store_name"
        :value="item.store_id">
      </el-option>
    </el-select>
    <el-input style="width:200px" placeholder="姓名" v-model="tableData.user_name" maxlength="20"></el-input>
    <el-input style="width:200px" placeholder="手机号" v-model="tableData.mobile" maxlength="11"></el-input>
    <el-button type="primary" icon="el-icon-search" @click="submitSearch">查询</el-button>
    <el-button type="primary" icon="el-icon-refresh-right" @click="resetData">重置</el-button>
    <el-table class="mt15" border height="calc(100vh - 225px)" :data="Mix_tableData.list" @selection-change="$selectionChange($event, 'store_admin_id')">
      <el-table-column type="selection" width="50" align="center"></el-table-column>
      <el-table-column label="ID" prop="store_admin_id"></el-table-column>
      <el-table-column label="姓名" prop="user_name"></el-table-column>
      <el-table-column label="手机号" prop="mobile"></el-table-column>
      <el-table-column label="短信通知">
        <template slot-scope="scope">
          <el-switch
            v-model="scope.row.sms_status"
            active-color="#13ce66"
            inactive-color="#c9c9c9"
            :active-value="1"
            :inactive-value="0"
            @change="smsChange(scope.row)"
          >
          </el-switch>
        </template>
      </el-table-column>
      <el-table-column label="状态">
        <template slot-scope="scope">
          <el-switch
            v-model="scope.row.status"
            active-color="#13ce66"
            inactive-color="#c9c9c9"
            :active-value="1"
            :inactive-value="0"
            @change="statusChange(scope.row)"
          >
          </el-switch>
        </template>
      </el-table-column>
      <el-table-column label="操作" type="action" width="300">
        <template slot-scope="scope">
          <el-button type="primary" icon="el-icon-refresh" size="mini" @click="editPwd(scope.row.store_admin_id)">修改密码</el-button>
          <el-button type="warning" icon="el-icon-edit" size="mini" @click="editStoreAdm(scope.row)">编辑</el-button>
          <el-button type="danger" icon="el-icon-delete" size="mini" @click="delStoreAdm([scope.row.store_admin_id])">删除</el-button>
        </template>
      </el-table-column>
    </el-table>
    <Pagination :tableData="Mix_tableData" @pageChange="$pageChange" :getList="getStoreAdminList"></Pagination>
    <el-dialog
      title="修改密码"
      :visible.sync="editPwdDialog.show"
      width="40%">
      <el-form ref="editPwdDialog" :model="editPwdDialog.form" :rules="editPwdDialog.formRules" label-width="100px">
        <el-form-item label="新密码" prop="pw">
          <el-input v-model="editPwdDialog.form.pw" type="password" maxlength="30" placeholder="请输入新密码"></el-input>
        </el-form-item>
      </el-form>
      <span slot="footer" class="dialog-footer">
        <el-button @click="hideEditPwdDialog">取消</el-button>
        <el-button type="primary" @click="submitEditPwdDialog">确定</el-button>
      </span>
    </el-dialog>
  </div>
</template>

<script>
import {getStoreAdminList, delStoreAdmin, smsEdit, editStoreAdmStatus, editPwd} from "@/api/system-manage/store-admin-manage"
import {getStoreList} from "@/api/store-manage/store-manage";

export default {
  name: "admin-store-manage-index",
  data() {
    return {
      storeList: [],
      tableData: {
        store_id: '',
        user_name: '',
        mobile: ''
      },
      // 修改密码弹窗
      editPwdDialog: {
        show: false,
        form: {
          pw: '',
          store_admin_id: ''
        },
        formRules: {
          pw: [{
            required: true,
            message: '请输入密码'
          }, {
            min: 6,
            message: '密码至少输入6位'
          }]
        }
      }
    }
  },
  methods: {
    // 查找
    submitSearch() {
      this.Mix_tableData.currentPage = 1
      this.getStoreAdminList()
    },
    // 重置数据
    resetData() {
      this.tableData.store_id = ''
      this.tableData.user_name = ''
      this.tableData.mobile = ''
    },
    // 提交修改密码
    submitEditPwdDialog() {
      this.$refs['editPwdDialog'].validate(async valid => {
        if (valid) {
          await editPwd(this.editPwdDialog.form).then(() => {
            this.$message.success('修改成功')
            this.$refs['editPwdDialog'].resetFields()
            this.editPwdDialog.show = false
          })
        } else {
          return false
        }
      })
    },
    // 隐藏弹窗
    hideEditPwdDialog() {
      this.editPwdDialog.show = false
      this.$refs['editPwdDialog'].resetFields()
    },
    // 门店管理员状态改变
    async statusChange(item) {
      await editStoreAdmStatus({
        store_admin_id: item.store_admin_id,
        status: item.status
      }).then(() => {
        this.$message.success('修改成功')
        this.getStoreAdminList()
      })
    },
    // 短信通知改变
    async smsChange(item) {
      await smsEdit({
        store_admin_id: item.store_admin_id,
        sms_status: item.sms_status
      }).then(() => {
        this.$message.success('修改成功')
        this.getStoreAdminList()
      })
    },
    // 修改密码
    editPwd(store_admin_id) {
      this.editPwdDialog.show = true
      this.editPwdDialog.form.store_admin_id = store_admin_id
    },
    // 添加管理员
    addStoreAdm() {
      this.$emit('componentsChange', 'add')
    },
    // 编辑管理员
    async editStoreAdm(data) {
      await this.$store.dispatch('system-manage/store-admin-manage/setStoreAdminData', data)
      this.$emit('componentsChange', 'edit')
    },
    // 删除门店管理员
    delStoreAdm(store_admin_id) {
      this.$confirm('您确定要删除该管理员吗?', '温馨提示', {
        type: 'warning'
      }).then(async () => {
        await delStoreAdmin({
          store_admin_id
        }).then(() => {
          this.$message.success('删除成功')
          this.Mix_tableData.currentPage = 1
          this.getStoreAdminList()
        })
      }).catch(() => {})
    },
    // 获取门店管理员列表
    async getStoreAdminList() {
      await getStoreAdminList({
        page: this.Mix_tableData.currentPage,
        limit: this.Mix_tableData.pageSize,
        store_id: this.tableData.store_id,
        user_name: this.tableData.user_name,
        mobile: this.tableData.mobile
      }).then((data) => {
        this.Mix_tableData.list = data.list
        this.Mix_tableData.total = data.count
      })
    },
    // 获取门店列表
    async getStoreList() {
      await getStoreList().then((data) => {
        this.storeList = data.list
      })
    }
  },
  mounted() {
    this.getStoreAdminList()
    this.getStoreList()
  }
}
</script>

<style scoped>

</style>
