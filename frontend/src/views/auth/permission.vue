<template>
  <div class="app-container">
    <search-box
      :filters="filters"
      :model="searchModel"
      :row-span="6"
      label-width="100px"
      @search="search"
      @reset="reset"
    >
      <el-button
        slot="buttonGroup"
        type="primary"
        size="small"
        plain
        @click="add('创建权限')"
        icon="el-icon-plus">创建权限
      </el-button>
    </search-box>
    <el-row>
      <el-col :span="24">
        <el-table
          v-loading="loading"
          border
          size="mini"
          default-expand-all
          :data="list"
          style="width: 100%"
          align="center"
        >
          <el-table-column
            align="center"
            label="权限名称"
            prop="name">
          </el-table-column>
          <el-table-column
            align="center"
            label="描述"
            prop="description">
          </el-table-column>
          <el-table-column
            align="center"
            label="操作"
            width="300px"
          >
            <template v-slot="scope">
              <el-button-group>
                <el-button
                  type="primary"
                  plain
                  size="mini"
                  round
                  icon="el-icon-edit-outline"
                  @click="edit(scope.row)">编辑
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
    <el-dialog :title="addModalTitle" :visible.sync="addModal" :close-on-click-modal="false">
      <el-form ref="addForm" :model="addForm" :rules="addFormRules" label-width="120px">
        <el-form-item label="名称:" prop="name">
          <el-input
            ref="name"
            v-model="addForm.name"
            tabindex="1"
          ></el-input>
        </el-form-item>
        <el-form-item label="描述:" prop="description">
          <el-input
            ref="description"
            v-model="addForm.description"></el-input>
        </el-form-item>
      </el-form>
      <div slot="footer" class="dialog-footer">
        <el-button @click="addModal = false">取 消</el-button>
        <el-button type="primary" @click="onSubmit">确 定</el-button>
      </div>
    </el-dialog>
  </div>
</template>

<script>
import SearchBox from '@/components/SearchBox/SearchBox'
import { permissionAdd, permissionDel, permissionIndex } from '@/api/auth'

export default {
  name: 'SiteAuthPermission',
  components: { SearchBox },
  data() {
    return {
      filters: [
        {
          type: 'input',
          name: 'name',
          placeholder: '',
          labelText: '名称:',
          options: []
        }
      ],
      searchModel: {},
      list: [],
      availableData: [],
      assigned: [],
      loading: false,
      addModalTitle: '创建权限',
      addModal: false,
      addForm: {
        name: '',
        ruleName: '',
        description: '',
        data: '',
        items: []
      },
      addFormDefault: null,
      addFormRules: {
        name: [{ required: true, trigger: 'blur', message: '缺少权限名称' }]
      }
    }
  },
  created() {
    this.addFormDefault = Object.assign({}, this.addForm)
    this.search()
  },
  watch: {
    addModal(val) {
      if (!val) {
        this.addForm = Object.assign({}, this.addFormDefault)
      }
    }
  },
  methods: {
    search() {
      this.loading = true
      permissionIndex().then(res => {
        this.list = res.data
      }).catch(err => {
        console.error(err)
      }).finally(() => {
        this.loading = false
      })
    },
    reset() {
      console.log('reset')
    },
    add(t) {
      this.addModalTitle = t
      this.addModal = true
    },
    keywords(query) {
      console.log(query)
    },
    onSubmit() {
      this.$refs.addForm.validate(valid => {
        if (valid) {
          // 校验成功
          permissionAdd(this.addForm).then(res => {
            if (res.code === 200) {
              this.addModal = false
              this.search()
            }
          }).catch(err => {
            console.error(err)
          })
        }
      })
    },
    edit(row) {
      this.addForm = Object.assign(this.addForm, row)
      this.addModal = true
    },
    del(row) {
      this.$alert('请确认要删除吗?', '提示', {
        confirmButtonText: '确定',
        type: 'warning'
      }).then(res => {
        permissionDel({ name: row.name }).then(res => {
          this.search()
        }).catch(err => {
          console.error(err)
        })
      }).catch(err => {
        console.error(err)
      })
    },
    assign(row) {
      this.$router.push({
        name: 'SiteAuthAssignRouter',
        query: { type: 'permission', name: row.name, description: row.description }
      })
    },
    test(row) {
      this.$router.push({ name: 'SiteAuthAssignRole', query: { type: 'role', name: row.name } })
    }
  }
}
</script>

<style scoped>

</style>
