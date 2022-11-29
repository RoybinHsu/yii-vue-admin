<template>
  <div class="app-container">
    <el-button type="primary" @click="test1">测试1</el-button>
    <el-button type="success" @click="test2" v-permission="['/test/test2']">测试2</el-button>
  </div>
</template>

<script>
import { testIndex, testTest2 } from '@/api/example'
import permission from '@/directive/permission/index'
import { mapGetters } from 'vuex'

export default {
  name: 'TestIndex',
  directives: { permission },
  computed: {
    ...mapGetters([
      'permissions'
    ])
  },
  methods: {
    test1() {
      testIndex().then(res => {
        console.log(res)
      }).catch(err => {
        console.error(err)
      })
    },
    test2() {
      testTest2().then(res => {
        console.log(res)
      }).catch(err => {
        console.error(err)
      })
    }
  }
}
</script>

<style scoped>

</style>
