<template>
    <div class="pit-post-form">
        <el-form ref="myForm" :model="myForm" :rules="myRules" v-loading="editFormLoading" label-width="80px"
                 class="pit-common">
            <el-form-item label="标题" prop="title">
                <el-input v-model="myForm.title"></el-input>
            </el-form-item>
            <!--<el-form-item label="路由" prop="route">
                <el-input placeholder="请输入内容" v-model="myForm.route">
                    <template slot="prepend">{{domain}}</template>
                </el-input>
            </el-form-item>-->
            <el-form-item label="标签">
                <el-tag v-for="tag in myForm.tags" type="primary" :closable="true" :close-transition="false"
                        @close="handleClose(tag)"
                >{{tag}}
                </el-tag>
                <el-input class="input-new-tag" v-if="inputVisible" v-model="inputValue" ref="saveTagInput" size="mini"
                          @keyup.enter.native="handleInputConfirm" @blur="handleInputConfirm"></el-input>
                <el-button v-else class="button-new-tag" size="small" @click="showInput">+标签</el-button>
            </el-form-item>
            <el-form-item label="分类" prop="category_id">
                <el-select v-model="myForm.category_id" placeholder="请选择分类">
                    <el-option v-for="item in categories" :label="item.name" :value="item.id"></el-option>
                </el-select>
            </el-form-item>
            <el-form-item label="内容" prop="markdown">
                <el-input type="textarea" ref="myMarkdown" :autosize="{ minRows: 12}"
                          :rows="textareaRow" v-model="myForm.markdown"></el-input>
            </el-form-item>
            <el-form-item>
                <el-button @click="closeForm('myForm')">取 消</el-button>
                <el-button @click="clearCache()">清缓存</el-button>
                <el-button type="primary" @click="submitMyForm('myForm')">确 定</el-button>
            </el-form-item>
        </el-form>
        <div class="pit-previews pit-common">
            <article class="markdown-previews markdown-body" v-html="markdownPreviews"></article>
        </div>
    </div>
</template>
<style type="text/css">
    .pit-common {
        margin: 20px;
        width: 60%;
        min-width: 800px;
    }

    .pit-previews .markdown-previews {
        border: #ccc 1px dashed;
        margin-left: 80px;
        background: #faf5eb;
        padding: 10px;
        color: #000;
    }

    .el-tag {
        margin-left: 10px;
        background-color: #8391a5;
        display: inline-block;
        padding: 0 5px;
        height: 24px;
        line-height: 22px;
        font-size: 12px;
        color: #fff;
        border-radius: 4px;
        box-sizing: border-box;
        border: 1px solid transparent;
        white-space: nowrap;
    }

    .input-new-tag {
        width: 78px;
        margin-left: 10px;
    }

    .button-new- tag {
        margin-left: 10px;
        height: 24px;
        line-height: 22px;
        padding-top: 0;
        padding-bottom: 0;
    }
    .showPreview img {
        max-width: 100%;
    }

    .avatar-uploader .el-upload {
        border: 1px dashed #d9d9d9;
        border-radius: 6px;
        cursor: pointer;
        position: relative;
        overflow: hidden;
    }
    .avatar-uploader .el-upload:hover {
        border-color: #20a0ff;
    }
    .avatar-uploader-icon {
        font-size: 28px;
        color: #8c939d;
        width: 350px;
        height: 150px;
        line-height: 150px;
        text-align: center;
    }
    .post-cover {
        width: 350px;
        height: 150px;
        display: block;
    }
