
require('./bootstrap');

window.Vue = require('vue');

import Api from './api.js';

window.api = new Api();

import Auth from './auth.js';
window.auth = new Auth();
import Vue from 'vue'
import axios from 'axios';
import VueAxios from 'vue-axios';
import VueRouter from 'vue-router';
import MaskedInput from 'vue-masked-input'
window.Event = new Vue;

Vue.use(VueRouter);
Vue.use(VueAxios, axios);

Vue.use(MaskedInput);
Vue.component('masked-input', MaskedInput);

import VueCarousel from '@chenfengyuan/vue-carousel';

Vue.component(VueCarousel.name, VueCarousel);


import vPagination from 'vue-plain-pagination'
Vue.component('v-pagination', vPagination);

Vue.component('example-component', require('./components/ExampleComponent.vue').default);
Vue.component('app', require('./components/App.vue').default);
Vue.component('Loading_line', require('./components/board/loading_line.vue').default);
Vue.component('modal', require('./components/board/ModalBlockList').default);


import VueForceNextTick from 'vue-force-next-tick'
Vue.use(VueForceNextTick)


import App from './components/App'
import MainView from './components/MainView'
import Register from './components/Auth/Register'
import Contacts from './components/other/contacts'
import Rules from './components/other/rules'
import Login from './components/Auth/Login'
import ResetPassword from './components/Auth/ResetPassword'
import Add_advertisement from './components/board/add_advertisement'
import Add_advertisement1 from './components/board/add_advertisement1'
import Add_advertisement2 from './components/board/add_advertisement2'
import Add_advertisement3 from './components/board/add_advertisement3'
import Loading_line from './components/board/loading_line'
import modal from './components/board/ModalBlockList'
import OnePost from './components/Posts/OnePost'
import MainPosts from './components/Posts/MainPosts'
import NotFound from './components/other/404'
import Profile from './components/profile/Profile'
import UserPosts from './components/profile/UserPosts'
import Thregister from './components/Auth/Thregister'
import RegisterConfirm from './components/Auth/RegisterConfirm'
import PasswordReset from './components/Auth/PasswordReset'
import ChangeMobile from './components/profile/ChangeMobile'
import Chat from './components/profile/Chat'
import VkLogin from './components/Auth/VkLogin'
import MainViewAdmin from './components/Admin/MainViewAdmin'
import AdminMain from './components/Admin/AdminMain'
import UsersAdmin from './components/Admin/UsersAdmin'
import PostsAdmin from './components/Admin/PostsAdmin'
import Ban from './components/Auth/Ban'


import Vuex from 'vuex';
Vue.use(Vuex);
import {store} from './store';

axios.defaults.baseURL = 'http://localhost:/api';

