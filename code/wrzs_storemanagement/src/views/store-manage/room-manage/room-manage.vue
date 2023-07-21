<template>
  <div class="room-manage">
    <Index v-if="type === 'index'" @back="back" @componentsChange="componentsChange"></Index>
    <Add v-if="type === 'add'" @componentsChange="componentsChange"></Add>
    <Edit v-if="type === 'edit'" @componentsChange="componentsChange"></Edit>
  </div>
</template>

<script>
import Index from './room-manage-index'
import Add from './room-manage-add'
import Edit from './room-manage-edit'

export default {
  name: "room-manage",
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