</style>
<script type="text/ecmascript-6">
    export default{
        data(){
            return {
                editFormLoading: false,
                inputVisible: false,
                inputValue:'',
//                domain: '',
                showTagsInput: '',
                categories: [],
                textareaRow: 15,
                markdownPreviews:'',
                myForm: {
                    id: 0,
                    title: '',
//                    route: '',
                    tags: [],
                    content: '',
                    category_id: 0,
                    markdown: ''
                },
                myRules: {
                    title: [
                        {required: true, type: "string", message: '请填写文章标题', trigger: 'blur'}
                    ],
                    flag: [
                        {required: true, type: "string", message: '请填写文章别名', trigger: 'blur'},
                        {pattern: /^[a-zA-Z0-9_-]+$/, message: '只允许英文或者拼音,横杠(-),下划线(_)', trigger: 'blur'}
                    ],
                    category_id: [
                        {required: true, type: "integer", message: '请选择分类', trigger: 'blur'}
                    ],
                    markdown: [
                        {required: true, type: "string", message: '文章内容不能为空', trigger: 'blur'}
                    ]
                },

            }
        },
        created () {
        },
        methods: {
            submitMyForm: function (myForm) {
                let _this = this;
                _this.$refs[myForm].validate((valid) => {
                    if (!valid){
                        console.log('myForm valid error.');
                        return false;
                    }

                    if (_this.myForm.id > 0) {
                        window.axios.put('/posts/update', _this.myForm).then(function (response) {
                            let res = response.data;
                            _this.$message({
                                message: res.status === 0 ? '编辑成功' : '编辑失败',
                                type: res.status
                            });
                            if (res.status === 0) {
                                _this.closeForm('myForm');
                            }
                        }).catch(function (error) {
                            console.log(error);
                        });
                    } else {
                        window.axios.post('/posts', _this.myForm).then(function (response) {
                            let res = response.data;
                            if (res.status === 0) {
//                                _this.closeForm('myForm');
                            }
                            _this.$message({
                                message: res.status === 0 ? '新增成功' : '新增失败',
                                type: 'success'
                            });
                        }).catch(function (error) {
                            if (error.response) {
                                if (error.response.status === 422) {
                                    for (let index in error.response.data) {
                                        _this.$notify({
                                            title: '警告',
                                            message: error.response.data[index][0],
                                            type: 'warning'
                                        });
                                    }
                                }
                            } else {
                                console.log(error);
                            }
                        });
                    }
                });
            },
            closeForm: function (myForm) {
                this.localforage.removeItem('myFormMarkdown').then(function () {
                    console.log('Key is cleared!');
                }).catch(function (err) {
                    console.log(err);
                });
                this.$refs[myForm].resetFields();
                this.$router.replace('/posts');
                console.log('closeForm');
            },
            getCategories() {
                let _this = this;
                window.axios.get('/category').then(function (response) {
                    let data = response.data;
                    if(data.status === 0) {
                        // 设置顶级匪类
                        data.data.unshift({id: 0, name: '顶级分类', hidden: true, parent: 0});
                        _this.categories = data.data;
                    }
                }).catch(function (error) {
                    
                })
            },
            handleClose(tag) {
                this.myForm.tags.splice(this.myForm.tags.indexOf(tag), 1);
            },

            showInput() {
                this.inputVisible = true;
                this.$nextTick(_ => {
                    this.$refs.saveTagInput.$refs.input.focus();
                });
            },

            handleInputConfirm() {
                let inputValue = this.inputValue;
                if (inputValue) {
                    this.myForm.tags.push(inputValue);
                }
                this.inputVisible = false;
                this.inputValue = '';
            },
            compileMarkdown: function () {
                this.markdownPreviews = this.marked(this.myForm.markdown);
            },
            setDomain: function () {
                let location = window.location;
                this.domain = location.protocol + '//' + location.host + location.pathname;
            },
            setMarkdown: function () {
                let _this = this;
                this.localforage.getItem('myFormMarkdown').then(function (value) {
                    if (value !== '' && value !== null) {
                        _this.myForm.markdown = JSON.parse(value);
                    }
                }).catch(function (err) {
                    console.log(err);
                });
            },
        },
        computed: {
            compiledMarkdown: function () {
                return marked(this.input, { sanitize: true });
                let _this = this;
                _this.compileMarkdown();
                let markdown2json = JSON.stringify(_this.myForm.markdown);
                this.localforage.setItem('myFormMarkdown', markdown2json).then(function (value) {
                    //console.log(value);
                }).catch(function (err) {
                    console.log(err);
                });
            }
        },
        watch: {
            'myForm.markdown': {
                handler: function (curVar, oldVar) {
                    let _this = this;
                    _this.compileMarkdown();
                    let markdown2json = JSON.stringify(_this.myForm.markdown);
                    this.localforage.setItem('myFormMarkdown', markdown2json).then(function (value) {
                        //console.log(value);
                    }).catch(function (err) {
                        console.log(err);
                    });
                }
            },
            '$route'(to, from) {//监听路由改变
                if (this.$route.params.id === undefined) {
                    this.$refs['myForm'].resetFields();
//                    this.fileList = [];
                }
            }
        },
        mounted() {
            this.getCategories();
//            this.setDomain();
            this.setMarkdown();
        }
    }
</script>
