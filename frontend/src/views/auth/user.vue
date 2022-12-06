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
        @click="addUser('添加用户')"
        icon="el-icon-plus">添加用户
      </el-button>
    </search-box>
    <section class="hidden-sm-and-down">
      <el-row>
        <el-col :span="24">
          <el-table
            v-loading="loading"
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
              prop="status_desc">
            </el-table-column>
            <el-table-column
              align="center"
              label="创建时间"
              prop="created_at">
            </el-table-column>
            <el-table-column
              align="center"
              label="操作"
              width="250px"
            >
              <template v-slot="scope">
                <el-button-group>
                  <el-button
                    type="primary"
                    plain
                    size="mini"
                    round
                    icon="el-icon-edit-outline"
                    @click="addUser('编辑用户', scope.row)">编辑
                  </el-button>
                  <el-button
                    type="danger"
                    plain
                    size="mini"
                    round
                    icon="el-icon-delete"
                    @click="del(scope.row)">删除
                  </el-button>
                  <el-button
                    type="success"
                    plain
                    size="mini"
                    round
                    icon="el-icon-link"
                    @click="assign(scope.row)">分配
                  </el-button>
                </el-button-group>
              </template>
            </el-table-column>
          </el-table>
        </el-col>
      </el-row>
      <el-row>
        <el-col :span="24" class="pagination-box ">
          <el-pagination
            @current-change="pageChange"
            background
            :page-size="searchModel.limit"
            :current-page="searchModel.page"
            layout="prev, pager, next"
            :total="userTotal">
          </el-pagination>
        </el-col>
      </el-row>
    </section>
    <section class="hidden-md-and-up">
      <min-table :data="userList" :load-more="minLoad" table="menuTable" :loading="loading">
        <min-table-column
          label="ID"
          prop="id"
          slot="id"
          slot-scope="scope"
          :row="scope.row"></min-table-column>
        <min-table-column
          label="用户名"
          prop="username"
          slot="username"
          slot-scope="scope"
          :row="scope.row"></min-table-column>
        <min-table-column
          label="手机号"
          prop="phone"
          slot="phone"
          slot-scope="scope"
          :row="scope.row"></min-table-column>
        <min-table-column
          label="邮箱"
          prop="email"
          slot="email"
          slot-scope="scope"
          :row="scope.row"></min-table-column>
        <min-table-column
          label="状态"
          prop="status_desc"
          slot="status_desc"
          slot-scope="scope"
          :row="scope.row"></min-table-column>
        <min-table-column
          label="创建时间"
          prop="created_at"
          slot="created_at"
          slot-scope="scope"
          :row="scope.row">
        </min-table-column>
        <min-table-column
          label="操作"
          prop="append"
          slot="append"
          slot-scope="scope"
          :row="scope.row">
          <template>
            <el-button-group>
              <el-button
                type="primary"
                plain
                size="mini"
                round
                icon="el-icon-edit-outline"
                @click="addUser('编辑用户', scope.row)">编辑
              </el-button>
              <el-button
                type="danger"
                plain
                size="mini"
                round
                icon="el-icon-delete"
                @click="del(scope.row)">删除
              </el-button>
              <el-button
                type="success"
                plain
                size="mini"
                round
                icon="el-icon-link"
                @click="assign(scope.row)">分配
              </el-button>
            </el-button-group>
          </template>
        </min-table-column>
      </min-table>
    </section>
    <el-dialog :title="addUserModalTitle" :visible.sync="addUserModal" :close-on-click-modal="false">
      <el-form ref="addUserForm" :model="addUserForm" :rules="addUserRules" label-width="120px">
        <el-form-item label="用户名称:" prop="username">
          <el-input
            ref="username"
            v-model="addUserForm.username"
            tabindex="1"
            autocomplete="off"
          ></el-input>
        </el-form-item>
        <el-form-item label="电话号码:" prop="phone">
          <el-input
            ref="phone"
            v-model="addUserForm.phone"
            autocomplete="off"
            tabindex="2"
          ></el-input>
        </el-form-item>
        <el-form-item label="邮箱:" prop="email">
          <el-input
            ref="email"
            v-model="addUserForm.email"
            autocomplete="off"></el-input>
        </el-form-item>
        <el-form-item label="密码:" prop="password">
          <el-input
            type="password"
            ref="password"
            autocomplete="off"
            v-model="addUserForm.password"></el-input>
        </el-form-item>
        <el-form-item label="确认密码:" prop="password_confirm">
          <el-input
            type="password"
            ref="password_confirm"
            autocomplete="off"
            v-model="addUserForm.password_confirm"></el-input>
        </el-form-item>
      </el-form>
      <div slot="footer" class="dialog-footer">
        <el-button @click="addUserModal = false">取 消</el-button>
        <el-button type="primary" @click="onSubmitAddUser">确 定</el-button>
      </div>
    </el-dialog>
  </div>
