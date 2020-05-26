<template>
    <div id="chat_body">
    <div id="frame">
        <div id="sidepanel">
            <div id="profile">
                <div class="wrap">
                    <img :src="image_src" id="profile-img" class="online" alt="">
                    <p>{{ user['name'] }}</p>
                    <i class="fa fa-chevron-down expand-button" aria-hidden="true"></i>
                    <div id="status-options">
                        <ul>
                            <li id="status-online" class="active"><span class="status-circle"></span> <p>Online</p></li>
                            <li id="status-away"><span class="status-circle"></span> <p>Away</p></li>
                            <li id="status-busy"><span class="status-circle"></span> <p>Busy</p></li>
                            <li id="status-offline"><span class="status-circle"></span> <p>Offline</p></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div id="contacts">
                <ul>
                    <li class="contact" v-for="(user,number) in users_dialogs_list" v-on:click="click_user(user)">
                        <div class="wrap">
                            <span class="contact-status online"></span>
                            <img :src="user.avatar" alt="" />
                            <div class="meta">
                                <p class="name">{{ user.name }}</p>
                                <p class="preview" v-if="user.chat[user.chat.length -1]">{{ user.chat[user.chat.length -1]['message_text'] }}</p>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
        <div class="content">
            <div class="contact-profile">
                <img :src="user_to_message_ava" alt="" />
                <p>{{ user_to_message_name }}</p>
            </div>
            <div class="messages" id="block_messages">
                <ul>
                    <span v-for="(message,number) in chat_messages">
                    <li class="sent" v-if="message.from_user_id==user['id']">
                        <img :src="image_src" alt="" />
                        <p v-if="message.img == 1"><img :src="'/images/chat/'+message.message_text" alt="" class="img_chat" /></p>
                        <p v-else>{{ message.message_text }}</p>
                    </li>
                    <li class="replies" v-else>
                        <img :src="user_to_message_ava" alt="" />
                        <p v-if="message.img == 1"><img :src="'/images/chat/'+message.message_text" alt="" class="img_chat"/></p>
                        <p v-else>{{ message.message_text }}</p>
                    </li>
                        </span>
                </ul>
            </div>
            <div class="message-input">
                <div class="wrap">
                    <input ref="to_focus" v-model="message_text_to" type="text" placeholder="Write your message..." />
                    <input type="file" id="files" ref="files" accept="image/*" multiple v-on:change="handleFilesUpload()"/>
                    <i class="fa fa-paperclip attachment" aria-hidden="true" v-on:click="addFiles()"></i>
                    <button class="submit" v-on:click="send_message()"><i class="fa fa-paper-plane" aria-hidden="true"></i></button>
                </div>
            </div>
            <modal v-if="showModal" @close="close_modal" v-bind:alert_message=this.alert_message>
                <h3 slot="link">Выберите блок</h3>
            </modal>
        </div>
    </div>
    </div>
</template>

