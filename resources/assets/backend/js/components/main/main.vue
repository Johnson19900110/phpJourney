<template>
    <div class="posts-content">
        <div class="posts-action-btn">
            <router-link to="/article/add">
                <el-button type="primary" icon="plus">新增</el-button>
            </router-link>
            <el-button type="primary" @click="handleDistory('multi',{})" icon="delete">删除</el-button>
            <el-select v-model="category_id" clearable @change="filterCategory" placeholder="请选择">
                <el-option
                        v-for="item in categories"
                        :label="item.name"
                        :value="item.id">
                </el-option>
            </el-select>
            <el-input v-model="q" placeholder="请输入内容" icon="search" style="width: 200px" :on-icon-click="searchBtn"></el-input>
        </div>

        <template>
            <el-table :data="listData" v-loading="listLoading" style="width: 100%"
                      @selection-change="handleSelectionChange">
                <el-table-column type="selection" width="55"></el-table-column>
                <el-table-column sortable label="标题" min-width="500">
                    <template scope="scope">
                        <router-link :to="{ path: '/article/edit/'+ scope.row.id}" class="links">{{ scope.row.title }}</router-link>
                    </template>
                </el-table-column>
                <el-table-column label="分类" width="200">
                    <template scope="scope">
                        <template v-for="category in categories">
                            <span v-if="category.id == scope.row.category_id">{{ category.name}}</span>
                        </template>
                    </template>
                </el-table-column>
                <el-table-column sortable label="标签" width="400">
                    <template scope="scope">
                        <el-tag v-for="tag in scope.row.tags" type="primary">
                            {{tag.tags_name}}
                        </el-tag>
                    </template>
                </el-table-column>
                <el-table-column prop="created_at" sortable label="日期"
                                 width="250"></el-table-column>
                <el-table-column inline-template :context="_self" label="操作" width="150">
                    <span class="fr">
                        <router-link :to="{ path: '/article/edit/'+ row.id}">
                            <el-button size="small" icon="edit"></el-button>
                        </router-link>
                        <el-button type="danger" size="small" icon="delete" @click="handleDistory('one',row)"></el-button>
                    </span>
                </el-table-column>
            </el-table>
        </template>

        <el-pagination
                @size-change="handleSizeChange"
                @current-change="handleCurrentChange"
                :current-page="currentPage"
                :page-sizes="[10, 20, 50, 100, 200]"
                :page-size="pageSize"
                layout="sizes, prev, pager, next"
                :total="total">
        </el-pagination>

    </div>
</template>
<style type="text/css">
    .posts-action-btn {
        margin: 15px 0;
    }
    .links {
        text-decoration: none;
        color: #1f2d3d;
    }
</style>
<script type="text/ecmascript-6">
    export default{
        data(){
            return {
                listData: [],
                category_id: '',
                categories: [],
                currentPage: 1,
                total: 0,
                pageSize: 10,
                listLoading: false,
                checkedAll: [],
                q: ''
            }
        },
        methods: {
            filterCategory: function (value) {
                this.getData();
            },
            searchBtn: function (event) {
                this.getData();
            },
            getData: function () {
                let _this = this;
                _this.listLoading = true;
                let query = {
                    rows: _this.pageSize,
                    page: _this.currentPage,
                    category_id: _this.category_id,
                    q: _this.q
                };

                //console.log(query);
                window.axios.get('/posts', { params: query }).then(function (response) {
                    let res = response.data;
                    if (res.status === 0) {
                        let data = res.data;
                        _this.listData = data.data;
                        _this.total = data.total;
                    } else {
                        _this.$message({
                            message: '数据获取失败',
                            type: 'error',
                            duration: 3 * 1000
                        });
                    }
                    _this.listLoading = false;
                }).catch(function (error) {
                    console.log(error);
                });
            },
            handleSizeChange(val) {
                //console.log(`每页 ${val} 条`);
                this.pageSize = val;
                this.getData();
            },
            handleCurrentChange(val) {
                this.currentPage = val;
                this.getData();
                //console.log(`当前页: ${val}`);
            },
            handleDistory: function (type, row) {
                let _this = this, idsParam = {};
                switch (type) {
                    case 'one':
                        let id = parseInt(row.id);
                        if ( id<= 0) {
                            _this.$message({
                                message: '请选择需要删除的数据',
                                type: 'warning'
                            });
                            return false;
                        }
                        idsParam = {ids: [id]};
                        break;
                    case 'multi':
                        var ids = _this.util.getIdByArr(_this.checkedAll);
                        if (ids.length <= 0) {
                            _this.$message({
                                message: '请选择需要删除的数据',
                                type: 'warning'
                            });
                            return false;
                        }
                        idsParam = {ids: ids};
                        break;
                    default:
                        break;
                }

                _this.$confirm('确认删除该记录吗?', '提示', {
                    //type: 'warning'
                }).then(() => {
                    _this.listLoading = true;
                    window.axios.delete('/posts/destroy', { data: idsParam }).then(function (response) {
                        let res = response.data;
                        if(res.status === 0 ) {
                            if (type === 'one') {
                                _this.util.removeByValue(_this.listData, row.id);
                            } else {
                                for (var index in _this.checkedAll) {
                                    _this.util.removeByValue(_this.listData, _this.checkedAll[index].id);
                                }
                            }
                        }
                        _this.$message({
                            message: res.message,
                            type: res.status === 0 ? 'success' : 'error'
                        });
                        _this.listLoading = false;
                    }).catch(function (error) {
                        console.log(error);
                    });
                }).catch(() => {
                    _this.listLoading = false;
                });
            },
            handleSelectionChange(val) {
                this.checkedAll = val;
            },
            getCategories: function () {
                let _this = this;
                window.axios.get('/category',{
                    params: {
                        rows: 999
                    }
                }).then(function (response) {
                    let res = response.data;
                    if (res.status === 0) {
                        let data = res.data.data;
                        data.splice(0, 0, {id: 0, name: '顶级分类', hidden: true, parent: 0});
                        _this.categories = data;
                    } else {
                        _this.$message({
                            message: '数据获取失败',
                            type: 'error',
                        });
                    }
                }).catch(function (error) {
                    console.log(error);
                });
            }
        },
        mounted() {
            this.getCategories();
            this.getData();
        }
    }
</script>
