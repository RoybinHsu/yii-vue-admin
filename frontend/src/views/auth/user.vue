<template>
  <div class="app-container">
    <search-box
      :filters="filters"
      :model="searchModel"
      :row-span="6"
      label-width="80px"
      @search="search"
      @reset="reset"
    >
     <el-button
       slot="buttonGroup"
       type="primary"
       size="small"
       plain
       @click="addUser"
       icon="el-icon-plus">添加用户</el-button>
    </search-box>
    <el-row>
      <el-col :span="24">
        <el-table
          border
          size="mini"
          default-expand-all
          :data="userList"
          style="width: 100%"
          align="center"
        >
          <el-table-column
            align="center"
            label="ID"
            width="90px"
            prop="id">
          </el-table-column>
          <el-table-column
            align="center"
            label="用户名"
            prop="username">
          </el-table-column>
          <el-table-column
            align="center"
            label="手机号"
            prop="phone">
          </el-table-column>
          <el-table-column
            align="center"
            label="邮箱"
            prop="email">
          </el-table-column>
          <el-table-column
            align="center"
            label="状态"
            prop="status">
          </el-table-column>
          <el-table-column
            align="center"
            label="创建时间"
            prop="created_at">
          </el-table-column>
          <el-table-column
            align="center"
            label="操作"
          >
            <template v-slot="scope">
              <el-button type="danger" plain size="mini" round @click="deleteUser(scope.row)"><i class="el-icon-delete"></i>删除</el-button>
            </template>
          </el-table-column>
        </el-table>
      </el-col>
    </el-row>
    <el-dialog title="收货地址" :visible.sync="addUserModal" :close-on-click-modal="false">
      <el-form :model="addUserForm">
        <el-form-item label="活动名称" label-width="120px">
          <el-input v-model="addUserForm.username" autocomplete="off"></el-input>
        </el-form-item>
      </el-form>
      <div slot="footer" class="dialog-footer">
        <el-button @click="addUserModal = false">取 消</el-button>
        <el-button type="primary" @click="addUserModal = false">确 定</el-button>
      </div>
    </el-dialog>
  </div>
</template>

<script>
import SearchBox from '@/components/SearchBox/SearchBox'

export default {
  name: 'AuthUser',
  components: { SearchBox },
  data() {
    return {
      filters: [
        {
          type: 'input',
          name: 'username',
          placeholder: '',
          labelText: '用户名称:',
          options: []
        },
        {
          type: 'input',
          name: 'phone',
          placeholder: '',
          labelText: '手机号:',
          options: []
        },
        {
          type: 'input',
          name: 'email',
          placeholder: '',
          labelText: '邮箱:',
          options: []
        }
      ],
      searchModel: {
        username: '',
        phone: '',
        email: ''
      },
      userList: [
        { id: 1, username: '徐顺斌', phone: '12313', email: 'groot@qq.com', status: 10, created_at: '2022-11-24 15:09:19' },
        { id: 2, username: '徐顺斌', phone: '12313', email: 'groot@qq.com', status: 10, created_at: '2022-11-24 15:09:19' }
      ],
      addUserModal: false,
      addUserForm: {
        username: '',
        phone: '',
        email: '',
        password: '',
        password_confirm: ''
      }
    }
  },
  methods: {
    search() {
      console.log('search')
    },
    reset() {
      console.log('reset')
    },
    deleteUser(row) {
      console.log(row)
    },
    addUser() {
      this.addUserModal = !this.addUserModal
    }
  }
}
</script>

<style scoped>

</style>
