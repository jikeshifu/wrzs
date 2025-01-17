<template>
  <div class="adv-manage-add">
    <el-button icon="el-icon-back" @click="back">返回</el-button>
    <el-row type="flex" align="center" justify="center">
      <el-col :span="12">
        <el-form :model="form" ref="form" :rules="formRules" label-width="120px">
          <el-form-item prop="src" label="图片">
            <el-upload name="file" :action="uploadURL" :on-remove="uploadRemove" accept=".jpg,.jpeg,.png" :on-success="uploadSuccess" :limit="1">
              <el-button size="small" type="primary">点击上传</el-button>
              <div slot="tip" class="el-upload__tip">只能上传 jpg/png 文件，尺寸为 1202*299</div>
            </el-upload>
          </el-form-item>
          <el-form-item prop="status" label="状态">
            <el-switch v-model="form.status" :active-value="1" :inactive-value="0"></el-switch>
          </el-form-item>
          <el-form-item prop="content" label="广告内容">
            <tinymce ref="tinymce" v-model="form.content" :height="300" />
          </el-form-item>
          <el-form-item>
            <el-button icon="el-icon-back" @click="back">返回</el-button>
            <el-button type="primary" @click="submit" icon="el-icon-check">确定</el-button>
          </el-form-item>
        </el-form>
      </el-col>
    </el-row>
  </div>
</template>

<script>
  import {
    addAdv
  } from '@/api/adv-manage.js'
  import Tinymce from '@/components/Tinymce'

  export default {
    components: {
      Tinymce
    },
    data() {
      return {
        form: {
          src: '',
          status: 1,
          content: ''
        },
        formRules: {
          src: [{
            required: true,
            message: '请上传图片'
          }],
          content: [{
            required: true,
            message: '请输入内容'
          }]
        }
      }
    },
    methods: {
      // 移除上传的钩子
      uploadRemove(file, fileList) {
        this.form.src = ''
      },
      // 上传成功的钩子
      uploadSuccess(response, file, fileList) {
        this.form.src = response.data
      },
      back() {
        this.$emit('changeComponents', 'index')
      },
      submit() {
        this.$refs['form'].validate(async valid => {
          if (valid) {
            await addAdv(this.form).then(() => {
              this.$message.success('添加成功')
              this.back()
            })
          } else {
            return false
          }
        })
      }
    }
  }
</script>

<style lang="scsss" scoped>

</style>
