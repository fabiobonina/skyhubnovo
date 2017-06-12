<template>
    
    <div id="app"class="phone-viewport">
        <md-toolbar>
            <md-button class="md-icon-button" @click.native="toggleLeftSidenav">
                <md-icon>menu</md-icon>
            </md-button>
            <h1 class="md-title" style="flex: 1">{{ title }}</h1>

            <md-button class="md-fab md-clean md-mini" @click.native="toggleRightSidenav">
                <md-avatar>
                    <img :src="currentUser.photoURL" alt="Avatar">
                </md-avatar>
            </md-button>
        </md-toolbar>
        <md-sidenav class="md-left" ref="leftSidenav" @open="open('Left')" @close="close('Left')">
        <div>
            <md-toolbar class="md-dense">
            <div class="md-toolbar-container">
                <h3 class="md-title">Menu</h3>
            </div>
            </md-toolbar>
            <md-list>
            <md-list-item>
                <router-link to="/clientes">Clientes</router-link>
            </md-list-item>
            <md-list-item>
                <router-link to="/localidades">Localidades</router-link>
            </md-list-item>
            <md-list-item>Sair</md-list-item>
            </md-list>
        </div>
        </md-sidenav>

    <md-sidenav class="md-right" ref="rightSidenav" @open="open('Right')" @close="close('Right')">
    <md-toolbar>
      <div class="md-toolbar-container">
        <h3 class="md-title">{{ currentUser.displayName }}</h3>
        <md-avatar class="md-right">
        <img :src="currentUser.photoURL" alt="Avatar">
        </md-avatar>
      </div>
      <connected-user></connected-user>
    </md-toolbar>

    <md-button class="md-raised md-accent" @click.native="closeRightSidenav">Close</md-button>
  </md-sidenav>
  <md-layout md-gutter>
    <md-layout md-flex="33">
        <channels></channels>   
    </md-layout>
    <md-layout>
    
    </md-layout>
  </md-layout>
  </div>

</template>

<script>
import { mapGetters } from 'vuex'
import ConnectedUser from './ConnectedUser'
import Channels from './Channels'
export default {
    name: 'app',
    components: {
        ConnectedUser, Channels
    },
    computed: {
        ...mapGetters(['currentUser'])
    },
    data () {
        return {
        title: 'Hello Vue!',
        presenceRef: firebase.database().ref('presence')
        }
    },
    methods: {
        toggleLeftSidenav () {
            this.$refs.leftSidenav.toggle()
        },
        toggleRightSidenav () {
            this.$refs.rightSidenav.toggle()
        },
        closeRightSidenav () {
            this.$refs.rightSidenav.close()
        },
        open (ref) {
            console.log('Opened: ' + ref)
        },
        close (ref) {
            console.log('Closed: ' + ref)
        }
    }
}
</script>

<style scoped>



</style>