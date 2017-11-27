<template>
    <el-row>
        <el-col :span="24" style="margin: 25px 0">
            <el-button type="primary" icon="plus" @click="addCategory">新增</el-button>
            <el-button type="primary" icon="delete" @click="deleteAll">删除</el-button>
        </el-col>
        <el-col :span="24">
            <el-table
                    ref="categoryTable"
                    :data="categoryData"
                    v-loading="listLoading"
                    stripe
                    border
                    show-overflow-tooltip
                    tooltip-effect="dark"
                    style="width: 100%"
                    @selection-change="handleSelectionChange">
                <el-table-column
                        type="selection"
                        width="55">
                </el-table-column>
                <el-table-column
                        prop="name"
                        label="分类名称"
                        width="120">
                </el-table-column>
                <el-table-column
                        prop="nickname"
                        label="分类别名"
                        width="120">
                </el-table-column>
                <el-table-column
                        label="父级分类"
                        show-overflow-tooltip>
                    <template scope="scope">{{ formatCategory(scope.row.parent) }}</template>
                </el-table-column>
                <el-table-column inline-template :context="_self" label="操作">
                    <span class="fr">
                        <el-button size="small" icon="edit" @click="editCategory(row)"></el-button>
                        <el-button type="danger" size="small" icon="delete" @click="deleteCategory(row)"></el-button>
                    </span>
                </el-table-column>
            </el-table>
        </el-col>
        <el-col :span="24" style="margin-top: 15px">
            <el-pagination
                    @size-change="handleSizeChange"
                    @current-change="handleCurrentChange"
                    :current-page.sync="pagination.currentPage"
                    :page-sizes="pagination.sizes"
                    :page-size="pagination.size"
                    layout="sizes, prev, pager, next"
                    :total="pagination.total">
            </el-pagination>
        </el-col>

        <el-dialog :title="dialogTitle" :visible.sync="dialogFormVisible">
            <el-form :model="categoryForm" :rules="categoryFormRules" ref="categoryForm">
                <el-form-item v-if="showFormData" style="display: none;">
                    <el-input v-model="categoryForm.id"></el-input>
                </el-form-item>
                <el-form-item label="分类名称" label-width="80px" prop="name">
                    <el-input v-model="categoryForm.name" auto-complete="off"></el-input>
                </el-form-item>
                <el-form-item label="分类别名" label-width="80px" prop="nickname">
                    <el-input v-model="categoryForm.nickname" auto-complete="off"></el-input>
                </el-form-item>
                <el-form-item label="分类描述" label-width="80px" prop="description">
                    <el-input type="textarea" v-model="categoryForm.description" auto-complete="off"></el-input>
                </el-form-item>
                <el-form-item label="父级分类" label-width="80px" prop="parent">
                    <el-select v-model="categoryForm.parent" placeholder="请选择父分类">
                        <el-option label="顶级分类" :value="parent_id"></el-option>
                        <el-option v-for="item in categoryData" :label="item.name" :value="item.id"></el-option>
                    </el-select>
                </el-form-item>
            </el-form>
            <div slot="footer" class="dialog-footer" style="text-align: center;">
                <el-button type="primary" @click="submitForm('categoryForm')">确 定</el-button>
                <el-button @click="closeForm('categoryForm')">取 消</el-button>
            </div>
        </el-dialog>
    </el-row>
</template>
<style type="text/css">