</template>

<script>
import SearchBox from '@/components/SearchBox/SearchBox'
import { addUser, userList } from '@/api/auth'
import MinTable from '@/components/MinTable/Table'
import MinTableColumn from '@/components/MinTable/Column'

export default {
  name: 'AuthUser',
  components: { SearchBox, MinTable, MinTableColumn },
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
        page: 1,
        limit: 20,
        username: '',
        phone: '',
        email: ''
      },
      defaultSearchModel: {},
      userTotal: 0,
      loading: false,
      noMore: false,
      loadMoreBtn: true,
      userList: [],
      addUserModal: false,
      addUserModalTitle: '',
      addUserRules: {
        username: [{ required: true, trigger: 'blur', message: '缺少用户名' }],
        phone: [{ required: true, trigger: 'blur', message: '缺少手机号码' }],
        email: [{ required: true, trigger: 'blur', message: '缺少邮箱地址' }],
        password: [{ required: true, trigger: 'blur', message: '缺少密码' }],
        password_confirm: [{ required: true, trigger: 'blur', message: '缺少确认密码' }]
      },
      addUserForm: {
        id: '',
        username: '',
        phone: '',
        email: '',
        password: '',
        password_confirm: ''
      },
      defaultAddUserForm: {}
    }
  },
  watch: {
    addUserModal(value) {
      if (!value) {
        // 隐藏dialog
        console.log(this.defaultAddUserForm)
        this.addUserForm = Object.assign({}, this.defaultAddUserForm)
      }
    },
    searchModel(val) {
      console.log(val)
    }
  },
  created() {
    this.defaultAddUserForm = Object.assign({}, this.addUserForm)
    this.defaultSearchModel = Object.assign({}, this.searchModel)
    this.search()
  },
  methods: {
    search() {
      this.loading = true
      userList(this.searchModel).then(res => {
        if (res.data.data.length === 0 || res.data.data.length < this.searchModel.limit) {
          this.noMore = true
        } else {
          this.loadMoreBtn = true
        }
        this.userList = res.data.data
        this.userTotal = res.data.total
      }).catch(err => {
        console.error(err)
      }).finally(() => {
        this.loading = false
      })
    },
    reset() {
      this.searchModel = this.defaultSearchModel
      this.search()
    },
    addUser(title, row = undefined) {
      this.addUserModalTitle = title
      this.addUserModal = !this.addUserModal
      if (row === undefined) {
        this.addUserForm.id = ''
      } else {
        this.addUserForm = Object.assign(this.addUserForm, row)
      }
    },
    onSubmitAddUser() {
      this.$refs.addUserForm.validate(valid => {
        if (valid) {
          // 校验成功
          addUser(this.addUserForm).then(res => {
            if (res.code === 200) {
              this.addUserModal = false
              this.search()
            }
          }).catch(err => {
            console.error(err)
          })
        }
      })
    },
    pageChange(page) {
      this.searchModel.page = page
      this.search()
    },
    assign(row) {
      this.$router.push({
        name: 'SiteAuthAssignUser',
        query: { type: 'user', name: row.username, uid: row.id, description: row.description }
      })
    },
    del(row) {
      console.log(row)
    },
    minLoad() {
      this.searchModel.page += 1
      this.search()
    }
  }
}
</script>

<style scoped>

</style>
