<template>
  <div class="shgsp-order-manage">
    <Index v-if="type === 'index'" @componentsChange="componentsChange"></Index>
  </div>
</template>

<script>
import Index from './shgsp-order-manage-index.vue'

export default {
  name: "shgsp-order-manage",
  components: {
    Index
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