</style>
<script type="text/ecmascript-6">

    export default {
        data() {
            return {
                parent_id: 0,
                listLoading: false,
                dialogTitle: '新增',
                dialogFormVisible: false,
                showFormData: false,
                checkAll: [],
                categoryData: [],
                categoryForm: {
                    id: 0,
                    name: '',
                    nickname: '',
                    description: '',
                    parent: 0,
                },
                categoryFormRules: {
                    name: [
                        {required: true, type: 'string', message: '请填写分类名称', trigger: 'blur'}
                    ],
                    nickname: [
                        {required: true, type: 'string', message: '请填写分类别名', trigger: 'blur'},
                        {pattern: /^[a-zA-Z0-9_-]+$/, message: '只允许英文或者拼音,横杠(-),下划线(_)', trigger: 'blur'}
                    ]
                },
                pagination: {
                    total: 10,
                    size: 10,
                    sizes: [10, 20, 50, 100],
                    currentPage: 1
                }
            }
        },
        methods: {
            formatCategory(parent) {
                let format = '';
                switch (parent) {
                    case 0: format = '顶级分类';break;
                    case 1: format = 'PHP';break;
                    case 2: format = 'Javascript';break;
                }
                return format;
            },
            getData() {
                let _this = this;
                this.listLoading = true;

                window.axios.get('/category',{
                    params: {
                        rows: _this.pagination.size,
                        page: _this.pagination.currentPage
                    }
                }).then(function (response) {
                    let data = response.data;

                    if(data.status === 0) {
                        _this.categoryData = data.data.data;
                        _this.pagination.total = data.data.total;
                        _this.pagination.currentPage = data.data.current_page;

                    }else {
                        _this.$message.error('数据获取失败');
                    }
                    _this.listLoading = false;
                }).catch(function (error) {
                    _this.$message.error('数据获取失败');
                })
            },
            handleSelectionChange(val) {
                this.checkAll = val;
            },
            addCategory() {
                this.dialogTitle = '新增';
                this.dialogFormVisible = true;
                this.showFormData = false;
            },
            editCategory(row) {
                let _this = this;
                _this.dialogTitle = '编辑';
                _this.dialogFormVisible = true;
                _this.showFormData = true;

                window.axios.get('/category/' + row.id).then(function (response) {
                    let data = response.data;
                    if(data.status === 0) {
                        _this.categoryForm = data.category;
                    }else {
                        _this.$message.error(data.message);
                    }

                }).catch(function (error) {
                    _this.$message.error('编辑失败');
                });
            },
            deleteCategory(row) {
                let _this = this;
                _this.$confirm('确认删除该分类吗？', {type: 'warning'}).then(() => {
                    window.axios.delete('/category/' + row.id).then(function (response) {
                        let data = response.data;
                        if(data.status === 0) {
                            _this.getData();
                            _this.$message({
                                type: 'success',
                                message: data.message,
                                duration: 1000
                            });
                        }else {
                            _this.$message.error(data.message);
                        }
                    }).catch(function (error) {
                        _this.$message.error(data.message);
                        console.log(error);
                    });
                }).catch(() => {

                });
            },
            deleteAll() {
                let _this = this;
                let Ids = _this.util.getIdByArr(_this.checkAll);

                if(Ids.length <= 0) {
                    _this.$message.error('请选择要删除的数据');
                    return false;
                }

                _this.$confirm('确认删除该记录吗', '提示', {
                    type: 'warning'
                }).then(() => {
                   window.axios.delete('category/destory', {
                       params: {
                           Ids: Ids
                       }
                   }).then(function (response) {
                       let data = response.data;
                       if(data.status === 0) {
                           _this.getData();
                           _this.$message({
                               type: 'success',
                               message: data.message,
                               duration: 1000
                           });
                       }else {
                           _this.$message.error(data.message);
                       }
                   }).catch(function (error) {
                       _this.$message.error(data.message);
                       console.log(error);
                   })
                }).catch(() => {

                });
            },
            submitForm(form) {
              let _this = this;
              _this.$refs[form].validate((valid) => {
                  if (!valid) {
                      console.log('categoryForm valid error.');
                      return false;
                  }

                  if(_this.showFormData) {  // 编辑
                      window.axios.put('/category/update', _this.categoryForm).then(function (response) {
                          let data = response.data;

                          if(data.status === 0) {
                              _this.$message({
                                  message: data.message,
                                  type: 'success',
                                  duration: 1000
                              });
                              _this.closeForm('categoryForm');
                              _this.getData();
                          }else {
                              _this.$message.error(data.message);
                          }
                      }).catch(function (error) {
                          _this.$message.error('更新失败');
                      })
                  }else { // 新增
                      window.axios.post('/category',_this.categoryForm).then(function (response) {
                          let data = response.data;

                          if(data.status === 0) {
                              _this.closeForm('categoryForm');
                              _this.getData();

                              _this.$message({
                                  message: data.message,
                                  type: 'success',
                                  duration: 1000
                              });
                          }else {
                              _this.$message.error(data.message);
                          }
                          _this.dialogFormVisible = false;
                      }).catch(function (error) {
                          _this.$message.error('新增失败');
                      });
                  }
              })
            },
            closeForm(form) {
              this.dialogFormVisible = false;
              this.$refs[form].resetFields();

              this.categoryForm = {
                  id: 0,
                  name: '',
                  nickname: '',
                  description: '',
                  parent: 0,
                }
            },
            handleSizeChange(val) {
                this.pagination.size = val;
                this.getData();
            },
            handleCurrentChange(val) {
                this.pagination.currentPage = val;
                this.getData();
            }
        },
        mounted() {
            this.getData();
        }
    }
</script>