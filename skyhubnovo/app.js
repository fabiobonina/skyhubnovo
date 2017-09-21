Vue.use(VueMaterial);

Vue.prototype.$http = axios;

var app = new Vue({
    el: "#root",
    data() {
        return{
            showingAddModal: false,
            showingEditModal: false,
            showingDeletModal: false,
            errorMessage: "",
            successMessage: "",
            newUser: { nome: "", email: "", user: "" },
            modalUser: {},
            users: []
            
        }
    },
    mounted: function(){
        console.log("bonina");
        this.getAllUsers();
    },
    methods: {
        getAllUsers: function(){
            this.$http.get('http://localhost/codephp/skyhubnovo/api/apiphp.php?action=read')
            .then((response) => {
                if(response.data.error){
                    this.errorMessage = response.data.message;
                } else{
                    this.users = response.data.users;
                }
            });
        },
        saveUser: function(){
            var formData = this.toFormData(this.newUser);
            axios.post("http://localhost/codephp/skyhubnovo/api/apiphp.php?action=create", formData)
            .then((response) => {
                this.newUser = { nome: "", email: "", user: "" };
                if(response.data.error){
                    this.errorMessage = response.data.message;
                } else{
                    this.successMessage = response.data.message;
                    this.getAllUsers();
                }
            });
        },
        updateUser: function(){
            var formData = this.toFormData(this.modalUser);
            axios.post("http://localhost/codephp/skyhubnovo/api/apiphp.php?action=update", formData)
            .then((response) => {
                this.modalUser = {};
                if(response.data.error){
                    this.errorMessage = response.data.message;
                } else{
                    this.successMessage = response.data.message;
                    this.getAllUsers();
                }
            });
        },
        deleteUser: function(){
            var formData = this.toFormData(this.modalUser);
            axios.post("http://localhost/codephp/skyhubnovo/api/apiphp.php?action=delete", formData)
            .then((response) => {
                this.modalUser = {};
                if(response.data.error){
                    this.errorMessage = response.data.message;
                } else{
                    this.successMessage = response.data.message;
                    this.getAllUsers();
                }
            });
        },
        selecUser: function(user){
            this.modalUser = user;
        },
        toFormData: function(obj){
            var form_data = new FormData();
                for ( var key in obj ){
                    form_data.append(key, obj[key]);
                }
                return form_data;
        },
        clearMassege: function(){
            this.errorMessage = "";
            this.successMessage = "";
        }
    }
});