<template>
  <div class="lattice-manage">
    <Index v-if="type === 'index'" @back="back" @componentsChange="componentsChange"></Index>
    <Add v-if="type === 'add'" @componentsChange="componentsChange"></Add>
    <Edit v-if="type === 'edit'" @componentsChange="componentsChange"></Edit>
  </div>
</template>

<script>
import Index from "./lattice-manage-index"
import Add from "./lattice-manage-add"
import Edit from "./lattice-manage-edit"

export default {
  name: "lattice-manage",
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
    // 返回最顶页
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
