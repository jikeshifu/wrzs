<template>
  <div class="sell-drawer-manage">
    <Index v-if="type === 'index'" @componentsChange="componentsChange"></Index>
    <Add v-if="type === 'add'" @componentsChange="componentsChange"></Add>
    <Edit v-if="type === 'edit'" @componentsChange="componentsChange"></Edit>
    <LatticeManage v-if="type === 'lattice'" @componentsChange="componentsChange"></LatticeManage>
  </div>
</template>

<script>
import Index from './sell-drawer-manage-index'
import Add from './sell-drawer-manage-add'
import Edit from './sell-drawer-manage-edit'
import LatticeManage from './lattice-manage/lattice-manage'

export default {
  name: "sell-drawer-manage",
  components: {
    Index,
    Add,
    Edit,
    LatticeManage
  },
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
  }
}
</script>

<style scoped>

</style>