const router = new VueRouter({

    mode: 'history',
    routes: [

        {
            path: '/',
            name: 'main',
            component: MainView,
            children: [

                {
                    path: '/',
                    name: 'MainPosts',
                    component: MainPosts
                },
                {
                    path: '/login',
                    name: 'login',
                    component: Login,
                    beforeEnter: (to, from, next) => {

                        if(auth.user)
                        {
                            next({ path: '/' })

                        }
                        else
                        {
                            next({  })
                        }

                    },

                },
                {
                    path: '/register',
                    name: 'register',
                    component: Register,
                    beforeEnter: (to, from, next) => {

                        if(auth.user)
                        {
                            next({ path: '/' })

                        }
                        else
                        {
                            next({  })
                        }

                    },
                },
                {
                    path: '/thregister',
                    name: 'thregister',
                    component: Thregister,
                },
                {
                    path: '/register_confirm/:id/:token',
                    name: 'register_confirm',
                    component: RegisterConfirm,
                },
                {
                    path: '/authvk',
                    name: 'VkLogin',
                    component: VkLogin,
                },
                {
                    path: '/reset_password',
                    name: 'reset_password',
                    component: ResetPassword,

                },
                {
                    path: '/contacts',
                    name: 'contacts',
                    component: Contacts,

                },
                {
                    path: '/ban',
                    name: 'ban',
                    component: Ban,
                    beforeEnter: (to, from, next) => {

                        if(auth.user)
                        {
                            axios
                                .post('/is_ban', {
                                })
                                .then(({data}) => {
                                    if (data.status == 'fail') {
                                        next({})
                                    }
                                    else
                                    {
                                        next({ path: '/'})
                                    }
                                })
                        }
                        else
                        {
                            next({ path: '/login' })
                        }

                    },
                },
                {
                    path: '/profile',
                    name: 'profile',
                    component: Profile,
                    beforeEnter: (to, from, next) => {

                            if(auth.user)
                            {
                                axios
                                    .post('/is_ban', {
                                    })
                                    .then(({data}) => {
                                        if (data.status == 'fail') {
                                            next({ path: '/ban' })
                                        }
                                        else
                                        {
                                            next({ })
                                        }
                                    })
                            }
                            else
                            {
                                next({ path: '/login' })
                            }

                    },
                },
                {
                    path: '/password_reset',
                    name: 'password_reset',
                    component: PasswordReset,
                    beforeEnter: (to, from, next) => {

                        if(auth.user)
                        {
                            next({ })

                        }
                        else
                        {
                            next({ path: '/login' })
                        }

                    },
                },
                {
                    path: '/change_mobile',
                    name: 'change_mobile',
                    component: ChangeMobile,
                    beforeEnter: (to, from, next) => {

                        if(auth.user)
                        {
                            next({ })

                        }
                        else
                        {
                            next({ path: '/login' })
                        }

                    },
                },
                {
                    path: '/chat',
                    name: 'chat',
                    component: Chat,
                    props: true,
                    beforeEnter: (to, from, next) => {

                        if(auth.user)
                        {
                            next({ })

                        }
                        else
                        {
                            next({ path: '/login' })
                        }

                    },
                },
                {
                    path: '/user_posts',
                    name: 'UserPosts',
                    component: UserPosts,
                    beforeEnter: (to, from, next) => {

                        if(auth.user)
                        {
                            next({ })

                        }
                        else
                        {
                            next({ path: '/login' })
                        }

                    },
                },
                {
                    path: '/rules',
                    name: 'rules',
                    component: Rules,

                },

                {
                    path: '/admin',
                    name: 'AdminMain',
                    component: AdminMain,

                    beforeEnter: (to, from, next) => {

                        let is_admin_ent =''
                        axios
                            .post('/is_admin',{
                            }).then(response => {
                            if(response.data.length != 0)
                            {
                                is_admin_ent=response.data[0].status
                                if(is_admin_ent==1)
                                {
                                    next({ })

                                }
                                else
                                {
                                    next({ path: '/' })
                                }
                            }

                        })

                    },
                    children: [
                        {
                            path: '/admin',
                            name: 'MainViewAdmin',
                            component: MainViewAdmin,
                        },
                        {
                            path: '/UsersAdmin',
                            name: 'UsersAdmin',
                            component: UsersAdmin,
                        },
                        {
                            path: '/PostsAdmin',
                            name: 'PostsAdmin',
                            component: PostsAdmin,
                        }

                        ]
                },

                {
                    path: '/add_advertisement',
                    name: 'add_advertisement',
                    component: Add_advertisement,
                    beforeEnter: (to, from, next) => {
                        if(auth.user)
                        {
                            axios
                                .post('/is_ban', {
                                })
                                .then(({data}) => {
                                    if (data.status == 'fail') {
                                        next({ path: '/ban' })
                                    }
                                    else
                                    {
                                        next({ })
                                    }
                                })
                        }
                        else
                        {
                            next({ path: '/login' })
                        }

                    },
                    children: [

                        {
                            path: '/add_advertisement',
                            name: 'add_advertisement',
                            component: Add_advertisement1,
                        },
                        {
                            path: '/add_advertisement2',
                            name: 'add_advertisement2',
                            component: Add_advertisement2,
                            beforeEnter: (to, from, next) => {
                                if(from.path =='/add_advertisement'){
                                    next()
                                }
                                else
                                {
                                    next({ path: '/' })
                                }
                            }
                        },
                        {
                            path: '/add_advertisement3',
                            name: 'add_advertisement3',
                            component: Add_advertisement3,
                        },
                        {
                            path: '/posts/:id',
                            name: 'OnePost',
                            component: OnePost,
                            props: true,
                        },
                    ]
                },



                {
                    path: '*',
                    component: NotFound ,
                },

                ]
        },




    ],
});

Vue.router = router;

const app = new Vue({
    el: '#app',
    components: { App, Loading_line , modal},
    router,
    store
});
