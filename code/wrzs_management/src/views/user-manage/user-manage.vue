<template>
  <div class="user-manage">
    <Index v-if="type === 'index'" @componentsChange="componentsChange"></Index>
  </div>
</template>

<script>
import Index from './user-manage-index'

export default {
  name: "device",
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