<script>
    export default {

        data() {
            return {
                showModal:false,
                alert_message:[],
                files: [],
                user_from_props:'',
                image_src:'',
                user:'',
                users_dialogs_list:[],
                user_to_message_name:'',
                user_to_message_ava:'',
                user_to_message_id:'',
                chat_messages:[],
                message_text_to:'',
                offset_arr:[],
                block_mes:'',
                height_block_mes:0,
                windowTop:0,
                null_scroll_flag:0,
                different_hight:0,
                click_flag:0
            }
        },
        props:['to_user_props'],
        mounted() {
            this.user = auth.user;
            this.check_ava();
             this.get_user_from_props();
            this.scroll();


         //   window.addEventListener("scroll", this.onScroll)


            window.Echo.channel('laravel_database_private-translation.' + auth.user.id)
                .listen('TranslationEvent', (e) => {
                    this.get_message(e);
                    console.log(e);
                });

        },
        beforeDestroy() {
            window.removeEventListener("scroll", this.onScroll)
        },
        created() {
        },
//срабатывает при изменении содержимого dom
        updated:
            function () {
                this.$nextTick(function () {
                     if(this.null_scroll_flag == 0){
                        this.scroll()
                        // let objDiv1 = document.getElementById("block_messages");
                               //  console.log(objDiv1.scrollTop)
                               //  console.log(objDiv1.scrollHeight - 487)
                             //   console.log('TTT')
                         // if(this.click_flag ==1 )
                         // {
                         // }

                     }
                     else
                     {
                         let objDiv1 = document.getElementById("block_messages");
                         // console.log("result" +  (objDiv1.scrollHeight-this.different_hight));
                         objDiv1.scrollTop = (objDiv1.scrollHeight-this.different_hight);
                     }

                    let objDiv1 = document.getElementById("block_messages");
                    objDiv1.addEventListener("scroll", this.onScroll);

                })
            },


                methods: {

                    close_modal()
                    {
                        this.showModal=false;
                    },

                    handleFilesUpload(){
                        let flag = 0;
                        let uploadedFiles = this.$refs.files.files;
                        for( let i = 0; i < uploadedFiles.length; i++ ) {

                            if (/\.(jpe?g|png)$/i.test(uploadedFiles[i].name)) {

                                if (uploadedFiles[i]['size'] > 8388608) {
                                    flag = 1;
                                    this.alert_message.push('Допустимый размер файла 8мб')
                                } else {
                                    this.files.push(uploadedFiles[i]);
                                }

                            } else {
                                flag = 1;
                                this.alert_message.push('Не верный формат файла')
                            }

                            if (flag == 1) {
                                let sortArr = this.alert_message.filter((it, index) => index === this.alert_message.indexOf(it = it.trim()));
                                this.alert_message = sortArr;
                                this.showModal = true;
                            }
                        }

                                let formData = new FormData();
                                for( let i = 0; i < this.files.length; i++ ){
                                       if(i==5)
                                       {
                                           break;
                                       }
                                    let file = this.files[i];
                                    formData.append('files[' + i + ']', file);
                                }
                        formData.append('user_to', this.user_to_message_id);
                         this.files=[];
                        axios.post( '/store_pic_chat',
                                    formData,
                                    {
                                        headers: {
                                            'Content-Type': 'multipart/form-data'
                                        }
                                    }
                                )

                            .then(({data}) => {

                                if (data.status == 'success') {

                                    for(let i = 0; i < this.users_dialogs_list.length; i++)
                                    {
                                        if(this.users_dialogs_list[i]['id']==this.user_to_message_id)
                                        {
                                            for(let k = 0; k < data.mes.length; k++)
                                            {
                                                console.log(data.mes)
                                                this.users_dialogs_list[i]['chat'].push(data.mes[k]);
                                            }
                                        }
                                    }

                                    for(let j = 0; j < this.offset_arr.length; j++)
                                    {
                                        if(this.offset_arr[j]['id']==this.chat_messages[0]['us_dialogs_id'])
                                        {
                                            this.offset_arr[j]['offset']+=data.mes.length
                                        }
                                    }

                                }})


                    },

                    addFiles(){
                        this.$refs.files.click();
                    },

                    onScroll(e) {
                       let objDiv = document.getElementById("block_messages");
                        this.windowTop = e.target.scrollTop
                         // console.log(e.target.scrollTop)

                      //  if(this.click_flag == 1){

                        //    this.scroll()
                        //    console.log('flag@@@' + e.target.scrollTop)
                        //    console.log( objDiv.scrollHeight - 487)

                        //    if((e.target.scrollTop) == objDiv.scrollHeight - 487)
                        //    {
                          //      this.click_flag=0
                        //        console.log('da@@@' + e.target.scrollTop)

                         //   }
                     //   }



                       // objDiv.scrollTop = objDiv.scrollHeight;

                        // console.log("first" + objDiv.scrollHeight)
                        if(this.windowTop == 0)
                        {
                            for(let i = 0; i < this.offset_arr.length; i++)
                            {
                                if(this.offset_arr[i]['id']==this.chat_messages[0]['us_dialogs_id'])
                                {
                                    // this.offset_arr[i]['offset'] +=10;
                                    this.different_hight = objDiv.scrollHeight;
                                    this.get_user_dialogs_after_scroll(this.chat_messages[0]['us_dialogs_id'],this.offset_arr[i]['offset'] + 10)
                                    // let objDiv = document.getElementById("block_messages");
                                    //  console.log("second" +  objDiv.scrollHeight)
                                    this.null_scroll_flag = 1;
                                }
                            }

                        }
                    },

                    get_user_dialogs_after_scroll(us_dialogs_id, offset)
                    {
                        axios
                            .post('/get_data_scroll_user',{
                                us_dialogs_id:us_dialogs_id,
                                offset:offset
                            })
                            .then(({ data }) =>
                            {
                                if(data.length != 0)
                                {

                                    let chat_arr =[];
                                    data.forEach(function(entry) {

                                        chat_arr.push({
                                            id:entry.id,
                                            from_user_id:entry.from_user_id,
                                            to_user_id:entry.to_user_id,
                                            message_text:entry.message_text,
                                            us_dialogs_id:entry.us_dialogs_id
                                        });

                                    })


                                    for (let j = 0; j < this.users_dialogs_list.length; j++) {
                                        if(this.users_dialogs_list[j]['chat'][0]['us_dialogs_id']==chat_arr[0]['us_dialogs_id'])
                                        {
                                            for (let k = chat_arr.length-1; k >=0; k--) {
                                                this.users_dialogs_list[j]['chat'].unshift(chat_arr[k])
                                            }
                                        }
                                    }

                                for(let j = 0; j < this.offset_arr.length; j++)
                                {
                                    if(this.offset_arr[j]['id']==chat_arr[0]['us_dialogs_id'])
                                    {
                                        this.offset_arr[j]['offset']+=chat_arr.length
                                    }
                                }
                                }
                            })



                    },

                    get_user_from_props()
                    {
                        if((this.to_user_props !=undefined) && (this.to_user_props !=auth.user.id))
                        {

                            axios
                                .post('/get_data_props_user',{
                                    to_user_props:this.to_user_props
                                })
                                .then(({ data }) =>
                                {
                                    if(data.length != 0)
                                    {

                                        let users_arr =[];
                                        data.forEach(function(entry) {

                                            users_arr.push({
                                                id:entry.id,
                                                name:entry.name,
                                                avatar:entry.img_ava,
                                                chat:entry.chat
                                            });

                                        })

                                        this.users_dialogs_list.push(users_arr[0]);
                                        this.click_user(users_arr[0]);
                                    }

                                    this.get_users_with_dialogs();
                                })
                        }
                        else
                        {
                            this.get_users_with_dialogs();
                        }
                    },

                    get_message(message)
                    {
                        let us_dialogs_id ='';
                        for(let i = 0; i < this.users_dialogs_list.length; i++)
                        {

                            if(this.users_dialogs_list[i]['id']==message.from_user)
                            {

                                if(message.from_user>message.id)
                                {
                                    us_dialogs_id=message.id + '_' + message.from_user
                                }
                                else
                                {
                                    us_dialogs_id=message.from_user + '_' + message.id
                                }

                                let user_message = new Object(); // синтаксис "конструктор объекта"
                                user_message = {
                                    from_user_id: message.from_user,
                                    message_text: message.message,
                                    to_user_id: message.id,
                                    us_dialogs_id:us_dialogs_id,
                                    img:message.img
                                };
                                this.users_dialogs_list[i]['chat'].push(user_message);
                            }
                        }

                        for(let j = 0; j < this.offset_arr.length; j++)
                        {
                            if(this.offset_arr[j]['id']==us_dialogs_id)
                            {
                                this.offset_arr[j]['offset']+=1
                            }
                        }

                        this.scroll();
                    },

                    send_message()
                    {
                        axios
                            .post('/save_message_chat',{
                                user_to:this.user_to_message_id,
                                message:this.message_text_to,
                            })
                            .then(({data}) => {

                                if (data.status == 'success') {

                                    for(let i = 0; i < this.users_dialogs_list.length; i++)
                                    {
                                        if(this.users_dialogs_list[i]['id']==this.user_to_message_id)
                                        {
                                            this.users_dialogs_list[i]['chat'].push(data.mes);
                                        }
                                    }

                                    for(let j = 0; j < this.offset_arr.length; j++)
                                    {
                                        if(this.offset_arr[j]['id']==this.chat_messages[0]['us_dialogs_id'])
                                        {
                                            this.offset_arr[j]['offset']+=1
                                        }
                                    }

                                }
                            })


                        this.message_text_to='';
                        this.scroll();
                    },


                    scroll()
                    {
                        let objDiv = document.getElementById("block_messages");
                        objDiv.scrollTop = objDiv.scrollHeight;
                        // console.log(objDiv.scrollTop);
                    },

                    alert_man()
                    {
                        alert('MAAAN')
                    },

                    click_user(mod_user)
                    {
                        // let objDiv1 = document.getElementById("block_messages");
                          // console.log(objDiv1.scrollTop)
                          // console.log(objDiv1.scrollHeight - 487)
                        //убрать дублирование при клике
                            this.different_hight=0;
                            this.null_scroll_flag=0;

                        this.user_to_message_name=mod_user.name,
                            this.user_to_message_ava=mod_user.avatar,
                            this.user_to_message_id=mod_user.id,
                                this.$forceNextTick(() => {
                                      this.chat_messages=mod_user.chat
                                }),
                            setTimeout(this.scroll, 300);
                        //  this.chat_messages=mod_user.chat,
                            this.$refs.to_focus.focus();

                    },

                    check_ava()
                    {
                        axios
                            .post('/check_ava',{
                            }).then(response => {
                            if(response.data=='1')
                            {
                                this.image_src='/images/avatars/'+auth.user['id']

                            }
                            else
                            {
                                this.image_src='/images/avatars/0'
                            }
                        })
                    },

                    set_offset_null()
                    {
                        for (let j = 0; j < this.users_dialogs_list.length; j++) {
                            this.offset_arr.push({
                                id:this.users_dialogs_list[j]['chat'][0]['us_dialogs_id'],
                                offset:0
                            })

                        }

                    },

                    get_users_with_dialogs()
                    {
                        axios
                            .post('/get_users_with_dialogs',{
                                offset_arr:this.offset_arr
                            }).then(({ data }) =>
                        {
                            if(data.length != 0)
                            {

                                let users_arr =[];
                                data.forEach(function(entry) {
//вытаскиваю юзеров кому написал
                                    users_arr.push({
                                        id:entry.id,
                                        name:entry.name,
                                        avatar:entry.img_ava,
                                        chat:entry.chat
                                    });

                                })

                                //убираем повторы чата если они есть
                                for (let i = 0; i < users_arr.length; i++) {
                                    let flag =0;
                                    for (let j = 0; j < this.users_dialogs_list.length; j++) {
                                        if(this.users_dialogs_list[j]['id']==users_arr[i]['id'])
                                        {
                                            flag =1;
                                        }
                                    }
                                    if(flag == 0)
                                    {
                                        this.users_dialogs_list.push(users_arr[i]);
                                    }

                                }

                            }

                            this.set_offset_null();

                        })

                    }



                }
            }

</script>
