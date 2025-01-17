<template>
  <div class="device-manage">
    <Index v-if="type === 'index'" @back="back" @componentsChange="componentsChange"></Index>
    <Add v-if="type === 'add'" @componentsChange="componentsChange"></Add>
    <Edit v-if="type === 'edit'" @componentsChange="componentsChange"></Edit>
  </div>
</template>

<script>
import Index from './device-manage-index'
import Add from './device-manage-add'
import Edit from './device-manage-edit'

export default {
  name: "device-manage",
  components: {
    Index,
    Add,
    Edit
  },
  data() {
    return {
      type: 'index'
    }
  },
  methods: {
    // 返回到门店列表
    back(e) {
      this.$emit('componentsChange', e)
    },
    // 组件改变
    componentsChange(e) {
      this.type = e
    }
  }
}
</script>

<style scoped>

</style>
