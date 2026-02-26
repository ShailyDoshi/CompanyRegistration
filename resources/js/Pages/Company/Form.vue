<template>
    <div
        style="
        min-height: 100vh;
        display: flex;
        justify-content: center;
        align-items: flex-start;
        padding: 20px;
        background-color: #f4f6f9;
        "
    >
        <div
            style="
                width: 900px;
                height: 600px;
                background: #ffffff;
                padding: 20px;
                border-radius: 10px;
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
                transition: 0.3s ease;
            "
        >
            <!-- Back Button  -->
            <div style="justify-content: space-between; display: flex;">
                <h2 style="margin-bottom: 10px; font-size: 20px; font-weight: bold;">Add Company</h2>
                <button 
                    @click="router.visit(companyRoutes.index)" 
                    style="
                        background-color: #2563eb; 
                        color: #ffffff; 
                        border: none; 
                        border-radius: 6px; 
                        width: 80px;
                    "
                >
                    Back
                </button>
            </div>
            <!-- Success Message -->
            <div 
                v-if="successMessage"
                style="
                    margin-top: 15px;
                    padding: 10px 15px;
                    background-color: #d1fae5;
                    color: #065f46;
                    border-radius: 6px;
                    border: 1px solid #10b981;
                "
            >
                {{ successMessage }}
            </div>

            <div 
                style="
                    margin-top: 30px;
                    display: flex;
                    gap: 20px;
                "
            >
                <!-- name -->
                <div style="flex: 1; display: flex; flex-direction: column;">
                    <label>Name</label>
                    <input
                        v-model="form.name" 
                        type="text" 
                        placeholder="Enter Name" 
                        style="
                            padding: 8px 10px; 
                            border: 1px solid #ccc; 
                            border-radius: 6px; 
                            outline: none;
                        "
                    />
                    <p v-if="error.name" style="color: red;">{{ error.name[0] }}</p>
                </div>
                <!-- email -->
                <div style="flex: 1; display: flex; flex-direction: column;">
                    <label>Email</label>
                    <input 
                        v-model="form.email"
                        type="email" 
                        placeholder="Enter Email" 
                        style="
                            padding: 8px 10px; 
                            border: 1px solid #ccc; 
                            border-radius: 6px; 
                            outline: none;
                        "
                    />
                    <p v-if="error.email" style="color: red;">{{ error.email[0] }}</p>
                </div>
            </div>

            <div 
                style="
                    margin-top: 30px;
                    display: flex;
                    gap: 20px;
                "
            >
                <!-- logo -->
                <div style="flex: 1; display: flex; flex-direction: column; margin-top: 20px;">
                    <label>Logo</label>
                    <input 
                        type="file"
                        @change="handleFile"
                        accept="image/*"
                    />
                    <p v-if="error.logo" style="color: red;">{{ error.logo[0] }}</p>
                </div>
                <!-- status -->
                <div style="flex: 1; display: flex; flex-direction: column;">
                    <label>Status</label>
                    <select
                        v-model="form.status"
                        style="
                            padding: 8px 10px; 
                            border: 1px solid #ccc; 
                            border-radius: 6px; 
                            outline: none;
                        "
                    >
                        <option :disabled="true">Select Status</option>
                        <option :value="0">Close</option>
                        <option :value="1">Open</option>
                    </select>
                    <p v-if="error.status" style="color: red;">{{ error.status[0] }}</p>
                </div>  
            </div>
            <button
                @click="handleSubmit"
                style="
                    background-color: #2563eb; 
                    color: #ffffff; 
                    border: none; 
                    border-radius: 6px; 
                    width: 80px;
                    height: 40px;
                    margin-top: 10px;
                "
            >
                Submit
            </button> 
        </div>

    </div>
</template>
<script setup>
import { router } from '@inertiajs/vue3';
import { companyRoutes } from '../../routes/CompanyRoutes';
import { onMounted, reactive, ref } from 'vue';
import axios from 'axios';

const props = defineProps({
    company:{
        type: [Object,null],
    }
})

const form = reactive({
    id: '',
    name: '',
    email: '',
    logo: null,
    status: 1,
});

const successMessage = ref('');
const error = ref({});

function handleFile(event){
    form.logo = event.target.files[0];
}

function handleSubmit()
{
    const formData = new FormData();
    formData.append('id',form.id);
    formData.append('name',form.name);
    formData.append('email',form.email);
    formData.append('status',form.status);

    if(form.logo){
        formData.append('logo',form.logo)
    }

    axios
        .post(companyRoutes.createOrUpdate(form.id),formData,{
            headers:{
                'Content-Type':'multipart/form-data'
            }
        })
        .then((response)=>{
            successMessage.value = response.data.message;
            window.location.href = companyRoutes.index;
            clearData();
        })
        .catch(function (err){
            if(err.response?.status === 422)
            {
                error.value = err.response.data.errors;
            }
        })
}

function clearData()
{
    error.value = {};
    form.id = '';
    form.name = '';
    form.email = '';
    form.status = 1;
    form.logo = nulll

}

onMounted(()=>{
    if(props.company){
        Object.assign(form,{
            id: props.company.id,
            name: props.company.name,
            email: props.company.email,
            status:props.company.status,
        })
    }
})
</script>