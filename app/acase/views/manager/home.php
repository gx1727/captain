<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>iview example</title>
    <link rel="stylesheet" type="text/css" href="/static/acase/manager/css/iview.css">
    <script type="text/javascript" src="/static/acase/manager/js/vue.min.js"></script>
    <script type="text/javascript" src="/static/acase/manager/js/iview.min.js"></script>
    <style scoped>
        .layout {
            border: 1px solid #d7dde4;
            background: #f5f7f9;
            position: relative;
            border-radius: 4px;
            overflow: hidden;
        }

        .layout-logo {
            width: 100px;
            height: 30px;
            background: #5b6270;
            border-radius: 3px;
            float: left;
            position: relative;
            top: 15px;
            left: 20px;
        }

        .layout-nav {
            width: 240px;
            margin: 0 auto;
            margin-right: 20px;
        }

        .layout-footer-center {
            text-align: center;
        }
    </style>
</head>
<body>
<div id="app">
    <hello-world></hello-world>
</div>
<script type="text/x-template" id="hello-world-template">
    <div class="layout">
        <Layout>
            <Header>
                <Menu mode="horizontal" theme="dark" active-name="1" @on-select="selectMenu">
                    <div class="layout-logo"></div>
                    <div class="layout-nav">
                        <MenuItem name="1">
                            <Icon type="ios-navigate"></Icon>
                            投票管理
                        </MenuItem>
                        <MenuItem name="2">
                            <Icon type="ios-keypad"></Icon>
                            投票对象
                        </MenuItem>
                    </div>
                </Menu>
            </Header>
            <Content :style="{padding: '0 10px'}">
                <Breadcrumb :style="{margin: '20px 0'}">
                    <BreadcrumbItem>Home</BreadcrumbItem>
                    <BreadcrumbItem>{{ menuTitle }}</BreadcrumbItem>
                    <BreadcrumbItem v-if="viewIndex === 3">编辑</BreadcrumbItem>
                </Breadcrumb>
                <Card v-if="viewIndex === 1">
                    <div style="min-height: 200px;">
                        <Upload
                                multiple
                                type="drag"
                                action="//jsonplaceholder.typicode.com/posts/">
                            <div style="padding: 20px 0">
                                <Icon type="ios-cloud-upload" size="52" style="color: #3399ff"></Icon>
                                <p>Click or drag files here to upload</p>
                            </div>
                        </Upload>
                    </div>
                </Card>
                <Card v-if="viewIndex === 2">
                    <div style="min-height: 200px;">
                        <div class="demo-upload-list" v-for="item in uploadList">
                            <template v-if="item.status === 'finished'">
                                <img :src="item.url">
                                <div class="demo-upload-list-cover">
                                    <Icon type="ios-eye-outline" @click.native="handleView(item.name)"></Icon>
                                    <Icon type="ios-trash-outline" @click.native="handleRemove(item)"></Icon>
                                </div>
                            </template>
                            <template v-else>
                                <Progress v-if="item.showProgress" :percent="item.percentage" hide-info></Progress>
                            </template>
                        </div>
                        <Upload
                                ref="upload"
                                :show-upload-list="false"
                                :default-file-list="defaultList"
                                :on-success="handleSuccess"
                                :format="['jpg','jpeg','png']"
                                :max-size="2048"
                                :on-format-error="handleFormatError"
                                :on-exceeded-size="handleMaxSize"
                                :before-upload="handleBeforeUpload"
                                multiple
                                type="drag"
                                action="//jsonplaceholder.typicode.com/posts/"
                                style="display: inline-block;width:58px;">
                            <div style="width: 58px;height:58px;line-height: 58px;">
                                <Icon type="camera" size="20"></Icon>
                            </div>
                        </Upload>
                        <Modal title="View Image" v-model="visible">
                            <img :src="'https://o5wwk8baw.qnssl.com/' + imgName + '/large'" v-if="visible" style="width: 100%">
                        </Modal>
                    </div>
                </Card>
                <Card v-if="viewIndex === 3">
                    <div style="min-height: 200px;">

                    </div>
                </Card>
            </Content>
            <Footer class="layout-footer-center">2018-2020 &copy; 米七</Footer>
        </Layout>
    </div>
</script>
<script>
    Vue.component('hello-world', {
        template: '#hello-world-template',
        data: function () {
            return {
                viewIndex: 1,
                menuTitle: '投票管理',
                defaultList: [
                    {
                        'name': 'a42bdcc1178e62b4694c830f028db5c0',
                        'url': 'https://o5wwk8baw.qnssl.com/a42bdcc1178e62b4694c830f028db5c0/avatar'
                    },
                    {
                        'name': 'bc7521e033abdd1e92222d733590f104',
                        'url': 'https://o5wwk8baw.qnssl.com/bc7521e033abdd1e92222d733590f104/avatar'
                    }
                ],
                imgName: '',
                visible: false,
                uploadList: []
            }
        },
        methods: {
            selectMenu: function (menuName) {
                if (menuName === '1') {
                    this.menuTitle = '投票管理';
                    this.viewIndex = 1;
                } else if (menuName === '2') {
                    this.menuTitle = '投票对象';
                    this.viewIndex = 2;
                }
            },
            handleView: function (name) {
                this.imgName = name;
                this.visible = true;
            },
            handleRemove: function (file) {
                const fileList = this.$refs.upload.fileList;
                this.$refs.upload.fileList.splice(fileList.indexOf(file), 1);
            },
            handleSuccess: function (res, file) {
                file.url = 'https://o5wwk8baw.qnssl.com/7eb99afb9d5f317c912f08b5212fd69a/avatar';
                file.name = '7eb99afb9d5f317c912f08b5212fd69a';
            },
            handleFormatError: function (file) {
                this.$Notice.warning({
                    title: 'The file format is incorrect',
                    desc: 'File format of ' + file.name + ' is incorrect, please select jpg or png.'
                });
            },
            handleMaxSize: function (file) {
                this.$Notice.warning({
                    title: 'Exceeding file size limit',
                    desc: 'File  ' + file.name + ' is too large, no more than 2M.'
                });
            },
            handleBeforeUpload: function () {
                const check = this.uploadList.length < 5;
                if (!check) {
                    this.$Notice.warning({
                        title: 'Up to five pictures can be uploaded.'
                    });
                }
                return check;
            }
        },
        mounted: function () {
            console.log(this.$refs);
//            this.uploadList = this.$refs.upload.fileList;
        },
        created: function () {
        }
    });

    new Vue({
        el: '#app'
    })

</script>
</body>
</html>