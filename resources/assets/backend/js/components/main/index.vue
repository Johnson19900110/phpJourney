<template>
    <el-row class="panel">
        <el-col :span="24" class="panel-top">
            <el-col :span="21">
                <span style="margin-left: 30px">
                    <img src="/backend/images/kobe.png" alt="" width="47px" height="64px">
                    <i style="font-weight: 900;font-size: 26px;">PhpJourney</i>
                </span>
            </el-col>
            <el-col :span="3">
                <el-dropdown>
                  <span class="el-dropdown-link" style="color: #ffffff;font-size: 16px">
                    {{ sysUserName }}<i class="el-icon-caret-bottom el-icon--right"></i>
                  </span>
                    <el-dropdown-menu slot="dropdown" style="color: #000">
                        <el-dropdown-item @click.native="userSetting">用户设置</el-dropdown-item>
                        <el-dropdown-item divided @click.native="logout">退出登录</el-dropdown-item>
                    </el-dropdown-menu>
                </el-dropdown>
            </el-col>
        </el-col>
        <el-col :span="24" class="panel-center">
            <aside class="panel-menu">
                <el-menu :default-active="currentPath" class="el-menu-vertical-demo" theme="dark" @open="handleOpen" @close="handleClose" unique-opened router>
                    <div v-for="(item, index) in $router.options.routes" v-if="!item.hidden">
                        <el-menu-item v-if="item.leaf && item.children.length>0" :index="item.children[0].path">
                            <i :class="item.iconCls"></i>{{ item.children[0].name }}
                        </el-menu-item>
                        <el-submenu :index="index+''" v-if="!item.leaf">
                            <template slot="title"><i :class="item.iconCls"></i>{{ item.name }}</template>
                            <el-menu-item v-for="child in item.children" :index="child.path">{{ child.name }}</el-menu-item>
                        </el-submenu>
                    </div>
                </el-menu>
            </aside>
            <section class="panel-content">
                <el-col :span="24">
                    <router-view></router-view>
                </el-col>
            </section>
        </el-col>
    </el-row>
</template>

<script type="text/ecmascript-6">
    export default{
        data() {
            return {
                currentPath: '/index',
                currentPathName: '',
                currentPathNameParent: '首页',
                sysUserName: 'Johnson',
            }
        },
        methods: {
            handleOpen() {
                //console.log('handleopen');
            },
            handleClose() {
                //console.log('handleclose');
            },
            logout() {
                let _this = this;
                let _duration = 1000;
                _this.$confirm('确认退出吗？', '提示', {type: 'warning'}).then( () => {
                    window.axios.post('auth/logout').then( function (response) {
                        let data = response.data;
                        if(!data.status) {
                            sessionStorage.removeItem('php_journey');
                            console.log(data.message);
                            _this.$message({
                                message: data.message,
                                type: 'success',
                                duration: _duration
                            });
                            setTimeout(function () {
                                _this.$router.push({path: '/login'});
                            }, _duration);
                        }
                    }).catch(function (error) {
                        console.log(error);
                        _this.$message.error("退出失败");
                    })
                }).catch(() => {
                    console.log('cancel')
                })
            },
            userSetting() {
                this.$router.push({path: 'user'});
            }
        },
        mounted() {
            console.log(this.$route);
        }
    }
</script>

<style type="text/css">
    .panel {
        position: absolute;
        top: 0;
        bottom: 0;
        width: 100%;
    }
    .panel-top {
        height: 64px;
        background-color: #1F2D3D;
        line-height: 64px;
    }
    .panel-center {
        position: absolute;
        top: 64px;
        bottom: 0;
        overflow: hidden;
        background-color: #324057;
    }
    .panel-center .panel-menu {
        width: 230px;

    }

    .el-menu .fa {
        vertical-align: baseline;
        margin-right: 10px;
        font-size: 18px;
    }
    .panel-center .panel-content {
        background-color: #f1f2f7;
        position: absolute;
        top: 0;
        left: 230px;
        bottom: 0;
        right: 0;
        overflow-y: scroll;
        padding: 20px;
    }
</style>