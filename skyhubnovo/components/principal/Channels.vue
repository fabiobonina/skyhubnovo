<template>
    
    <div class="channels__container">
        <md-button class="md-fab md-fab-bottom-right" id="fab" @click.native="openDialog('dialog2')">
            <md-icon>add</md-icon>
        </md-button>
        <h2 class="ui inverted center aligned header">Canais <i class="add square icon add_channel" @click="openChannelModal"></i></h2>
        <div class="ui raised padded segment channels__list">
            <div class="phone-viewport">
                <md-list class="custom-list md-triple-line md-dense" 
                v-for="cliente in channels" 
                :key="cliente.id" 
                :class="{'is_active': setChannelActive(cliente)}"
                @click="changeChannel(cliente)"
                >
                    <md-list-item>
                        <div class="md-list-text-container">
                            <span> {{ cliente.nick }} </span>
                            <span> {{ cliente.nome }} </span>
                        </div>

                        <md-button class="md-icon-button md-list-action">
                            <md-icon class="md-primary">star</md-icon>
                        </md-button>

                        
                        <div class="ui label purple channel__count" v-if="getNotification(cliente) > 0 && cliente.id !== currentChannel.id">{{ getNotification(cliente) }}</div>
                    </md-list-item>
                    <md-divider class="md-inset"></md-divider>
                </md-list>
            </div>
        </div>

        <!-- Modal Pour ajouter un Channel -->

        <div class="ui basic modal" id="channelModal">
            <div class="ui icon header">
                Adicionar Cliente
            </div>

            <div class="content">
                <div class="ui inverted form" :class="{'error': hasErrors }">
                    <div class="field">
                        <label for="new_channel">Nome do canal</label>
                        <input type="text" name="new_channel" v-model="new_channel" id="new_channel">
                    </div>

                    <div class="ui error message" v-if="hasErrors">
                        <p v-for="error in errors">{{ error }}</p>
                    </div>
                </div>
            </div>

            <div class="actions">
                <div class="ui red basic cancel inverted button">
                    <i class="remove icon"></i>Cancelar
                </div>
                <div class="ui green inverted button" @click="addChannel">
                    <i class="checkmark icon"></i>Adicionar
                </div>
            </div>


        </div>

    </div>

</template>

<script>

    import {mapGetters} from 'vuex'
    import mixin from '../mixins'

    export default {
        name: 'channels', 
        data () {
            return {
                channels: [],
                new_channel: '',
                channelsRef: firebase.database().ref('clientes'),
                messagesRef: firebase.database().ref('messages'),
                errors: [],
                firstLoad: true,
                notifCount: [],
                channel: null
            }
        },
        mixins: [mixin],
        computed : {
            ...mapGetters(['currentChannel', 'isPrivate']),
            hasErrors () {
                return this.errors.length > 0
            }
        },
        watch : {
            isPrivate(){                
                if(this.isPrivate){
                    this.resetNotifications()
                }
            }
        },
        mounted () {
            this.addListeners()
        },
        methods: {
            addListeners () {
                this.channelsRef.on('child_added', snap => {
                    this.channels.push(snap.val())
                    
                    if(this.firstLoad && this.channels.length > 0){
                        this.$store.dispatch("setCurrentChannel", this.channels[0])
                        this.channel = this.channels[0]
                    }
                    this.firsLoad = false

                    //Rajout du listener pour gerér les notifications
                    this.addCountListener(snap.key)

                })
            },
            addCountListener(channelId){
                this.messagesRef.child(channelId).on('value', snap => {
                    this.handleNotifications(channelId, this.currentChannel.id, this.notifCount, snap)
                })
            },            
            getNotification(channel){
                let notif = 0

                this.notifCount.forEach(el => {
                    if(el.id === channel.id){
                        notif = el.notif
                    }
                })
                return notif

            },
            openChannelModal () {
                $("#channelModal").modal('show')
            },
            addChannel () {
                this.errors = []
                let key = this.channelsRef.push().key

                let newChannel = { id: key, name: this.new_channel }
                this.channelsRef.child(key).update(newChannel).then( () => {

                    this.new_channel = ''
                    $("#channelModal").modal('hide')                 

                }).catch( error => {
                    this.errors.push(error.message)
                })
            },
            changeChannel(channel){   
                this.resetNotifications()                          
                this.$store.dispatch('setPrivate', false)
                this.$store.dispatch('setCurrentChannel', channel)
                this.channel = channel
            },            
            resetNotifications(){
                let index = this.notifCount.findIndex( el => el.id === this.channel.id)
                if(index !== -1){
                    this.notifCount[index].total = this.notifCount[index].lastKnownTotal
                    this.notifCount[index].notif = 0
                }
            },
            detachListeners () {
                this.channelsRef.off()
                this.channels.forEach( el => {
                    this.messagesRef.child(el.id).off()
                })
            },
            setChannelActive(channel){
                return channel.id === this.currentChannel.id
            }
        },
        beforeDestroy () {
            this.detachListeners()
        }
    }
</script>

<style scoped>
    .channels__list {
        min-height: 100px;
        max-height: 300px!important;
        overflow-y: auto!important;
    }
    .channels__container ul{
        margin: 0;
        padding: 0;
    }
    .channels__item{
        height: 30px;
        margin: 8px;
        list-style: none;
        background-color: #ca67ff;
        cursor: pointer;
        line-height: 30px;       
        border-radius: 2px;
        padding-left: 12px;
        font-weight: bold;
        font-size: 1.1em;
    }
    .channel__count{
        float:right;
    }
    .is_active{
        background-color: #9740c5;
    }
    .channels__item:hover{
        background-color: #9740c5;
    }
    .add_channel{
        cursor: pointer;
        color: #8BC34A;
    }
    .add_channel:hover{
        color: #689F38;
    } 
</style>