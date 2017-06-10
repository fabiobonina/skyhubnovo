Vue.use(VueMaterial);

Vue.prototype.$http = axios;

var app = new Vue({
    el: "#root",
    data() {
        return{
            showingAddModal: false,
            showingEditModal: false,
            showingDeleteModal: false,
            errorMessage: "",
            successMessage: "",
            user: {
                nome: "",
                email: "",
                user: "",
            },
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
                    //console.log(this.users);
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
            //});
        },
        saveUser: function(){
            //console.log(this.user);
            var formData = this.toFormData(this.user);
            //console.log(this.formData);
            axios.post("http://localhost/codephp/skyhubnovo/api/apiphp.php?action=create", formData)
            .then((response) => {
                this.user = { nome: "", email: "", user: "" };
                if(response.data.error){
                    this.errorMessage = response.data.message;
                } else{
                    this.getAllUsers();
                    //console.log(this.users);
                }
            });
        },
        selecUser(user){
            this.user = user;
            this.showingAddModal = true;
            var formData = this.toFormData(this.user);
            //console.log(this.formData);
            axios.post("http://localhost/codephp/skyhubnovo/api/apiphp.php?action=create", formData)
            .then((response) => {
                this.user = { nome: "", email: "", user: "" };
                if(response.data.error){
                    this.errorMessage = response.data.message;
                } else{
                    this.getAllUsers();
                    //console.log(this.users);
                }
            });
        },
        updateUser: function(){
            //console.log(this.user);
            var formData = this.toFormData(this.user);
            //console.log(this.formData);
            axios.post("http://localhost/codephp/skyhubnovo/api/apiphp.php?action=create", formData)
            .then((response) => {
                this.user = { nome: "", email: "", user: "" };
                if(response.data.error){
                    this.errorMessage = response.data.message;
                } else{
                    this.getAllUsers();
                    //console.log(this.users);
                }
            });
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