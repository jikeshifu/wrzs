<template>
  <div class="lattice-manage-edit">
    <el-row type="flex" justify="center">
      <el-col :span="24">
        <el-form ref="form" :model="form" :rules="formRules" label-width="100px">
          <el-form-item label="当前售货柜">
            <el-tag type="primary">{{ sellDrawerManage.cabinet_name }}</el-tag>
          </el-form-item>
          <el-form-item label="商品格编号" prop="cabinet_number">
            <el-input placeholder="请输入商品格编号" maxlength="25" v-model="form.cabinet_number"></el-input>
          </el-form-item>
          <el-form-item label="商品格标题" prop="goods_name">
            <el-input placeholder="请输入商品格标题" maxlength="25" v-model="form.goods_name"></el-input>
          </el-form-item>
          <el-form-item label="商品价格" prop="goods_price">
            <el-input-number v-model="form.goods_price" :precision="2" :min="0" :max="1000000"></el-input-number>
            <el-tag type="primary">元</el-tag>
          </el-form-item>
          <el-form-item label="商品格主图" prop="goods_image">
            <el-upload
              :action="uploadURL"
              list-type="picture-card"
              :limit="1"
              :file-list="fileList"
              :on-remove="removeUpload"
              :on-success="uploadSuccess">
              <i class="el-icon-plus"></i>
            </el-upload>
            <el-tag type="warning">仅限上传一张</el-tag>
          </el-form-item>
          <el-form-item label="商品介绍">
            <tinymce ref="tinymce" v-model="form.goods_about" :height="300" />
          </el-form-item>
          <el-form-item>
            <el-button @click="back">返回商品格列表</el-button>
            <el-button type="primary" @click="submit">立即修改</el-button>
          </el-form-item>
        </el-form>
      </el-col>
    </el-row>
  </div>
</template>

<script>
import {mapState} from 'vuex'
import Tinymce from '@/components/Tinymce'
import {editLattice} from "@/api/sell-drawer-manage/lattice-manage"

export default {
  name: "lattice-manage-edit",
  components: {
    Tinymce
  },
  computed: {
    ...mapState({
      sellDrawerManage: state => state['sell-drawer-manage/sell-drawer-manage'].sellDrawerManage,
      latticeManage: state => state['sell-drawer-manage/lattice-manage'].latticeManage
    })
  },
  data() {
    // 验证空
    const validateEmpty = (rule, value, callback) => {
      if (!('' + value).trim()) {
        if (rule.field === 'cabinet_number') {
          callback(new Error('请输入商品格编号'))
        }
        if (rule.field === 'goods_name') {
          callback(new Error('请输入商品格标题'))
        }
      }
      callback()
    }
    return {
      form: {},
      formRules: {
        cabinet_number: [{
          required: true,
          message: '请输入商品格编号'
        }, {
          validator: validateEmpty
        }],
        goods_name: [{
          required: true,
          message: '请输入商品格标题'
        }, {
          validator: validateEmpty
        }],
        goods_price: [{
          required: true,
          message: '请输入商品价格'
        }]
      },
      fileList: []
    }
  },
  methods: {
    // 返回
    back() {
      this.$emit('componentsChange', 'index')
    },
    // 上传格子主图成功的钩子
    uploadSuccess(res) {
      this.form.goods_image = res.data
    },
    // 删除上传格子的主图
    removeUpload() {
      this.form.goods_image = ''
    },
    // 提交
    submit() {
      this.$refs['form'].validate(async valid => {
        if (valid) {
          await editLattice({
            ...this.form,
            cabinet_id: this.sellDrawerManage.cabinet_id
          }).then(() => {
            this.$message.success('修改成功')
            this.back()
          })
        } else {
          return false
        }
      })
    }
  },
  mounted() {
    this.form = this.latticeManage
    this.fileList = [{
      name: '',
      url: this.baseUrl + this.form.goods_image
    }]
  }
}
</script>

<style scoped>

</style>
