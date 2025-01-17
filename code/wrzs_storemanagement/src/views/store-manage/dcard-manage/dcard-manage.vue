<template>
  <div class="dcard-manage">
    <Index v-if="type === 'index'" @back="back" @componentsChange="componentsChange"></Index>
  </div>
</template>

<script>
import Index from './dcard-manage-index'

export default {
  name: "dcard-manage",
  components: {
    Index
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
