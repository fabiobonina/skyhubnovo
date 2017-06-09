Vue.use(VueMaterial);

Vue.prototype.$http = axios;

var app = new Vue({
    el: "#root",
    mounted: function(){
        console.log("bonina");
        this.getAllUsers();
    },
    methods: {
        getAllUsers: function(){
            this.$http.get('http://localhost:83/codephp/skyhubnovo/apiphp/apiphp.php?action=read')
            .then((response) => {
                if(response.data.error){
                    this.errorMessage = response.data.message;
                } else{
                    this.users = response.data.users;
                    console.log(this.users);
                }
            });
            //axios.get("http://localhost:83/codephp/skyhubnovo/apiphp/apiphp.php?action=read")
            //.then(function(response){
            //    if(response.data.error){
            //        this.errorMessage = response.data.message;
            //    } else{
            //        this.users = response.data;
            //        console.log(this.users);
            //    }
           // });
        }
    },
    data() {
        return{
            showingAddModal: false,
            showingEditModal: false,
            showingDeleteModal: false,
            errorMessage: "",
            successMessage: "",
            users: [],
            user: {
                nome: "",
                email: "",
                user: "",
            }
        }
    }
});