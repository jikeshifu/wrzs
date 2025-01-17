<template>
  <div class="admin-store-manage">
    <Index v-if="type === 'index'" @componentsChange="componentsChange"></Index>
    <Add v-if="type === 'add'" @componentsChange="componentsChange"></Add>
    <Edit v-if="type === 'edit'" @componentsChange="componentsChange"></Edit>
  </div>
</template>

<script>
import Index from './admin-store-manage-index'
import Edit from './admin-store-manage-edit'
import Add from './admin-store-manage-add'

export default {
  name: "admin-store-manage",
  data() {
    return {
      type: 'index'
    }
  },
  methods: {
    // 组件改变
    componentsChange(e) {
      this.type = e
    }
  },
  components: {
    Index,
    Edit,
    Add
  }
}
</script>

<style scoped>

</style>
